<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\LabTest;
use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorPortalController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->role === 'doctor', 403);

        $today = now()->startOfDay();

        $visits = Visit::with(['patient', 'department', 'service'])
            ->where('doctor_id', Auth::id())
            ->orderByDesc('scheduled_at')
            ->limit(8)
            ->get();

        $activeQueue = Visit::with(['patient', 'department'])
            ->where('doctor_id', Auth::id())
            ->whereIn('status', ['pending_doctor', 'in_progress'])
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $pendingPrescriptions = Prescription::whereHas('visit', fn($q) => $q->where('doctor_id', Auth::id()))
            ->where('status', 'pending')
            ->latest()
            ->limit(6)
            ->get();

        $recentLabTests = LabTest::with(['visit.patient'])
            ->whereHas('visit', fn($q) => $q->where('doctor_id', Auth::id()))
            ->latest()
            ->limit(5)
            ->get();

        $metrics = [
            'today_appointments' => Visit::where('doctor_id', Auth::id())->whereDate('scheduled_at', $today)->count(),
            'active_patients' => Visit::where('doctor_id', Auth::id())->whereIn('status', ['pending_doctor', 'in_progress'])->count(),
            'pending_prescriptions' => $pendingPrescriptions->count(), // Already filtered above? No, let's filter it properly
            'pending_labs' => LabTest::whereHas('visit', fn($q) => $q->where('doctor_id', Auth::id()))->where('status', 'pending')->count(),
        ];

        $doctor = \App\Models\Doctor::with('department')->where('user_id', Auth::id())->first();
        $deptName = $doctor?->department?->name;

        $isSurgeon = $deptName === 'Surgery';
        // Specialists who do Ward Rounds
        $isWardDoctor = in_array($deptName, ['Cardiology', 'Neurology', 'Internal Medicine', 'Surgery']);

        $upcomingSurgeries = [];
        $surgeriesToday = [];
        $preOpQueue = [];
        $postOpPatients = [];

        if ($isSurgeon) {
            $surgeriesToday = \App\Models\Surgery::with(['patient'])
                ->whereDate('scheduled_at', $today)
                ->where('status', 'scheduled')
                ->get();
                
            $preOpQueue = \App\Models\Surgery::with(['patient'])
                ->whereIn('status', ['scheduled', 'pending']) 
                ->orderBy('scheduled_at')
                ->limit(10)
                ->get();

             $postOpPatients = \App\Models\Surgery::with(['patient'])
                ->where('status', 'completed')
                ->latest('scheduled_at')
                ->limit(5)
                ->get();
            
            // Re-purpose upcomingSurgeries for the view linkage
            $upcomingSurgeries = $preOpQueue;
            
            $metrics['surgeries_today'] = $surgeriesToday->count();
            $metrics['pending_requests'] = $preOpQueue->count();
            $metrics['post_op_active'] = $postOpPatients->count();
        }

        $myAdmissions = [];
        if ($isWardDoctor) {
            $myAdmissions = \App\Models\Admission::with(['patient', 'ward', 'bed'])
                ->whereHas('visit', fn($q) => $q->where('doctor_id', Auth::id()))
                ->where('status', 'admitted')
                ->latest('admitted_at')
                ->limit(5)
                ->get();
        }

        return view('doctor.dashboard', compact(
            'metrics',
            'visits',
            'activeQueue',
            'pendingPrescriptions',
            'recentLabTests',
            'isSurgeon',
            'upcomingSurgeries',
            'surgeriesToday',
            'postOpPatients',
            'isWardDoctor',
            'myAdmissions'
        ));
    }

    public function patients(Request $request)
    {
        $search = $request->string('q')->toString();

        $patients = Patient::query()
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('hospital_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('doctor.patients.index', compact('patients', 'search'));
    }

    public function showPatient(Patient $patient)
    {
        $patient->load([
            'wallet', 
            'visits' => fn($q) => $q->latest()->limit(50),
            'visits.department', // Eager load department for display
            'admissions.ward',
            'admissions.bed',
            'surgeries',
            'referrals'
        ]);
        
        return view('doctor.patients.show', compact('patient'));
    }

    public function showVisit(Visit $visit)
    {
        $visit->load(['patient', 'prescriptions', 'labTests', 'nursingNotes']);
        
        $wards = \App\Models\Ward::with(['beds' => function($q) {
            $q->where('status', 'available');
        }])->get();

        return view('doctor.visits.show', compact('visit', 'wards'));
    }

    public function storeAdmission(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'ward_id' => ['required', 'exists:wards,id'],
            'bed_id' => ['required', 'exists:beds,id'],
            'notes' => ['nullable', 'string'],
        ]);

        // Check if bed is still available
        $bed = \App\Models\Bed::find($data['bed_id']);
        if ($bed->status !== 'available') {
            return back()->withErrors(['bed_id' => 'Selected bed is no longer available.']);
        }

        // Create Admission
        \App\Models\Admission::create([
            'patient_id' => $visit->patient_id,
            'visit_id' => $visit->id,
            'ward_id' => $data['ward_id'],
            'bed_id' => $data['bed_id'],
            'status' => 'admitted',
            'admitted_by' => Auth::user()->name,
            'admitted_at' => now(),
        ]);

        // Update Bed Status
        $bed->update(['status' => 'occupied']);

        // Update Visit Status
        $visit->update(['status' => 'in_progress']); // Or 'admitted' if enum allows

        return back()->with('status', 'Patient admitted successfully.');
    }

    public function storeReferral(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'type' => ['required', 'in:internal,external'],
            'destination' => ['required', 'string'],
            'doctor_name' => ['nullable', 'string'],
            'reason' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        \App\Models\Referral::create([
            'patient_id' => $visit->patient_id,
            'visit_id' => $visit->id,
            'type' => $data['type'],
            'destination' => $data['destination'],
            'doctor_name' => $data['doctor_name'],
            'reason' => $data['reason'],
            'notes' => $data['notes'],
            'status' => 'pending',
            'referred_by' => Auth::user()->name,
            'referred_at' => now(),
        ]);

        return back()->with('status', 'Referral created successfully.');
    }

    public function storeSurgery(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'procedure_name' => ['required', 'string'],
            'scheduled_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        \App\Models\Surgery::create([
            'visit_id' => $visit->id,
            'patient_id' => $visit->patient_id,
            'procedure_name' => $data['procedure_name'],
            'status' => 'scheduled',
            'scheduled_at' => $data['scheduled_at'],
            'notes' => $data['notes'],
        ]);

        return back()->with('status', 'Surgery request submitted.');
    }


    public function storeDiagnosis(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'diagnosis' => ['required', 'string'],
            'clinical_notes' => ['nullable', 'string'],
        ]);

        $visit->update([
            'diagnosis' => $data['diagnosis'],
            'clinical_notes' => $data['clinical_notes'],
        ]);

        return back()->with('status', 'Diagnosis and notes saved successfully.');
    }

    public function searchDrugs(Request $request)
    {
        $search = $request->string('q')->toString();

        $drugs = \App\Models\Drug::query()
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'price', 'stock']);

        return response()->json($drugs);
    }

    public function storePrescription(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.drug_name' => ['required', 'string'],
            'items.*.dosage' => ['required', 'string'],
            'items.*.frequency' => ['required', 'string'],
            'items.*.duration' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.notes' => ['nullable', 'string'],
            'buy_from_hospital' => ['nullable', 'boolean'],
        ]);

        $buyFromHospital = $request->boolean('buy_from_hospital');
        $totalCost = 0;

        // Calculate total cost if buying from hospital
        if ($buyFromHospital) {
            foreach ($data['items'] as $item) {
                // Try to find drug in inventory to get price
                $drug = \App\Models\Drug::where('name', $item['drug_name'])->first();
                if ($drug) {
                    $totalCost += $drug->price * $item['quantity'];
                }
            }

            // Check wallet balance
            if ($visit->patient->wallet && $visit->patient->wallet->balance < $totalCost) {
                return back()->withErrors(['wallet' => 'Insufficient wallet balance for hospital pharmacy purchase. Required: ₦' . number_format($totalCost, 2)]);
            }
        }

        foreach ($data['items'] as $item) {
            $drug = \App\Models\Drug::where('name', $item['drug_name'])->first();
            
            $visit->prescriptions()->create([
                'drug_name' => $item['drug_name'],
                'dosage' => $item['dosage'],
                'frequency' => $item['frequency'],
                'duration' => $item['duration'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? null,
                'status' => 'pending',
                'prescribed_at' => now(),
                'prescribed_by' => Auth::user()->name,
                'unit_price' => $drug ? $drug->price : 0,
                'total_cost' => $drug ? ($drug->price * $item['quantity']) : 0,
            ]);
        }

        // Note: Actual wallet deduction happens at Pharmacy dispensing stage, 
        // but we've verified they have funds if they chose to buy here.

        return back()->with('status', 'Prescription sent to pharmacy.');
    }

    public function storeLabTest(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'test_name' => ['required', 'string', 'max:255'],
            'clinical_notes' => ['nullable', 'string'],
            'priority' => ['required', 'in:routine,urgent,emergency'],
        ]);

        $visit->labTests()->create([
            'test_name' => $data['test_name'],
            'clinical_notes' => $data['clinical_notes'],
            'priority' => $data['priority'],
            'status' => 'pending',
            'ordered_at' => now(),
            'ordered_by' => Auth::user()->name,
        ]);

        return back()->with('status', 'Lab test ordered successfully.');
    }

    public function queue()
    {
        // "Clinic Queue" - Active visits (pending/in_progress)
        $queue = Visit::with(['patient', 'department'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('scheduled_at')
            ->paginate(20);

        return view('doctor.queue', compact('queue'));
    }

    public function appointments()
    {
        // "Appointments" - All scheduled visits
        $appointments = Visit::with(['patient', 'department'])
            ->orderByDesc('scheduled_at')
            ->paginate(20);

        return view('doctor.appointments', compact('appointments'));
    }

    public function prescriptions()
    {
        // "Prescriptions" - History of prescriptions by this doctor (or all)
        $prescriptions = Prescription::with(['visit.patient'])
            ->latest()
            ->paginate(20);

        return view('doctor.prescriptions', compact('prescriptions'));
    }

    public function labs()
    {
        // "Lab Results" - History of lab tests
        $labs = LabTest::with(['visit.patient'])
            ->latest()
            ->paginate(20);

        return view('doctor.labs', compact('labs'));
    }

    public function nursingNotes()
    {
        // "Nursing Notes" - Recent notes
        // Assuming NursingNote model exists and is linked to visits
        $notes = \App\Models\NursingNote::with(['visit.patient'])
            ->latest('recorded_at')
            ->paginate(20);

        return view('doctor.nursing-notes', compact('notes'));
    }

    public function theatreRequests(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Surgery::with(['patient']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('hospital_id', 'like', "%{$search}%");
            });
        }

        $surgeries = $query->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('doctor.theatre-requests', compact('surgeries'));
    }

    public function manageSurgery(\App\Models\Surgery $surgery)
    {
        // Ensure only surgeons or relevant doctors can access
        // Ideally check department, but for now simple View return
        $surgery->load(['patient', 'visit.labTests', 'visit.prescriptions']);
        return view('doctor.surgeries.manage', compact('surgery'));
    }

    public function updateSurgeryNotes(\Illuminate\Http\Request $request, \App\Models\Surgery $surgery)
    {
        $surgery->update([
            'notes' => $request->notes,
            // 'anesthesia_notes' => $request->anesthesia_notes 
        ]);
        return back()->with('success', 'Notes updated.');
    }

    public function completeSurgery(\App\Models\Surgery $surgery)
    {
        $surgery->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);
        return redirect()->route('doctor.portal.dashboard')->with('success', 'Surgery completed successfully.');
    }

    public function printSurgeryReport(\App\Models\Surgery $surgery)
    {
        $surgery->load(['patient', 'visit']);
        return view('doctor.surgeries.print', compact('surgery'));
    }
}
