<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Service;
use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    public function index()
    {
        $departments = Department::with('services')->orderBy('name')->get();

        return view('admin.management', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
            'category' => ['nullable', 'string', 'max:120'],
            'head_of_department' => ['nullable', 'string', 'max:120'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
        ]);

        Department::create($data);

        return redirect()->route('administration.index')->with('status', 'Department created successfully.');
    }

    public function storeService(Request $request)
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:30'],
            'service_type' => ['nullable', 'string', 'max:120'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'is_billable' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        Service::create([
            ...$data,
            'is_billable' => (bool) ($data['is_billable'] ?? false),
        ]);

        return redirect()->route('administration.index')->with('status', 'Service added successfully.');
    }
}
