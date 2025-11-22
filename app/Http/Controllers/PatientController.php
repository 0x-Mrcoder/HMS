<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Wallet;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();

        $patients = Patient::with('wallet')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('hospital_id', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        $states = $this->states();
        return view('patients.create', compact('states'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'middle_name' => ['nullable', 'string', 'max:120'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['required', 'string', 'max:25', 'unique:patients,phone'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'lga' => ['nullable', 'string', 'max:120'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'genotype' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string'],
            'nhis_number' => ['nullable', 'string', 'max:60'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:25'],
            'wallet_minimum_balance' => ['nullable', 'numeric'],
        ]);

        $patient = Patient::create($data);

        $patient->wallet()->create([
            'balance' => 0,
            'low_balance_threshold' => $data['wallet_minimum_balance'] ?? 0,
            'virtual_account_number' => \App\Models\Wallet::generateVirtualAccountNumber(),
        ]);

        return redirect()->route('patients.show', $patient)->with('status', 'Patient profile created.');
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'wallet.transactions' => fn ($query) => $query->latest()->limit(10),
            'visits.department',
            'visits.service',
        ]);

        return view('patients.show', compact('patient'));
    }

    protected function states(): array
    {
        return [
            'Abia', 'Abuja (FCT)', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa', 'Kaduna',
            'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo',
            'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara',
        ];
    }
}
