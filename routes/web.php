<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirección al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MÓDULO DE ROLES ---
    Route::middleware('permission:manage roles')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // --- MÓDULO USUARIOS ---
    Route::middleware('permission:manage users')->group(function () {
        Route::patch('/usuarios/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::resource('users', UserController::class)->names('users');
    });

    // --- MÓDULO CLIENTES ---
    Route::middleware('permission:manage clients')->group(function () {
        Route::delete('/clients/{client}/force', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');
        Route::resource('clients', ClientController::class);
    }); 

    Route::resource('materials', App\Http\Controllers\MaterialController::class);

});

require __DIR__.'/auth.php';