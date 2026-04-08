<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employees.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'departemen' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
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
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'departemen' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
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
        if ($request->ajax()) {
            $data = Employee::query();

            return datatables()
                ->eloquent($data)
                ->addIndexColumn()
                ->addColumn('photo', function($row){
                    $photoUrl = $row->photo ? asset('storage/' . $row->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($row->nama);
                    return '<img src="'.$photoUrl.'" alt="'.$row->nama.'" class="w-10 h-10 rounded-full object-cover border border-gray-200">';
                })
                ->addColumn('action', function($row){
                    $edit = '<a href="'.route('employees.edit', $row->id).'" class="text-indigo-600 hover:text-indigo-700 font-medium">Edit</a>';
                    
                    $delete = '<form action="'.route('employees.destroy', $row->id).'" method="POST" class="inline" onsubmit="return confirm(\'Yakin ingin menghapus?\');">';
                    $delete .= csrf_field();
                    $delete .= method_field('DELETE');
                    $delete .= '<button type="submit" class="text-red-600 hover:text-red-700 font-medium">Hapus</button>';
                    $delete .= '</form>';
                    
                    return '<div class="employees-table-actions">'.$edit.' | '.$delete.'</div>';
                })
                ->rawColumns(['photo', 'action'])
                ->make(true);
        }
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
