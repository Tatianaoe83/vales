<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SaleController; // <--- Faltaba importar este

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
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
        // API interna para el Wizard de Ventas (AJAX)
        Route::get('/api/clients/{id}', [ClientController::class, 'getById']);
        
        Route::delete('/clients/{client}/force', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');
        Route::resource('clients', ClientController::class);
    }); 

    // --- MÓDULO MATERIALES ---
    Route::resource('materials', MaterialController::class);

    // --- MÓDULO UNIDADES ---
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::get('/units/gafete/{uuid}', [UnitController::class, 'descargarGafete'])->name('units.gafete');
    Route::get('/check-access/{uuid}', [UnitController::class, 'validateAccess'])->name('units.validate_access');
    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::get('/units/{id}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('/units/{id}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}', [UnitController::class, 'destroy'])->name('units.destroy');

    // --- MÓDULO VENTAS (SALES) ---
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales', [App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');   
    Route::get('/vales/{id}/history', [App\Http\Controllers\ValeController::class, 'history'])->name('vales.history');
    Route::post('/vales/{id}/restore', [App\Http\Controllers\ValeController::class, 'restore'])->name('vales.restore');

    
    Route::get('/sales/{id}/ticket', [SaleController::class, 'ticket'])->name('sales.ticket');
    Route::get('/sales/{id}/pdf', [SaleController::class, 'pdf'])->name('sales.pdf');
    Route::get('/sales/{id}/email', [SaleController::class, 'email'])->name('sales.email');
    Route::post('/vales/{id}/status', [App\Http\Controllers\ValeController::class, 'updateStatus'])->name('vales.status');  

    // --- RUTAS DE OPERACIONES (CASETA) ---
    Route::get('/scanner', [App\Http\Controllers\OperationsController::class, 'index'])->name('operations.scanner');
    Route::post('/scanner/lookup', [App\Http\Controllers\OperationsController::class, 'lookup'])->name('operations.lookup');
    Route::post('/scanner/register', [App\Http\Controllers\OperationsController::class, 'register'])->name('operations.register');
    Route::get('/vales/export/{format}', [App\Http\Controllers\ValeController::class, 'export'])->name('vales.export');


});

require __DIR__.'/auth.php';