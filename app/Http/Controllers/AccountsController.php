<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FinancialRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        $channel = $request->string('channel')->toString();
        $type = $request->string('type')->toString();
        $from = $request->date('from');
        $to = $request->date('to');

        $recordsQuery = FinancialRecord::with(['patient', 'department'])
            ->when($channel, fn ($query) => $query->where('payment_channel', $channel))
            ->when($type, fn ($query) => $query->where('record_type', $type))
            ->when($from, fn ($query) => $query->whereDate('recorded_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('recorded_at', '<=', $to))
            ->latest('recorded_at');

        $records = $recordsQuery->paginate(15)->withQueryString();

        $summary = FinancialRecord::select('record_type', DB::raw('SUM(amount) as total'))
            ->groupBy('record_type')
            ->pluck('total', 'record_type');

        $channelSplit = FinancialRecord::select('payment_channel', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_channel')
            ->pluck('total', 'payment_channel');

        $patients = Patient::select('id', 'first_name', 'last_name', 'hospital_id')->orderBy('first_name')->limit(100)->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();

        return view('accounts.index', compact('records', 'summary', 'channelSplit', 'patients', 'departments', 'channel', 'type', 'from', 'to'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['nullable', 'exists:patients,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'payment_channel' => ['required', 'in:wallet,cash,pos,transfer,online'],
            'record_type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reference' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'recorded_by' => ['nullable', 'string', 'max:120'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        FinancialRecord::create([
            ...$data,
            'recorded_at' => $data['recorded_at'] ?? now(),
        ]);

        return redirect()->route('accounts.index')->with('status', 'Financial record captured successfully.');
    }
}
