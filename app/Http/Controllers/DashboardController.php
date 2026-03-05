<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Vale;
use App\Models\Client;
use App\Models\ValeHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── KPIs principales ──────────────────────────────────────────────────
        $totalValesActivos  = Vale::where('estatus', 'Vigente')->count();
        $ventasHoy          = Sale::whereDate('created_at', Carbon::today())->sum('total');
        $entregasPendientes = Vale::whereIn('estatus', ['Vigente', 'En Planta'])->count();
        $nuevosClientes     = Client::whereMonth('created_at', Carbon::now()->month)->count();

        // Calificaciones — solo esta semana
        $inicioSemana = Carbon::now()->startOfWeek();

        $promedioCalificacion = Sale::whereNotNull('calificacion')
            ->where('created_at', '>=', $inicioSemana)
            ->avg('calificacion') ?? 0;

        $totalCalificaciones = Sale::whereNotNull('calificacion')
            ->where('created_at', '>=', $inicioSemana)
            ->count();

        $rawDistribucion = Sale::whereNotNull('calificacion')
            ->where('created_at', '>=', $inicioSemana)
            ->selectRaw('ROUND(calificacion) as estrella, COUNT(*) as total')
            ->groupBy('estrella')
            ->pluck('total', 'estrella')
            ->toArray();

        $distribucionCalificaciones = [];
        foreach (range(1, 5) as $star) {
            $distribucionCalificaciones[$star] = $rawDistribucion[$star] ?? 0;
        }
        
        // Distribución por estrella (1–5), garantiza las 5 claves aunque no haya datos
        $rawDistribucion = Sale::whereNotNull('calificacion')
            ->selectRaw('ROUND(calificacion) as estrella, COUNT(*) as total')
            ->groupBy('estrella')
            ->pluck('total', 'estrella')
            ->toArray();

        $distribucionCalificaciones = [];
        foreach (range(1, 5) as $star) {
            $distribucionCalificaciones[$star] = $rawDistribucion[$star] ?? 0;
        }

        // ── Actividad reciente ────────────────────────────────────────────────
        $actividadReciente = ValeHistory::with(['vale.sale.client', 'vale.material'])
            ->latest()
            ->take(5)
            ->get();

        // ── Gráfica de ventas (últimos 7 días) ────────────────────────────────
        $ventasPorDia = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date       = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName    = Carbon::now()->subDays($i)->locale('es')->isoFormat('ddd');
            $ventaDia   = $ventasPorDia->firstWhere('date', $date);
            $chartLabels[] = ucfirst($dayName);
            $chartData[]   = $ventaDia ? $ventaDia->total : 0;
        }

        return view('dashboard', compact(
            'totalValesActivos',
            'ventasHoy',
            'entregasPendientes',
            'nuevosClientes',
            'actividadReciente',
            'chartLabels',
            'chartData',
            'promedioCalificacion',
            'totalCalificaciones',
            'distribucionCalificaciones',
        ));
    }
}