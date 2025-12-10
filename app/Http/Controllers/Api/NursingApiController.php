<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Visit;
use App\Models\NursingNote;
use Illuminate\Support\Facades\Hash;

class NursingApiController extends Controller
{
    // Login
    public function settings()
    {
        return response()->json([
            'hospital' => config('hms.hospital'),
        ]);
    }

    public function seedDemoData()
    {
        // Create Demo Patient
        $patient = \App\Models\Patient::firstOrCreate(
            ['email' => 'john.dao@example.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Dao',
                'date_of_birth' => '1980-01-01',
                'gender' => 'male',
                'phone' => '08012345678',
                'address' => '123 Main St',
                'hospital_id' => 'HMS-P-' . rand(1000, 9999),
                'password' => Hash::make('password'),
            ]
        );

        // Create Demo Ward/Bed
        $ward = \App\Models\Ward::firstOrCreate(['name' => 'General Ward']);
        $bed = \App\Models\Bed::firstOrCreate(
            ['ward_id' => $ward->id, 'number' => 'G-01'],
            ['is_occupied' => true]
        );

        // Create Active Visit
        $doctor = User::where('role', 'doctor')->first();
        if (!$doctor) {
            $doctor = User::create([
                'name' => 'Dr. Strange',
                'email' => 'doctor@hms.com',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);
        }
        
        // Create Dummy Dept/Service
        $dept = \App\Models\Department::firstOrCreate(['name' => 'General Medicine'], ['code' => 'GM-001']);
        $service = \App\Models\Service::firstOrCreate(['name' => 'Consultation'], ['price' => 5000, 'department_id' => $dept->id, 'code' => 'SVC-001']);

        $visit = Visit::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'ward_id' => $ward->id,
            'bed_id' => $bed->id,
            'department_id' => $dept->id,
            'service_id' => $service->id,
            'status' => 'admitted',
            'admission_date' => now(),
            'symptoms' => 'Fever, Headache',
            'diagnosis' => 'Malaria',
        ]);

        return response()->json(['message' => 'Demo data seeded!', 'visit' => $visit]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        // Ensure user has nurse role (or doctor/admin for testing)
        // if (!$user->hasRole('nurse')) { ... }

        $token = $user->createToken('nursing-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'hospital' => config('hms.hospital'),
        ]);
    }

    // Dashboard Stats
    public function dashboard()
    {
        // For simplicity, count all admitted patients
        // In real app, filter by nurse's ward
        $admittedCount = Visit::where('status', 'admitted')->count();
        $pendingTasks = 5; // Placeholder

        return response()->json([
            'admitted_patients' => $admittedCount,
            'pending_tasks' => $pendingTasks,
        ]);
    }

    // Patient List (Admitted)
    public function patients()
    {
        $patients = Visit::where('status', 'admitted')
            ->with(['patient', 'ward', 'bed'])
            ->latest()
            ->paginate(20);

        return response()->json($patients);
    }

    // Patient Details
    public function patientDetails($id)
    {
        $visit = Visit::with(['patient', 'doctor', 'ward', 'bed', 'nursingNotes', 'prescriptions'])
            ->findOrFail($id);
            
        return response()->json($visit);
    }

    // Store Vitals (using Nursing Note for now, or dedicated Vitals table if exists)
    public function storeVitals(Request $request, $visitId)
    {
        $request->validate([
            'temperature' => 'required',
            'blood_pressure' => 'required',
            'pulse' => 'required',
        ]);

        $visit = Visit::findOrFail($visitId);

        // Append to nursing notes or create a new Vitals model if you have one
        // For this demo, we'll append to Nursing Notes as a "Vitals Check"
        
        $note = NursingNote::create([
            'visit_id' => $visit->id,
            'nurse_id' => Auth::id(),
            'note' => "Vitals Check:\nTemp: {$request->temperature}\nBP: {$request->blood_pressure}\nPulse: {$request->pulse}\n\n" . ($request->notes ?? ''),
            'type' => 'vitals', // Assuming you have a type column, or just generic note
        ]);

        // Update the current vitals on the Visit model for quick access
        $visit->update([
            'vitals' => [
                'temperature' => $request->temperature,
                'blood_pressure' => $request->blood_pressure,
                'pulse' => $request->pulse,
                'spo2' => $request->spo2 ?? null,
                'respiratory_rate' => $request->respiratory_rate ?? null,
                'recorded_at' => now()->toDateTimeString(),
            ]
        ]);

        return response()->json(['message' => 'Vitals recorded successfully', 'data' => $note]);
    }

    // Store Note
    public function storeNote(Request $request, $visitId)
    {
        $request->validate(['note' => 'required']);

        $note = NursingNote::create([
            'visit_id' => $visitId,
            'nurse_id' => Auth::id(),
            'note' => $request->note,
            'type' => 'general',
        ]);

        return response()->json(['message' => 'Note saved', 'data' => $note]);
    }
    // List Medications for Visit
    public function medications($visitId)
    {
        $visit = Visit::findOrFail($visitId);
        
        // Fetch prescriptions linked to this visit
        // Also fetch recent medication logs to determine "Last Given" time
        $prescriptions = \App\Models\Prescription::where('visit_id', $visitId)
            // ->with('drug') // Removed as relation does not exist
            ->get()
            ->map(function ($prescription) use ($visitId) {
                // Find last administration note for this specific drug
                $lastGiven = NursingNote::where('visit_id', $visitId)
                    ->where('type', 'medication')
                    ->where('note', 'LIKE', "%Administered: {$prescription->drug_name}%")
                    ->latest()
                    ->first();
                
                $prescription->last_given_at = $lastGiven ? $lastGiven->created_at : null;
                return $prescription;
            });

        return response()->json([
            'data' => $prescriptions,
            'visit' => $visit
        ]);
    }

    // Administer Medication
    public function administerMedication(Request $request, $visitId)
    {
        $request->validate([
            'drug_name' => 'required',
            'dosage' => 'required',
        ]);

        $note = NursingNote::create([
            'visit_id' => $visitId,
            'nurse_id' => Auth::id(),
            'note' => "Administered: {$request->drug_name} ({$request->dosage})\nRoute: " . ($request->route ?? 'Oral') . "\nNotes: " . ($request->notes ?? ''),
            'type' => 'medication',
        ]);

        return response()->json(['message' => 'Medication recorded', 'data' => $note]);
    }
}
