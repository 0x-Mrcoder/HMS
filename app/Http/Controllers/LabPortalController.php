<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabPortalController extends Controller
{
    public function dashboard()
    {
        $metrics = [
            'pending_tests' => \App\Models\LabTest::where('status', 'pending')->count(),
            'completed_today' => \App\Models\LabTest::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count(),
            'revenue_today' => \App\Models\LabTest::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->sum('charge_amount'),
        ];

        $recentRequests = \App\Models\LabTest::with(['visit.patient'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('lab.dashboard', compact('metrics', 'recentRequests'));
    }

    public function index(Request $request)
    {
        $query = \App\Models\LabTest::with(['visit.patient', 'visit.doctor'])
            ->latest();

        // Status Filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Search Filter (Patient Name/ID or Test Name)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('test_name', 'like', "%{$search}%")
                  ->orWhereHas('visit.patient', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('hospital_id', 'like', "%{$search}%");
                  });
            });
        }

        // Specific Patient Filter (History)
        if ($request->has('patient_id')) {
            $query->whereHas('visit', function($q) use ($request) {
                $q->where('patient_id', $request->patient_id);
            });
        }

        $labTests = $query->paginate(15);
        return view('lab.requests.index', compact('labTests'));
    }

    public function show($id)
    {
        $labTest = \App\Models\LabTest::with(['visit.patient.wallet'])->findOrFail($id);
        
        // Try strict match first
        $template = \App\Models\LabTemplate::where('test_name', $labTest->test_name)->first();
        $initialTemplate = $template ? $template->fields : [];

        // Pass all templates for manual override
        $allTemplates = \App\Models\LabTemplate::all(['test_name', 'fields']);

        return view('lab.requests.show', compact('labTest', 'initialTemplate', 'allTemplates'));
    }

    public function process(Request $request, \App\Models\LabTest $labTest)
    {
        $request->validate(['amount' => 'required|numeric|min:0']);

        $amount = $request->amount;
        $wallet = $labTest->visit->patient->wallet;

        if ($wallet->balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        // Deduct Wallet
        $wallet->decrement('balance', $amount);

        // Update Test
        $labTest->update([
            'status' => 'in_progress',
            'charge_amount' => $amount,
            'charged_at' => now(),
            'technician_name' => Auth::user()->name,
        ]);

        return back()->with('status', 'Payment deducted. Test is now in progress.');
    }

    public function update(Request $request, \App\Models\LabTest $labTest)
    {
        $request->validate([
            'result_summary' => 'required|string',
            'results' => 'nullable|array',
            'results.*.parameter' => 'required_with:results|string',
            'results.*.value' => 'required_with:results|string',
        ]);

        $data = [
            'status' => 'completed',
            'result_summary' => $request->result_summary,
            'result_at' => now(),
            'result_data' => $request->results ?? [], // Save structured data
        ];

        $labTest->update($data);

        return redirect()->route('lab.portal.requests.show', $labTest->id)->with('status', 'Test updated and finalized.');
    }

    public function print($id)
    {
        $labTest = \App\Models\LabTest::with(['visit.patient', 'visit.doctor'])->findOrFail($id);
        
        if ($labTest->status !== 'completed') {
            abort(404, 'Result not ready');
        }

        return view('lab.requests.print', compact('labTest'));
    }

    public function reports()
    {
        // Metric: Tests Today
        $testsToday = \App\Models\LabTest::whereDate('created_at', now())->count();
        $revenueToday = \App\Models\LabTest::whereDate('created_at', now())->sum('charge_amount');

        // Metric: Monthly
        $testsMonth = \App\Models\LabTest::whereMonth('created_at', now()->month)->count();
        $revenueMonth = \App\Models\LabTest::whereMonth('created_at', now()->month)->sum('charge_amount');

        // Top 5 Tests
        $topTests = \App\Models\LabTest::select('test_name', \DB::raw('count(*) as total'))
            ->groupBy('test_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Recent Completed
        $recentCompleted = \App\Models\LabTest::where('status', 'completed')
            ->with(['visit.patient'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('lab.reports.index', compact('testsToday', 'revenueToday', 'testsMonth', 'revenueMonth', 'topTests', 'recentCompleted'));
    }
}
