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
        $zainpay = new \App\Classes\Zainpay();
        $zainboxCode = '31317_hLrKOyyV3ld7T4I8MSYw'; // CMS Sandbox

        $virtualAccountData = [
            'bankType' => 'fcmb', // Default bank type (working in sandbox)
            'firstName' => $validated['first_name'],
            'surname' => $validated['last_name'],
            'email' => $validated['email'] ?? strtolower($hospitalId) . '@hospital.com',
            'mobileNumber' => $validated['phone'] ?? '08000000000',
            'dob' => \Carbon\Carbon::parse($validated['date_of_birth'])->format('d-m-Y'),
            'gender' => $validated['gender'] == 'male' ? 'M' : 'F',
            'address' => $validated['address'] ?? 'Kano, Nigeria', // Fallback address required by API
            'title' => $validated['gender'] == 'male' ? 'Mr' : 'Mrs',
            'state' => 'Kano', // Default state
            'bvn' => '22222222222', // Test BVN for Sandbox
            'zainboxCode' => $zainboxCode
        ];

        $accountResponse = $zainpay->createVirtualAccount($virtualAccountData);
        
        $bankName = 'Wema Bank';
        $accountNumber = Wallet::generateVirtualAccountNumber(); // Fallback

        if (isset($accountResponse['code']) && $accountResponse['code'] == '00') {
            $bankName = $accountResponse['data']['bankName'];
            $accountNumber = $accountResponse['data']['accountNumber'];
        }

        $wallet = Wallet::create([
            'patient_id' => $patient->id, 
            'balance' => 0,
            'bank_name' => $bankName, 
            'virtual_account_number' => $accountNumber,
            'low_balance_threshold' => 1000,
        ]);

        // Generate Registration Invoice
        $registrationFee = 1000;
        $invoice = \App\Models\Invoice::create([
            'patient_id' => $patient->id,
            'invoice_number' => \App\Models\Invoice::generateNumber(),
            'status' => 'pending', // Unpaid
            'total_amount' => $registrationFee,
            'generated_by' => Auth::id(),
        ]);

        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Patient Registration Fee',
            'quantity' => 1,
            'unit_price' => $registrationFee,
            'total_price' => $registrationFee,
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
        // Fetch Doctors with their User and Department info
        $doctors = \App\Models\Doctor::with(['user', 'department'])
            ->where('is_available', true)
            ->get()
            ->sortBy('department.name');
            
        return view('staff.appointments.create', compact('patient', 'doctors'));
    }

    public function storeAppointment(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => 'required|string',
            'reason' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'vitals' => 'nullable|array',
            'vitals.temperature' => 'nullable|numeric',
            'vitals.blood_pressure' => 'nullable|string',
            'vitals.pulse_rate' => 'nullable|numeric',
            'vitals.weight' => 'nullable|numeric',
        ]);

        $doctor = User::findOrFail($validated['doctor_id']);

        // Calculate Fee (Free Consultation)
        $consultationFee = 0;
        
        // Create Invoice
        $invoice = \App\Models\Invoice::create([
            'patient_id' => $patient->id,
            'invoice_number' => \App\Models\Invoice::generateNumber(),
            'status' => 'paid', // Free service is always "paid"
            'total_amount' => $consultationFee,
            'generated_by' => Auth::id(),
        ]);

        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Consultation Fee - ' . ucwords(str_replace('_', ' ', $validated['visit_type'])),
            'quantity' => 1,
            'unit_price' => $consultationFee,
            'total_price' => $consultationFee,
        ]);

        // Attempt Payment logic (Only if fee > 0)
        $visitStatus = 'pending_doctor';
        $paymentStatus = 'Free';
        
        if ($consultationFee > 0) {
            $invoice->update(['status' => 'pending']); // Reset to pending to check balance
            
            if ($patient->wallet && $patient->wallet->balance >= $consultationFee) {
                // Debit Wallet
                $patient->wallet->decrement('balance', $consultationFee);
                
                // Log Transaction
                \App\Models\WalletTransaction::create([
                    'wallet_id' => $patient->wallet->id,
                    'transaction_type' => 'debit',
                    'amount' => $consultationFee,
                    'balance_after' => $patient->wallet->balance,
                    'description' => 'Payment for Invoice #' . $invoice->invoice_number,
                    'transacted_at' => now(),
                ]);

                // Update Invoice
                $invoice->update(['status' => 'paid']);
                $paymentStatus = 'Paid';
            } else {
                 $paymentStatus = 'Unpaid';
            }
        }

        // Create Pending Visit
        $patient->visits()->create([
            'doctor_id' => $doctor->id,
            'department_id' => $doctor->department_id, // Assign Doctor's Department
            'status' => $visitStatus, 
            'visit_type' => $validated['visit_type'],
            'reason' => $validated['reason'],
            'scheduled_at' => $validated['scheduled_at'] ?? now(),
            'vitals' => $validated['vitals'] ?? null,
            // Link invoice if we add invoice_id column to visits later
        ]);

        return redirect()->route('staff.portal.patients.show', $patient)
            ->with('success', 'Appointment booked. Invoice generated: ' . $invoice->invoice_number . ' (' . $paymentStatus . ')');
    }

    public function printCard(Patient $patient, Request $request)
    {
        // Only allow viewing password once (passed via query param from store/reset)
        $password = $request->query('raw_password');

        return view('staff.patients.card', compact('patient', 'password'));
    }

    public function wards()
    {
        $wards = \App\Models\Ward::with('beds')->get();
        
        $stats = [
            'total' => \App\Models\Bed::count(),
            'available' => \App\Models\Bed::where('status', 'available')->count(),
            'occupied' => \App\Models\Bed::where('status', 'occupied')->count(),
            'maintenance' => \App\Models\Bed::where('status', 'maintenance')->count(),
        ];

        return view('staff.wards.index', compact('wards', 'stats'));
    }
}
