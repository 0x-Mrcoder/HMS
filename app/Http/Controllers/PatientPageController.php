<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\NursingNote;
use App\Models\Prescription;
use App\Models\Visit;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientPageController extends Controller
{
    protected function patientOrFail()
    {
        $patient = Auth::user()?->patient;
        abort_unless($patient, 403);
        return $patient;
    }

    public function wallet()
    {
        $patient = $this->patientOrFail();
        $patient->load('wallet');
        $transactions = WalletTransaction::where('wallet_id', optional($patient->wallet)->id)
            ->latest('transacted_at')
            ->paginate(12);
        $walletAlert = $patient->wallet
            ? max(0, ($patient->wallet_minimum_balance ?? 0) - (float) $patient->wallet->balance)
            : null;

        return view('patient.pages.wallet', compact('patient', 'transactions', 'walletAlert'));
    }

    public function visits()
    {
        $patient = $this->patientOrFail();
        $visits = Visit::with(['department', 'service'])
            ->where('patient_id', $patient->id)
            ->latest('scheduled_at')
            ->paginate(10);

        return view('patient.pages.visits', compact('patient', 'visits'));
    }

    public function prescriptions()
    {
        $patient = $this->patientOrFail();
        $prescriptions = Prescription::with(['visit.department'])
            ->whereHas('visit', fn ($query) => $query->where('patient_id', $patient->id))
            ->latest()
            ->paginate(10);

        return view('patient.pages.prescriptions', compact('patient', 'prescriptions'));
    }

    public function labs()
    {
        $patient = $this->patientOrFail();
        $labTests = LabTest::with(['visit.department'])
            ->whereHas('visit', fn ($query) => $query->where('patient_id', $patient->id))
            ->latest()
            ->paginate(10);

        return view('patient.pages.labs', compact('patient', 'labTests'));
    }

    public function careNotes()
    {
        $patient = $this->patientOrFail();
        $nursingNotes = NursingNote::with(['visit.department'])
            ->whereHas('visit', fn ($query) => $query->where('patient_id', $patient->id))
            ->latest('recorded_at')
            ->paginate(10);

        return view('patient.pages.care-notes', compact('patient', 'nursingNotes'));
    }

    public function profile()
    {
        $patient = $this->patientOrFail();
        $patient->load('wallet');

        return view('patient.pages.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = $this->patientOrFail();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'middle_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:25'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:25'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/avatars');
            $data['photo_url'] = Storage::url($path);
        }

        $patient->update($data);

        return back()->with('status', 'Profile updated successfully.');
    }

    public function requestVisit()
    {
        $patient = $this->patientOrFail();
        $departments = \App\Models\Department::orderBy('name')->get();
        $services = \App\Models\Service::orderBy('name')->get();

        return view('patient.pages.request-visit', compact('patient', 'departments', 'services'));
    }

    public function storeVisit(Request $request)
    {
        $patient = $this->patientOrFail();

        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'visit_type' => ['required', 'in:opd,ipd'],
            'reason' => ['required', 'string', 'max:500'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        Visit::create([
            ...$data,
            'patient_id' => $patient->id,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.portal.visits')->with('status', 'Visit requested successfully. We will confirm the schedule soon.');
    }
}
