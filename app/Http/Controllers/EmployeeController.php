<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.employees.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('admin.employees.create', compact('departments', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees', 'public');
        }

        $validated['photo'] = $photoPath;
        Employee::create($validated);

        return redirect()->route('employees.index')
                        ->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('admin.employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
                        ->with('success', 'Karyawan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
                        ->with('success', 'Karyawan berhasil dihapus');
    }

    /**
     * Get employees data for DataTable
     */
    public function getEmployees(Request $request)
    {
        $data = Employee::with('department', 'position')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photo', function($row){
                $photoUrl = $row->photo ? asset('storage/' . $row->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($row->nama);
                return '<img src="'.$photoUrl.'" alt="'.$row->nama.'" class="w-10 h-10 rounded-full object-cover border border-gray-200">';
            })
            ->addColumn('departemen', function($row){
                return $row->department ? $row->department->name : '-';
            })
            ->addColumn('jabatan', function($row){
                return $row->position ? $row->position->name : '-';
            })
            ->addColumn('action', function($row){
                $edit = '<a href="'.route('employees.edit', $row->id).'" title="Edit" style="color:#6b7280;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;"><i class="fas fa-edit w-4 h-4"></i> Edit</a>';
                
                $delete = '<form action="'.route('employees.destroy', $row->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Apakah Anda yakin?\');">';
                $delete .= '<input type="hidden" name="_method" value="DELETE">';
                $delete .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $delete .= '<button type="submit" title="Delete" style="color:#9ca3af;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;background:none;border:none;cursor:pointer;margin-left:1.5rem;"><i class="fas fa-trash w-4 h-4"></i> Hapus</button>';
                $delete .= '</form>';
                
                return '<div class="action-links justify-center" style="display:flex;gap:1.5rem;align-items:center;justify-content:center;">'.$edit.' '.$delete.'</div>';
            })
            ->rawColumns(['photo', 'departemen', 'jabatan', 'action'])
            ->make(true);
    }

    /**
     * Import employees from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $import = new \App\Imports\EmployeesImport();
            Excel::import($import, $file);

            $message = 'Data karyawan berhasil diimport: ' . $import->getImported() . ' data baru ditambahkan';
            if ($import->getSkipped() > 0) {
                $message .= ', ' . $import->getSkipped() . ' data diperbarui karena email sudah ada';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Export employees to Excel
     */
    public function export()
    {
        return Excel::download(new \App\Exports\EmployeesExport, 'karyawan.xlsx');
    }

    /**
     * Download template Excel untuk import
     */
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\EmployeeTemplateExport, 'template_karyawan.xlsx');
    }
}
