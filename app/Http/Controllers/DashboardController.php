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
        $totalValesActivos = Vale::where('estatus', 'Vigente')->count();
        
        $ventasHoy = Sale::whereDate('created_at', Carbon::today())->sum('total');
        
        $entregasPendientes = Vale::whereIn('estatus', ['Vigente', 'En Planta'])->count();
        
        $nuevosClientes = Client::whereMonth('created_at', Carbon::now()->month)->count();

        $actividadReciente = ValeHistory::with(['vale.sale.client', 'vale.material'])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        $ventasPorDia = Sale::select(
            DB::raw('DATE(created_at) as date'), 
            DB::raw('SUM(total) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName = Carbon::now()->subDays($i)->locale('es')->isoFormat('ddd'); // Lun, Mar...
            
            $ventaDia = $ventasPorDia->firstWhere('date', $date);
            
            $chartLabels[] = ucfirst($dayName);
            $chartData[] = $ventaDia ? $ventaDia->total : 0;
        }

        return view('dashboard', compact(
            'totalValesActivos', 
            'ventasHoy', 
            'entregasPendientes', 
            'nuevosClientes',
            'actividadReciente',
            'chartLabels',
            'chartData'
        ));
    }
}