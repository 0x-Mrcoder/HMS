<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\FinancialRecord;
use App\Models\InsuranceClaim;
use App\Models\LabTest;
use App\Models\NursingNote;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Service;
use App\Models\Surgery;
use App\Models\Visit;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class HmsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::with('wallet')->get();
        $departments = Department::with('services')->get()->filter(fn ($department) => $department->services->isNotEmpty());

        if ($patients->isEmpty() || $departments->isEmpty()) {
            return;
        }

        foreach ($patients as $patient) {
            $wallet = $patient->wallet;

            if ($wallet) {
                $this->creditWallet($wallet, 40000, 'Initial funding', Arr::random(['cash', 'transfer', 'pos']));
            }

            $visitCount = rand(1, 2);

            for ($i = 0; $i < $visitCount; $i++) {
                $department = $departments->random();
                /** @var Service|null $service */
                $service = $department->services->random();

                $scheduledAt = Carbon::now()->subDays(rand(0, 5))->addHours(rand(0, 23));
                $status = Arr::random(['pending', 'in_progress', 'completed']);
                $estimated = Arr::random([15000, 20000, 25000, 30000]);
                $charged = $estimated - rand(0, 5000);

                $visit = Visit::create([
                    'patient_id' => $patient->id,
                    'department_id' => $department->id,
                    'service_id' => $service?->id,
                    'visit_type' => Arr::random(['opd', 'ipd']),
                    'status' => $status,
                    'doctor_name' => Arr::random(['Dr. Bello', 'Dr. Mensah', 'Dr. Lawson']),
                    'reason' => Arr::random(['Routine check', 'Follow-up visit', 'Complaints of headache', 'Chest pain evaluation']),
                    'vitals' => [
                        'bp' => Arr::random(['120/80', '130/85', '110/70']),
                        'temp' => Arr::random(['36.5°C', '37.4°C']),
                        'spo2' => Arr::random(['97%', '98%']),
                    ],
                    'estimated_cost' => $estimated,
                    'amount_charged' => $charged,
                    'scheduled_at' => $scheduledAt,
                    'completed_at' => $status === 'completed' ? $scheduledAt->addHours(rand(1, 4)) : null,
                ]);

                $this->seedPrescriptions($visit, $wallet);
                $this->seedLabTests($visit, $wallet);
                $this->seedNursingNotes($visit);

                if ($wallet && $charged > 0) {
                    $this->debitWallet($wallet, min($wallet->balance, $charged), 'Consultation/Lab charges');
                }
            }

            if (rand(0, 1) === 1) {
                $this->seedSurgery($patient);
            }

            if (rand(0, 1) === 1) {
                $this->seedInsuranceClaim($patient);
            }
        }

        $this->seedFinancialRecords();
    }

    protected function seedPrescriptions(Visit $visit, ?\App\Models\Wallet $wallet): void
    {
        $prescriptions = [
            ['drug' => 'Amoxicillin 500mg', 'dosage' => '500mg', 'freq' => 'TDS', 'duration' => '5 days', 'unit' => 1500, 'qty' => 10],
            ['drug' => 'Paracetamol', 'dosage' => '1g', 'freq' => 'BD', 'duration' => '3 days', 'unit' => 500, 'qty' => 6],
            ['drug' => 'Vitamin C', 'dosage' => '500mg', 'freq' => 'OD', 'duration' => '7 days', 'unit' => 300, 'qty' => 7],
        ];

        $set = Arr::random($prescriptions);
        $total = $set['unit'] * $set['qty'];

        $status = Arr::random(['pending', 'dispensed']);

        $prescription = Prescription::create([
            'visit_id' => $visit->id,
            'drug_name' => $set['drug'],
            'dosage' => $set['dosage'],
            'frequency' => $set['freq'],
            'duration' => $set['duration'],
            'status' => $status,
            'unit_price' => $set['unit'],
            'quantity' => $set['qty'],
            'total_cost' => $total,
            'dispensed_at' => $status === 'dispensed' ? now()->subHours(rand(1, 24)) : null,
            'dispensed_by' => $status === 'dispensed' ? Arr::random(['Pharm. Dolapo', 'Pharm. Mike']) : null,
            'notes' => $status === 'dispensed' ? 'Dispensed via demo seeder' : null,
        ]);

        if ($status === 'dispensed' && $wallet && $wallet->balance > $total) {
            $this->debitWallet($wallet, $total, 'Pharmacy - ' . $prescription->drug_name);
        }
    }

    protected function seedLabTests(Visit $visit, ?\App\Models\Wallet $wallet): void
    {
        if (rand(0, 1) === 0) {
            return;
        }

        $tests = [
            ['name' => 'Full Blood Count', 'charge' => 4500],
            ['name' => 'Malaria Parasite Test', 'charge' => 3500],
            ['name' => 'Random Blood Sugar', 'charge' => 4000],
        ];

        $test = Arr::random($tests);
        $status = Arr::random(['pending', 'in_progress', 'completed']);

        $labTest = LabTest::create([
            'visit_id' => $visit->id,
            'test_name' => $test['name'],
            'technician_name' => $status === 'completed' ? Arr::random(['MLS Tonia', 'MLS Imran']) : null,
            'status' => $status,
            'charge_amount' => $test['charge'],
            'charged_at' => $status === 'completed' ? now()->subHours(rand(1, 24)) : null,
            'result_summary' => $status === 'completed' ? Arr::random(['All values within range', 'Slight elevation noted']) : null,
            'result_data' => $status === 'completed' ? ['hb' => rand(11, 13) . ' g/dl'] : null,
            'result_at' => $status === 'completed' ? now()->subHours(rand(1, 24)) : null,
        ]);

        if ($status === 'completed' && $wallet && $wallet->balance > $labTest->charge_amount) {
            $this->debitWallet($wallet, $labTest->charge_amount, 'Laboratory - ' . $labTest->test_name);
        }
    }

    protected function seedNursingNotes(Visit $visit): void
    {
        if (rand(0, 1) === 0) {
            return;
        }

        NursingNote::create([
            'visit_id' => $visit->id,
            'nurse_name' => Arr::random(['RN Mary', 'RN Tega', 'RN Okoye']),
            'note_type' => Arr::random(['Vitals', 'Medication', 'Observation']),
            'note' => Arr::random(['Vitals checked and stable', 'Medication administered', 'Patient resting comfortably']),
            'recorded_at' => now()->subHours(rand(1, 12)),
        ]);
    }

    protected function seedSurgery(Patient $patient): void
    {
        $wallet = $patient->wallet;
        $procedures = [
            ['name' => 'Appendectomy', 'cost' => 150000],
            ['name' => 'Hernia Repair', 'cost' => 120000],
            ['name' => 'Caesarean Section', 'cost' => 180000],
        ];

        $data = Arr::random($procedures);
        $status = Arr::random(['scheduled', 'in_progress', 'completed']);

        $surgery = Surgery::create([
            'patient_id' => $patient->id,
            'visit_id' => $patient->visits()->inRandomOrder()->value('id'),
            'procedure_name' => $data['name'],
            'surgeon_name' => Arr::random(['Dr. Obinna', 'Dr. Tulu', 'Dr. Adamu']),
            'status' => $status,
            'materials_used' => ['Sutures', 'Drapes', 'Gauze'],
            'estimated_cost' => $data['cost'],
            'actual_cost' => $status === 'completed' ? $data['cost'] : 0,
            'scheduled_at' => now()->addDays(rand(-2, 3)),
            'started_at' => $status !== 'scheduled' ? now()->subHours(rand(2, 5)) : null,
            'completed_at' => $status === 'completed' ? now()->subHours(rand(1, 3)) : null,
            'notes' => 'Demo surgery log.',
        ]);

        if ($status === 'completed' && $wallet && $wallet->balance > $surgery->actual_cost) {
            $this->debitWallet($wallet, $surgery->actual_cost, 'Theatre - ' . $surgery->procedure_name);
        }
    }

    protected function seedInsuranceClaim(Patient $patient): void
    {
        $providers = ['NHIS', 'Hygeia HMO', 'Reliance HMO', 'AXA'];
        $total = Arr::random([25000, 35000, 50000, 60000]);
        $coPay = Arr::random([0, 2000, 3500]);
        $status = Arr::random(['submitted', 'approved', 'paid']);

        $claim = InsuranceClaim::create([
            'patient_id' => $patient->id,
            'visit_id' => $patient->visits()->inRandomOrder()->value('id'),
            'policy_number' => 'HMS-' . rand(10000, 99999),
            'provider' => Arr::random($providers),
            'claim_status' => $status,
            'total_amount' => $total,
            'approved_amount' => $status === 'approved' || $status === 'paid' ? $total - $coPay : 0,
            'co_pay_amount' => $coPay,
            'submitted_at' => now()->subDays(rand(1, 7)),
            'responded_at' => $status !== 'submitted' ? now()->subDays(rand(0, 2)) : null,
            'documents' => ['invoice' => 'INV-' . rand(1000, 9999)],
            'remarks' => $status === 'rejected' ? 'Additional documents required' : 'Demo claim seeded.',
        ]);

        $wallet = $patient->wallet;
        if ($coPay > 0 && $wallet && $wallet->balance > $coPay && $status !== 'submitted') {
            $this->debitWallet($wallet, $coPay, 'NHIS Co-Pay');
            $claim->update(['co_pay_deducted_at' => now()]);
        }
    }

    protected function seedFinancialRecords(): void
    {
        $channels = ['wallet', 'cash', 'pos', 'transfer', 'online'];
        $types = ['income', 'expense'];

        for ($i = 0; $i < 15; $i++) {
            $channel = Arr::random($channels);
            $type = Arr::random($types);
            $amount = $type === 'income' ? rand(20000, 80000) : rand(5000, 20000);

            FinancialRecord::create([
                'patient_id' => Patient::inRandomOrder()->value('id'),
                'department_id' => Department::inRandomOrder()->value('id'),
                'payment_channel' => $channel,
                'record_type' => $type,
                'amount' => $amount,
                'reference' => 'FIN-' . rand(10000, 99999),
                'description' => $type === 'income' ? 'Service payment' : 'Department expense',
                'recorded_by' => Arr::random(['Accounts Bot', 'Cash Desk']),
                'recorded_at' => now()->subDays(rand(0, 6))->addHours(rand(0, 23)),
            ]);
        }
    }

    protected function creditWallet(\App\Models\Wallet $wallet, float $amount, string $description, string $method = 'cash'): void
    {
        $wallet->increment('balance', $amount);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'transaction_type' => 'deposit',
            'payment_method' => $method,
            'amount' => $amount,
            'balance_after' => $wallet->balance,
            'performed_by' => 'Seeder',
            'service' => 'Wallet Funding',
            'description' => $description,
            'transacted_at' => now(),
        ]);
    }

    protected function debitWallet(\App\Models\Wallet $wallet, float $amount, string $service): void
    {
        if ($amount <= 0) {
            return;
        }

        $wallet->decrement('balance', $amount);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'transaction_type' => 'deduction',
            'payment_method' => Arr::random(['cash', 'pos', 'transfer', 'online']),
            'amount' => $amount,
            'balance_after' => $wallet->balance,
            'performed_by' => 'Seeder',
            'service' => $service,
            'description' => 'Auto generated by demo seeder',
            'transacted_at' => now(),
        ]);
    }
}
