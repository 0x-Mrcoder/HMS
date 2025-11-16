<?php

namespace App\Http\Controllers;

use App\Models\NursingNote;
use App\Models\Visit;
use Illuminate\Http\Request;

class NursingController extends Controller
{
    public function index(Request $request)
    {
        $visitId = $request->integer('visit_id');

        $notes = NursingNote::with('visit.patient')
            ->when($visitId, fn ($query) => $query->where('visit_id', $visitId))
            ->latest('recorded_at')
            ->paginate(10)
            ->withQueryString();

        $activeVisits = Visit::with('patient')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByDesc('scheduled_at')
            ->limit(25)
            ->get();

        return view('nursing.index', compact('notes', 'activeVisits', 'visitId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visit_id' => ['required', 'exists:visits,id'],
            'nurse_name' => ['required', 'string', 'max:120'],
            'note_type' => ['nullable', 'string', 'max:120'],
            'note' => ['required', 'string'],
        ]);

        NursingNote::create([
            ...$data,
            'recorded_at' => now(),
        ]);

        return redirect()->route('nursing.notes.index')->with('status', 'Nursing note recorded successfully.');
    }
}
