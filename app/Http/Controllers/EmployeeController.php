<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        ]);

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
        ]);

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
                ->addColumn('action', function($row){
                    $edit = '<a href="'.route('employees.edit', $row->id).'" class="text-indigo-600 hover:text-indigo-700 font-medium">Edit</a>';
                    
                    $delete = '<form action="'.route('employees.destroy', $row->id).'" method="POST" class="inline" onsubmit="return confirm(\'Yakin ingin menghapus?\');">';
                    $delete .= csrf_field();
                    $delete .= method_field('DELETE');
                    $delete .= '<button type="submit" class="text-red-600 hover:text-red-700 font-medium">Hapus</button>';
                    $delete .= '</form>';
                    
                    return '<div class="employees-table-actions">'.$edit.' | '.$delete.'</div>';
                })
                ->rawColumns(['action'])
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
