<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vale;
use App\Models\Client;
use App\Models\Material;
use App\Models\Unit;
use App\Exports\ValesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Muestra la vista con los filtros y la tabla
    public function index(Request $request)
    {
        // Obtener listas para los selectores
        $clients = Client::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        $units = Unit::orderBy('placa')->get();

        // Aplicar filtros si existen
        $vales = $this->filtrarVales($request)->paginate(10);

        return view('reports.index', compact('vales', 'clients', 'materials', 'units'));
    }

    // Exportar a Excel
    public function exportExcel(Request $request)
    {
        $vales = $this->filtrarVales($request)->get();
        return Excel::download(new ValesExport($vales), 'reporte_vales_' . now()->format('Ymd_His') . '.xlsx');
    }

    // Exportar a PDF
    public function exportPdf(Request $request)
    {
        $vales = $this->filtrarVales($request)->get();
        
        $pdf = Pdf::loadView('reports.pdf', compact('vales'))
                  ->setPaper('a4', 'landscape'); // Horizontal para que quepan las columnas

        return $pdf->download('reporte_vales_' . now()->format('Ymd_His') . '.pdf');
    }

    // --- LÓGICA PRIVADA DE FILTRADO (REUTILIZABLE) ---
    private function filtrarVales(Request $request)
    {
        $query = Vale::with(['sale.client', 'material', 'unit'])
             ->orderBy('id', 'desc');

        // 1. Rango de Fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // 2. Por Cliente (Relación sale -> client)
        if ($request->filled('client_id')) {
            $query->whereHas('sale', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        // 3. Por Material
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        // 4. Por Unidad
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        return $query;
    }
}