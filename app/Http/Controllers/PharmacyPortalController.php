<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyPortalController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->role === 'pharmacy', 403);

        $pendingQueue = Prescription::with(['visit.patient'])
            ->whereIn('status', ['pending'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentDispensed = Prescription::with(['visit.patient'])
            ->where('status', 'dispensed')
            ->latest('dispensed_at')
            ->limit(5)
            ->get();

        $visitsAwaitingPharmacy = Visit::with(['patient', 'department'])
            ->whereHas('prescriptions', fn ($query) => $query->where('status', 'pending'))
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $metrics = [
            'pending_orders' => $pendingQueue->count(),
            'dispensed_today' => Prescription::where('status', 'dispensed')
                ->whereDate('dispensed_at', today())
                ->count(),
            'wallet_deductions_today' => Prescription::where('status', 'dispensed')
                ->whereDate('dispensed_at', today())
                ->sum('total_cost'),
        ];

        return view('pharmacy.dashboard', compact(
            'metrics',
            'pendingQueue',
            'recentDispensed',
            'visitsAwaitingPharmacy'
        ));
    }
    public function index()
    {
        $prescriptions = Prescription::with(['visit.patient'])
            ->latest()
            ->paginate(20);

        return view('pharmacy.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['visit.patient.wallet']);
        
        // Check current stock
        $drug = \App\Models\Drug::where('name', $prescription->drug_name)->first();
        $stock = $drug ? $drug->stock : 0;
        $price = $drug ? $drug->price : 0;

        return view('pharmacy.prescriptions.show', compact('prescription', 'stock', 'price'));
    }

    public function dispense(Request $request, Prescription $prescription)
    {
        if ($prescription->status === 'dispensed') {
            return back()->with('error', 'Prescription already dispensed.');
        }

        $drug = \App\Models\Drug::where('name', $prescription->drug_name)->first();
        
        if (!$drug) {
            return back()->with('error', 'Drug not found in inventory.');
        }

        if ($drug->stock < $prescription->quantity) {
            return back()->with('error', 'Insufficient stock.');
        }

        $totalCost = $drug->price * $prescription->quantity;
        $patientWallet = $prescription->visit->patient->wallet;

        if (!$patientWallet || $patientWallet->balance < $totalCost) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        // Deduct Stock
        $drug->decrement('stock', $prescription->quantity);

        // Log Stock Change
        \DB::table('drug_stock_logs')->insert([
            'drug_id' => $drug->id,
            'user_id' => Auth::id(),
            'quantity_change' => -$prescription->quantity,
            'type' => 'dispensed',
            'notes' => 'Dispensed for Prescription #' . $prescription->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Deduct Wallet
        $patientWallet->decrement('balance', $totalCost);

        // Update Prescription
        $prescription->update([
            'status' => 'dispensed',
            'dispensed_at' => now(),
            'dispensed_by' => Auth::user()->name,
            'unit_price' => $drug->price,
            'total_cost' => $totalCost,
        ]);

        return redirect()->route('pharmacy.portal.dashboard')->with('status', 'Prescription dispensed successfully.');
    }

    public function inventory()
    {
        $drugs = \App\Models\Drug::orderBy('name')->paginate(20);
        return view('pharmacy.inventory.index', compact('drugs'));
    }

    public function store(Request $request)
    {
        $data = $rerquest->validate([
            'name' => ['required', 'string', 'max:255', 'unique:drugs,name'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $drug = \App\Models\Drug::create($data);

        // Log Stock Change
        \DB::table('drug_stock_logs')->insert([
            'drug_id' => $drug->id,
            'user_id' => Auth::id(),
            'quantity_change' => $data['stock'],
            'type' => 'in',
            'notes' => 'Initial Stock',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Drug added successfully.');
    }

    public function update(Request $request, \App\Models\Drug $drug)
    {
        $data = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
        ]);

        $oldStock = $drug->stock;
        $drug->update($data);

        $stockDiff = $data['stock'] - $oldStock;

        if ($stockDiff != 0) {
            \DB::table('drug_stock_logs')->insert([
                'drug_id' => $drug->id,
                'user_id' => Auth::id(),
                'quantity_change' => $stockDiff,
                'type' => $stockDiff > 0 ? 'in' : 'adjustment',
                'notes' => 'Stock Update',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('status', 'Drug updated successfully.');
    }

    public function reject(Request $request, Prescription $prescription)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $prescription->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'dispensed_by' => Auth::user()->name, // Track who rejected it
        ]);

        return redirect()->route('pharmacy.portal.dashboard')->with('status', 'Prescription rejected.');
    }

    public function stockLogs(\App\Models\Drug $drug)
    {
        $logs = \DB::table('drug_stock_logs')
            ->join('users', 'drug_stock_logs.user_id', '=', 'users.id')
            ->where('drug_id', $drug->id)
            ->select('drug_stock_logs.*', 'users.name as user_name')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('pharmacy.inventory.logs', compact('drug', 'logs'));
    }

    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $sales = Prescription::where('status', 'dispensed')
            ->whereBetween('dispensed_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with(['visit.patient'])
            ->orderByDesc('dispensed_at')
            ->get();

        $totalRevenue = $sales->sum('total_cost');
        $totalItems = $sales->sum('quantity');

        // Group by Drug
        $salesByDrug = $sales->groupBy('drug_name')->map(function ($group) {
            return [
                'quantity' => $group->sum('quantity'),
                'revenue' => $group->sum('total_cost'),
            ];
        })->sortByDesc('revenue');

        return view('pharmacy.reports.index', compact('sales', 'totalRevenue', 'totalItems', 'salesByDrug', 'startDate', 'endDate'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pharmacy.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/avatars');
            $data['photo_url'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $user->update($data);

        return back()->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('status', 'Password updated successfully.');
    }
}
