<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $visits = Visit::with(['patient', 'department'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('scheduled_at')
            ->paginate(10)
            ->withQueryString();

        return view('visits.index', compact('visits', 'status'));
    }

    public function show(Visit $visit)
    {
        $visit->load(['patient', 'department', 'service', 'prescriptions', 'labTests', 'nursingNotes']);

        return view('visits.show', compact('visit'));
    }

    public function updateStatus(Request $request, Visit $visit)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed,billed'],
        ]);

        $visit->update($data);

        return redirect()->route('visits.show', $visit)->with('status', 'Visit status updated.');
    }
}
