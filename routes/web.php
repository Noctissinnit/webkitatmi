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
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/data', [EmployeeController::class, 'getEmployees'])->name('employees.getEmployees');
    Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('admin/extensions/{extension}/install', [DashboardController::class, 'installExtension'])->name('admin.extensions.install');
        
        // Employee management
        Route::resource('admin/employees', EmployeeController::class, [
            'names' => 'admin.employees',
            'parameters' => ['employees' => 'employee'],
        ]);
    });

    // Role management routes
    Route::middleware('role:admin')->resource('roles', RoleController::class);

    // User management routes
    Route::middleware('role:admin')->resource('users', UserController::class);
});

