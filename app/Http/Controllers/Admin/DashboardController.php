<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('view admin panel');

        $stats = [
            'total_users' => \App\Models\User::count(),
            'employees_table_exists' => Schema::hasTable('employees'),
            'total_employees' => Schema::hasTable('employees') ? \App\Models\Employee::count() : 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function installExtension($extension)
    {
        $this->authorize('view admin panel');

        if ($extension === 'employees') {
            // Create employees table
            Schema::create('employees', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('position')->nullable();
                $table->string('department')->nullable();
                $table->date('hire_date')->nullable();
                $table->string('photo')->nullable();
                $table->timestamps();
            });

            return redirect()->route('admin.dashboard')->with('success', 'Sistem Karyawan telah diaktifkan!');
        }

        return redirect()->back()->with('error', 'Extension tidak dikenal');
    }
}

