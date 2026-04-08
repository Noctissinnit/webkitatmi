<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index');
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Department berhasil ditambahkan');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Department berhasil diperbarui');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department berhasil dihapus');
    }

    // API endpoint untuk DataTables
    public function getDepartments()
    {
        $departments = Department::select('departments.*');

        return DataTables::of($departments)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="' . route('departments.edit', $row->id) . '" title="Edit" style="color:#6b7280;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;">';
                $editBtn .= '<i class="fas fa-edit w-4 h-4"></i> Edit</a>';
                
                $deleteBtn = '<form action="' . route('departments.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this department?\');">';
                $deleteBtn .= '<input type="hidden" name="_method" value="DELETE">';
                $deleteBtn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $deleteBtn .= '<button type="submit" title="Delete" style="color:#9ca3af;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;background:none;border:none;cursor:pointer;margin-left:1.5rem;">';
                $deleteBtn .= '<i class="fas fa-trash w-4 h-4"></i> Delete</button></form>';
                
                return '<div class="action-links justify-center" style="display:flex;gap:1.5rem;align-items:center;justify-content:center;">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
