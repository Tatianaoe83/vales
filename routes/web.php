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
use App\Http\Controllers\CalificacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─────────────────────────────────────────────────────────────────────────────
// PÚBLICO — Calificación (sin auth, acceso por enlace externo)
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('calificacion')->name('calificacion.')->group(function () {
    Route::get('/',      [CalificacionController::class, 'index']) ->name('index');
    Route::get('/check', [CalificacionController::class, 'check']) ->name('check');
    Route::post('/rate', [CalificacionController::class, 'store']) ->name('store');
    Route::post('/skip', [CalificacionController::class, 'skip'])  ->name('skip');
});

// ─────────────────────────────────────────────────────────────────────────────
// PÚBLICO — Redirección raíz
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ─────────────────────────────────────────────────────────────────────────────
// AUTENTICADO — Dashboard
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ─────────────────────────────────────────────────────────────────────────────
// AUTENTICADO — Grupo principal
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── Perfil (cualquier usuario logueado) ───────────────────────────────────
    Route::controller(ProfileController::class)->group(function () {
        Route::get   ('/profile', 'edit')   ->name('profile.edit');
        Route::patch ('/profile', 'update') ->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // ── Búsqueda global ───────────────────────────────────────────────────────
    Route::get('/buscar', [SearchController::class, 'globalSearch'])->name('search.global');

    // ── Seguridad — Roles ─────────────────────────────────────────────────────
    Route::middleware('permission:manage roles')
        ->resource('roles', RoleController::class);

    // ── Seguridad — Usuarios ──────────────────────────────────────────────────
    Route::middleware('permission:manage users')->group(function () {
        Route::patch('/usuarios/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::resource('users', UserController::class)->names('users');
    });

    // ── Catálogo — Clientes ───────────────────────────────────────────────────
    Route::middleware('permission:manage clients')->group(function () {
        // API interna
        Route::get('/api/clients/{id}', [ClientController::class, 'getById']);

        // Historial de compras (modal)
        Route::get('/clients/{id}/sales-history', [ClientController::class, 'salesHistory'])
            ->name('clients.salesHistory');

        // Eliminar definitivamente
        Route::delete('/clients/{client}/force', [ClientController::class, 'forceDelete'])
            ->name('clients.forceDelete');

        Route::resource('clients', ClientController::class);
    });

    // ── Catálogo — Materiales ─────────────────────────────────────────────────
    Route::middleware('permission:manage materials')->group(function () {
        Route::get('/materials/{material}/history', [MaterialController::class, 'history'])
            ->name('materials.history');
        Route::resource('materials', MaterialController::class);
    });

    // ── Catálogo — Unidades / Camiones ────────────────────────────────────────
    Route::middleware('permission:manage units')
        ->controller(UnitController::class)
        ->prefix('units')
        ->name('units.')
        ->group(function () {
            Route::get   ('/',                 'index')           ->name('index');
            Route::post  ('/',                 'store')           ->name('store');
            Route::get   ('/{id}/edit',        'edit')            ->name('edit');
            Route::put   ('/{id}',             'update')          ->name('update');
            Route::delete('/{id}',             'destroy')         ->name('destroy');
            Route::get   ('/gafete/{uuid}',    'descargarGafete') ->name('gafete');
            Route::get   ('/check-access/{uuid}', 'validateAccess')->name('validate_access');
        });

    // ── Operaciones — Ventas ──────────────────────────────────────────────────
    Route::middleware('permission:view tickets|create tickets')
        ->controller(SaleController::class)
        ->prefix('sales')
        ->name('sales.')
        ->group(function () {
            Route::get ('/create',     'create')->name('create');
            Route::post('/',           'store') ->name('store');
            Route::get ('/{id}',       'show')  ->name('show');
            Route::get ('/{id}/ticket','ticket')->name('ticket');
            Route::get ('/{id}/pdf',   'pdf')   ->name('pdf');
            Route::get ('/{id}/email', 'email') ->name('email');
            Route::get ('/',           'index') ->name('index');
        });

    // ── Operaciones — Vales ───────────────────────────────────────────────────
    Route::middleware('permission:view tickets')
        ->controller(ValeController::class)
        ->prefix('vales')
        ->name('vales.')
        ->group(function () {
            Route::get  ('/{id}/history', 'history')     ->name('history');
            Route::post ('/{id}/restore', 'restore')     ->name('restore');
            Route::post ('/{id}/status',  'updateStatus')->name('status');
            Route::get  ('/export/{format}', 'export')  ->name('export');
        });

    // ── Operaciones — Caseta / Escáner ────────────────────────────────────────
    Route::middleware('permission:validate exit')
        ->controller(OperationsController::class)
        ->group(function () {
            Route::get  ('/scanner',             'index')   ->name('scanner.index');
            Route::post ('/operations/lookup',   'lookup')  ->name('operations.lookup');
            Route::post ('/operations/register', 'register')->name('operations.register');
        });

    // ── Reportes ──────────────────────────────────────────────────────────────
    Route::middleware('permission:view reports')
        ->controller(ReportController::class)
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {
            Route::get('/export/excel', 'exportExcel')->name('excel');
            Route::get('/export/pdf',   'exportPdf')  ->name('pdf');
            Route::get('/',             'index')       ->name('index');
        });

});

require __DIR__ . '/auth.php';