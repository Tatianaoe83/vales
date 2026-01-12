<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vale;
use App\Models\ValeHistory;
use App\Models\Unit;

class OperationsController extends Controller
{
    public function index()
    {
        return view('operations.scanner');
    }

    public function lookup(Request $request)
    {
        $request->validate(['code' => 'required']);

        $vale = Vale::with(['material', 'unit', 'sale.client'])
                    ->where('uuid', $request->code)
                    ->orWhere('folio_vale', $request->code)
                    ->first();

        if (!$vale) {
            return response()->json(['status' => 'error', 'message' => 'Código de VALE no encontrado.'], 404);
        }

        $context = ''; 

        switch ($vale->estatus) {
            case 'Vigente':
                $context = 'entrada'; 
                break;
            case 'En Planta':
                $context = 'salida'; 
                break;
            case 'Surtido':
                return response()->json(['status' => 'error', 'message' => 'Este vale YA FUE SURTIDO anteriormente.'], 422);
            case 'Vencido':
                return response()->json(['status' => 'error', 'message' => 'VALE VENCIDO. No dar acceso.'], 422);
            case 'Cancelado':
                return response()->json(['status' => 'error', 'message' => 'VALE CANCELADO.'], 422);
            default:
                $context = 'error';
        }

        return response()->json([
            'status' => 'success',
            'data' => $vale,
            'context' => $context
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'vale_id' => 'required|exists:vales,id',
            'accion' => 'required|in:confirmar_entrada,salida_surtido,salida_vacio',
            'unit_code' => 'nullable'
        ]);

        $vale = Vale::with('unit')->findOrFail($request->vale_id);
        $estatusAnterior = $vale->estatus;
        $nuevoEstatus = '';
        $comentario = '';

        if ($request->accion === 'confirmar_entrada') {
            
            if ($vale->unit) {
                if (!$request->unit_code) {
                    return response()->json(['status' => 'error', 'message' => 'Debe escanear el QR de la unidad para validar.'], 422);
                }

                $unidadEscaneada = Unit::where('uuid', $request->unit_code)
                                       ->orWhere('placa', $request->unit_code)
                                       ->first();

                if (!$unidadEscaneada) {
                    return response()->json(['status' => 'error', 'message' => 'El QR de la unidad no existe en el sistema.'], 422);
                }

                if ($unidadEscaneada->id !== $vale->unit_id) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => "¡ERROR DE SEGURIDAD!\nEl vale pertenece a: {$vale->unit->placa}\nUnidad escaneada: {$unidadEscaneada->placa}"
                    ], 422);
                }
            }

            $nuevoEstatus = 'En Planta';
            $comentario = 'Entrada registrada. Validación Vale + Unidad correcta.';
        }

        elseif ($request->accion === 'salida_surtido') {
            $nuevoEstatus = 'Surtido';
            $comentario = 'Salida completa. Material surtido.';
        }
        elseif ($request->accion === 'salida_vacio') {
            $nuevoEstatus = 'Vigente';
            $comentario = 'Salida SIN carga. El vale regresa a estatus Vigente.';
        }

        $vale->update(['estatus' => $nuevoEstatus]);

        ValeHistory::create([
            'vale_id' => $vale->id,
            'user_id' => auth()->id(),
            'estatus_anterior' => $estatusAnterior,
            'estatus_nuevo' => $nuevoEstatus,
            'comentarios' => $comentario
        ]);

        return response()->json(['status' => 'success', 'message' => 'Movimiento registrado correctamente.']);
    }
}