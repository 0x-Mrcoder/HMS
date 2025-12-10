<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Visit;

class StaffPortalController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with(['user', 'wallet'])->latest();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('hospital_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(20);
        return view('staff.patients.index', compact('patients'));
    }

    public function appointments(Request $request)
    {
        $query = Visit::with(['patient', 'doctor'])
            ->whereNotNull('doctor_id')
            ->latest('scheduled_at');

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        } else {
            // Default to upcoming/today if no specific filter? 
            // Or just show all? Let's show all but ordered.
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20);
        $doctors = User::where('role', 'doctor')->get();

        return view('staff.appointments.index', compact('appointments', 'doctors'));
    }

    public function dashboard()
    {
        // Metric: Total Patients
        $totalPatients = Patient::count();

        // Metric: Patients Registered Today
        $registeredToday = Patient::whereDate('created_at', today())->count();
            
        // Metric: Doctors Count
        $doctorsCount = User::where('role', 'doctor')->count();

        // Metric: Pending Appointments
        $pendingAppointments = Visit::where('status', 'pending_doctor')->count();

        $recentPatients = User::where('role', 'patient')
            ->latest()
            ->take(5)
            ->get();
            
        // Today's Appointments
        $todayAppointments = Visit::with(['patient', 'doctor'])
            ->whereDate('scheduled_at', today())
            ->whereNotNull('doctor_id')
            ->orderBy('scheduled_at')
            ->get();

        return view('staff.dashboard', compact(
            'totalPatients', 
            'registeredToday', 
            'doctorsCount', 
            'pendingAppointments',
            'recentPatients', 
            'todayAppointments'
        ));
    }

    public function createPatient()
    {
        return view('staff.patients.create');
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email',
            'address' => 'nullable|string',
        ]);

        // Auto-generate credentials
        $hospitalId = 'P-' . date('Y') . '-' . strtoupper(Str::random(6));
        $password = Str::random(8); // Random 8-char password
        
        // Create User
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'] ?? strtolower($hospitalId) . '@hospital.com',
            'password' => Hash::make($password),
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);

        // Create Patient Record
        $patient = Patient::create([
            'user_id' => $user->id, // Link to User if column exists (migration implies it does or vice versa)
            'hospital_id' => $hospitalId,
            'card_number' => 'C-' . strtoupper(Str::random(8)),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Create Wallet
        $wallet = Wallet::create([
            'patient_id' => $patient->id, // Correctly link to Patient
            'balance' => 0,
            'virtual_account_number' => Wallet::generateVirtualAccountNumber(), // Use the model helper
            'low_balance_threshold' => 1000,
        ]);

        // Redirect to Print Card view with credentials
        return redirect()->route('staff.portal.patients.card', ['patient' => $patient->id, 'raw_password' => $password]);
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'wallet', 'visits.doctor', 'visits.prescriptions']);
        return view('staff.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('staff.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
        ]);

        $patient->update($validated);
        
        // Also update User name
        if ($patient->user) {
            $patient->user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ]);
        }

        return redirect()->route('staff.portal.patients.show', $patient)->with('success', 'Patient profile updated.');
    }

    public function resetPassword(Patient $patient)
    {
        $newPassword = Str::random(8); // Generate new 8-char password
        
        if ($patient->user) {
            $patient->user->update([
                'password' => Hash::make($newPassword),
            ]);
        }

        // Redirect to Card Print with new password
        return redirect()->route('staff.portal.patients.card', [
            'patient' => $patient, 
            'raw_password' => $newPassword
        ]);
    }

    public function findPatientForBooking(Request $request)
    {
        $patients = collect();
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $patients = Patient::with('user')
                ->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('hospital_id', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->get();
        }

        return view('staff.appointments.find', compact('patients'));
    }

    public function createAppointment(Patient $patient)
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('staff.appointments.create', compact('patient', 'doctors'));
    }

    public function storeAppointment(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => 'required|string',
            'reason' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $doctor = User::findOrFail($validated['doctor_id']);

        // Create Pending Visit
        $patient->visits()->create([
            'doctor_id' => $doctor->id,
            'department_id' => $doctor->department_id, // Assign Doctor's Department
            'status' => 'pending_doctor', 
            'visit_type' => $validated['visit_type'],
            'reason' => $validated['reason'],
            'scheduled_at' => $validated['scheduled_at'] ?? now(),
        ]);

        return redirect()->route('staff.portal.patients.show', $patient)
            ->with('success', 'Appointment booked successfully.');
    }

    public function printCard(Patient $patient, Request $request)
    {
        // Only allow viewing password once (passed via query param from store/reset)
        $password = $request->query('raw_password');

        return view('staff.patients.card', compact('patient', 'password'));
    }
}
