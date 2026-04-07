<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

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
            $data = Employee::all();

            return datatables()
                ->of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<div class="flex gap-2">';
                    $actionBtn .= '<a href="'.route('employees.edit', $row->id).'" class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded text-sm hover:bg-indigo-200">Edit</a>';
                    $actionBtn .= '<form action="'.route('employees.destroy', $row->id).'" method="POST" class="inline" onsubmit="return confirm(\'Yakin ingin menghapus?\');">';
                    $actionBtn .= csrf_field();
                    $actionBtn .= method_field('DELETE');
                    $actionBtn .= '<button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">Hapus</button>';
                    $actionBtn .= '</form></div>';
                    return $actionBtn;
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
            $import->import($file);

            return back()->with('success', 'Data karyawan berhasil diimport');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Export employees to Excel
     */
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeesExport, 'karyawan.xlsx');
    }
}
