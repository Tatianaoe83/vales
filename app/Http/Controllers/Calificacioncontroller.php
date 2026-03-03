<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalificacionController extends Controller
{
    /**
     * Pantalla kiosco — siempre abierta en tablet/teléfono.
     * No requiere autenticación para que funcione sin sesión.
     */
    public function index()
    {
        return view('calificacion.index');
    }

    /**
     * Polling endpoint — la pantalla consulta esto cada 4 segundos.
     * Devuelve la venta más reciente pendiente de calificar.
     */
    public function check()
    {
        $sale = Sale::with('client')
            ->where('pendiente_calificacion', true)
            ->whereNull('calificacion')
            ->latest()
            ->first();

        if (!$sale) {
            return response()->json(['pending' => false]);
        }

        return response()->json([
            'pending'    => true,
            'sale_id'    => $sale->id,
            'folio'      => $sale->folio,
            'client'     => $sale->client->name ?? 'Cliente',
            'total'      => number_format($sale->total, 2),
            'subtotal'   => number_format($sale->subtotal, 2),
            'iva'        => number_format($sale->iva, 2),
            'tipo_venta' => $sale->tipo_venta,
            'vales'      => $sale->vales_count ?? $sale->vales()->count(),
        ]);
    }

    /**
     * Guardar calificación enviada desde la pantalla kiosco.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id'      => 'required|exists:sales,id',
            'calificacion' => 'required|integer|between:1,5',
        ]);

        $sale = Sale::findOrFail($request->sale_id);

        $sale->update([
            'calificacion'            => $request->calificacion,
            'calificacion_at'         => Carbon::now(),
            'pendiente_calificacion'  => false,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Cancelar / marcar como "ya calificada o ignorada" sin puntuar.
     * Se llama cuando el timer de 2 min vence sin que el cliente califique.
     */
    public function skip(Request $request)
    {
        $request->validate(['sale_id' => 'required|exists:sales,id']);

        Sale::where('id', $request->sale_id)
            ->update(['pendiente_calificacion' => false]);

        return response()->json(['success' => true]);
    }
}