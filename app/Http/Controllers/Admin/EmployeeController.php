<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->authorize('view admin panel');
    }

    public function index()
    {
        return view('admin.employees.index');
    }

    /**
     * Get admin employees data for DataTables
     */
    public function getEmployees()
    {
        $employees = Employee::select('employees.*');

        return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('name_display', function ($row) {
                $avatar = '';
                if ($row->photo) {
                    $avatar = '<img src="' . Storage::url($row->photo) . '" alt="' . htmlspecialchars($row->name) . '" class="w-8 h-8 rounded-full object-cover">';
                } else {
                    $avatar = '<div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-sm font-semibold text-white" style="width:32px;height:32px;border-radius:50%;background:#374151;display:flex;align-items:center;justify-content:center;font-size:0.875rem;font-weight:600;color:white;">' . substr($row->name, 0, 1) . '</div>';
                }
                return '<div style="display:flex;align-items:center;gap:0.75rem;">' . $avatar . '<span style="font-weight:500;">' . htmlspecialchars($row->name) . '</span></div>';
            })
            ->addColumn('status_display', function ($row) {
                if ($row->status === 'active') {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-900">Aktif</span>';
                } else {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-900">' . ucfirst($row->status) . '</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="' . route('admin.employees.edit', $row->id) . '" title="Edit employee" style="color:#6b7280;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;">';
                $editBtn .= '<i class="fas fa-edit w-4 h-4"></i> Edit</a>';
                
                $deleteBtn = '<form action="' . route('admin.employees.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Apakah Anda yakin?\');">';
                $deleteBtn .= '<input type="hidden" name="_method" value="DELETE">';
                $deleteBtn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $deleteBtn .= '<button type="submit" title="Delete employee" style="color:#9ca3af;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;background:none;border:none;cursor:pointer;margin-left:1.5rem;">';
                $deleteBtn .= '<i class="fas fa-trash w-4 h-4"></i> Hapus</button></form>';
                
                return '<div class="action-links justify-center" style="display:flex;gap:1.5rem;align-items:center;justify-content:center;">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['name_display', 'status_display', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}
