<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ValeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\OperationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::prefix('calificacion')->name('calificacion.')->group(function () {
    Route::get('/',        [App\Http\Controllers\CalificacionController::class, 'index'])  ->name('index');
    Route::get('/check',   [App\Http\Controllers\CalificacionController::class, 'check'])  ->name('check');
    Route::post('/rate',   [App\Http\Controllers\CalificacionController::class, 'store'])  ->name('store');
    Route::post('/skip',   [App\Http\Controllers\CalificacionController::class, 'skip'])   ->name('skip');
});

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard General (Accesible para todos los logueados, el controlador decidirá qué mostrar)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Grupo Principal (Autenticado)
Route::middleware('auth')->group(function () {

    // --- PERFIL (Cualquier usuario logueado puede editar su propio perfil) ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // --- SEGURIDAD (Solo Administradores) ---
    Route::middleware('permission:manage roles')->resource('roles', RoleController::class);

    Route::middleware('permission:manage users')->group(function () {
        Route::patch('/usuarios/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::resource('users', UserController::class)->names('users');
    });

    // --- CATÁLOGOS (Administradores y Ventas) ---
    Route::middleware('permission:manage clients')->group(function () {
        Route::get('/api/clients/{id}', [ClientController::class, 'getById']);
        Route::delete('/clients/{client}/force', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');
        Route::resource('clients', ClientController::class);
    });

    // Agregamos middleware a Materiales
    Route::middleware('permission:manage materials')->group(function () {
        Route::get('/materials/{material}/history', [MaterialController::class, 'history'])->name('materials.history');
        Route::resource('materials', MaterialController::class);
    });

    Route::middleware(['auth'])->group(function () {
    // Agrega esta línea
    Route::get('/buscar', [SearchController::class, 'globalSearch'])->name('search.global');
    }); 

    // Agregamos middleware a Unidades/Camiones
    Route::middleware('permission:manage units')->controller(UnitController::class)->prefix('units')->name('units.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/gafete/{uuid}', 'descargarGafete')->name('gafete');
        Route::get('/check-access/{uuid}', 'validateAccess')->name('validate_access');
    });

    // --- OPERACIONES: VENTAS Y VALES ---
    // Usamos 'view tickets' y 'create tickets' para proteger estas rutas
    Route::middleware('permission:view tickets|create tickets')->controller(SaleController::class)->prefix('sales')->name('sales.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/ticket', 'ticket')->name('ticket');
        Route::get('/{id}/pdf', 'pdf')->name('pdf');
        Route::get('/{id}/email', 'email')->name('email');
    });

    Route::middleware('permission:view tickets')->controller(ValeController::class)->prefix('vales')->name('vales.')->group(function () {
        Route::get('/{id}/history', 'history')->name('history');
        Route::post('/{id}/restore', 'restore')->name('restore');
        Route::post('/{id}/status', 'updateStatus')->name('status');
        Route::get('/export/{format}', 'export')->name('export');
    });

    // --- OPERACIONES: CASETA / ESCÁNER ---
    // Protegido específicamente para quienes validan salidas (Caseta)
    Route::middleware('permission:validate exit')->controller(OperationsController::class)->group(function () {
        Route::get('/scanner', 'index')->name('scanner.index'); 
        Route::post('/operations/lookup', 'lookup')->name('operations.lookup');
        Route::post('/operations/register', 'register')->name('operations.register');
    });

    // --- REPORTES ---
    Route::middleware('permission:view reports')->controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export/excel', 'exportExcel')->name('excel');
        Route::get('/export/pdf', 'exportPdf')->name('pdf');
    });

});

require __DIR__.'/auth.php';