<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GraduationYearController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\UniversityController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/register');

Route::get('/register', [ApplicationController::class, 'create'])->name('applications.create');
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/students/by-year', [DashboardController::class, 'studentsByYear'])->name('students.by-year');
    Route::get('/graduates/export', [DashboardController::class, 'exportGraduates'])->name('graduates.export');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

    Route::resource('universities', UniversityController::class)->except(['show']);

    Route::resource('graduation-years', GraduationYearController::class)->except(['show']);

    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('departments.specializations', SpecializationController::class)
        ->except(['show'])
        ->scoped();
});
