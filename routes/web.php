<?php

use App\Authorization\Permissions;
use App\Http\Controllers\AdminActivityLogController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GraduationYearController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit', fn () => redirect()->route('profile.edit'));
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware(['permission:'.Permissions::DASHBOARD_VIEW])->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/students/by-year', [DashboardController::class, 'studentsByYear'])->name('students.by-year');
        Route::get('/profiles/{profile}', [DashboardController::class, 'showProfile'])->name('profiles.show');
    });

    Route::get('/graduates/export', [DashboardController::class, 'exportGraduates'])
        ->middleware(['permission:'.Permissions::GRADUATES_EXPORT])
        ->name('graduates.export');

    Route::middleware(['permission:'.Permissions::APPLICATIONS_MANAGE])->group(function (): void {
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
    });

    Route::middleware(['permission:'.Permissions::CATALOG_MANAGE])->group(function (): void {
        Route::resource('universities', UniversityController::class)->except(['show']);

        Route::resource('graduation-years', GraduationYearController::class)->except(['show']);

        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('departments.specializations', SpecializationController::class)
            ->except(['show'])
            ->scoped();
    });

    Route::middleware(['permission:'.Permissions::USERS_MANAGE])->group(function (): void {
        Route::resource('users', AdminUserController::class)->except(['show']);
    });

    Route::get('/activity', [AdminActivityLogController::class, 'index'])
        ->middleware(['permission:'.Permissions::ACTIVITY_LOG_VIEW])
        ->name('activity.index');

    Route::middleware(['permission:'.Permissions::ROLES_MANAGE])->group(function (): void {
        Route::get('/roles/create', [AdminRoleController::class, 'createRole'])->name('roles.create');
        Route::post('/roles', [AdminRoleController::class, 'storeRole'])->name('roles.store');
        Route::get('/permissions/create', [AdminRoleController::class, 'createPermission'])->name('permissions.create');
        Route::post('/permissions', [AdminRoleController::class, 'storePermission'])->name('permissions.store');
        Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');
    });
});
