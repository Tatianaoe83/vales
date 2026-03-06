<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function index()
    {
        return view('calificacion.index');
    }

    public function check()
    {
        $sale = Sale::with(['client', 'vales.unit', 'vales.material'])
            ->where('pendiente_calificacion', 1)
            ->whereNull('calificacion')
            ->latest()
            ->first();

        if (!$sale) {
            return response()->json(['pending' => false]);
        }

        // ── Detalle de vales ──────────────────────────────────────────────
        $valesDetail = $sale->vales->map(fn($v) => [
            'folio_vale'    => $v->folio_vale,
            'cantidad'      => $v->cantidad,
            'unidad_medida' => $v->material->unit  ?? '',
            'material'      => $v->material->name  ?? '—',
            'unidad'        => $v->unit->placa ?? null,
            'estatus'       => $v->estatus,
        ])->values();

        // ── Detalle de materiales (agrupado por material) ─────────────────
        $materialsDetail = $sale->vales
            ->groupBy('material_id')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'nombre'    => $first->material->name ?? '—',
                    'unidad'    => $first->material->unit ?? '',
                    'cantidad'  => $group->sum('cantidad'),
                    'precio'    => 0,
                    'descuento' => 0,
                ];
            })->values();

        return response()->json([
            'pending'          => true,
            'sale_id'          => $sale->id,
            'folio'            => $sale->folio,
            'client'           => $sale->client->name ?? 'Cliente',
            'total'            => number_format($sale->total,    2),
            'subtotal'         => number_format($sale->subtotal, 2),
            'iva'              => number_format($sale->iva,      2),
            'descuento'        => number_format($sale->descuento ?? 0, 2),
            'tipo_venta'       => $sale->tipo_venta,
            'vales'            => $sale->vales->count(),
            'vales_detail'     => $valesDetail,
            'materials_detail' => $materialsDetail,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id'      => 'required|exists:sales,id',
            'calificacion' => 'required|integer|between:1,3',
        ]);

        \DB::table('sales')->where('id', $request->sale_id)->update([
            'calificacion'           => $request->calificacion,
            'calificacion_at'        => now(),
            'pendiente_calificacion' => 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function skip(Request $request)
    {
        $request->validate(['sale_id' => 'required|exists:sales,id']);

        \DB::table('sales')->where('id', $request->sale_id)->update([
            'pendiente_calificacion' => 0,
        ]);

        return response()->json(['success' => true]);
    }
}