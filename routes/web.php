<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employee management routes
    Route::get('employees/template/download', [EmployeeController::class, 'downloadTemplate'])->name('employees.downloadTemplate');
    Route::get('employees/data', [EmployeeController::class, 'getEmployees'])->name('employees.getEmployees');
    Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::resource('employees', EmployeeController::class);

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('admin/extensions/{extension}/install', [DashboardController::class, 'installExtension'])->name('admin.extensions.install');
        
        // User management
        Route::get('users/data', [UserController::class, 'getUsers'])->name('users.getUsers');
        Route::resource('users', UserController::class);
        
        // Role management
        Route::get('roles/data', [RoleController::class, 'getRoles'])->name('roles.getRoles');
        Route::resource('roles', RoleController::class);
        
        // Employee management
        Route::get('admin/employees/data', [AdminEmployeeController::class, 'getEmployees'])->name('admin.employees.getData');
        Route::resource('admin/employees', AdminEmployeeController::class, [
            'names' => 'admin.employees',
            'parameters' => ['employees' => 'employee'],
        ]);
    });
});

