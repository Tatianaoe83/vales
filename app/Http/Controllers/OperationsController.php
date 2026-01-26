<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vale;
use App\Models\ValeHistory;
use App\Models\Unit;

class OperationsController extends Controller
{
    // Vista del Escáner
    public function index()
    {
        return view('operations.scanner');
    }

    // Paso 1: Buscar el Vale y determinar qué hacer
    public function lookup(Request $request)
    {
        $request->validate(['code' => 'required']);

        // Buscamos por UUID (QR) o Folio legible
        $vale = Vale::with(['material', 'unit', 'sale.client'])
                    ->where('uuid', $request->code)
                    ->orWhere('folio_vale', $request->code)
                    ->first();

        if (!$vale) {
            return response()->json(['status' => 'error', 'message' => 'Código de VALE no encontrado.'], 404);
        }

        $context = ''; 

        // Máquina de estados
        switch ($vale->estatus) {
            case 'Vigente':
                $context = 'entrada'; // Toca validar entrada
                break;
            case 'En Planta':
                $context = 'salida';  // Toca validar salida
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

    // Paso 2: Registrar la Acción (Entrada o Salida)
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

        // --- CASO A: ENTRADA (Guardamos fecha_entrada) ---
        if ($request->accion === 'confirmar_entrada') {
            
            // Si el vale tiene unidad asignada, verificamos QR
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

                // Validación de seguridad: Placas coinciden
                if ($unidadEscaneada->id !== $vale->unit_id) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => "¡ERROR DE SEGURIDAD!\nEl vale pertenece a: {$vale->unit->placa}\nUnidad escaneada: {$unidadEscaneada->placa}"
                    ], 422);
                }
            }

            $nuevoEstatus = 'En Planta';
            $comentario = 'Entrada registrada. Validación Vale + Unidad correcta.';
            
            // GUARDAR HORA DE ENTRADA
            $vale->fecha_entrada = now();
        }

        // --- CASO B: SALIDA SURTIDO (Guardamos fecha_salida) ---
        elseif ($request->accion === 'salida_surtido') {
            $nuevoEstatus = 'Surtido';
            $comentario = 'Salida completa. Material surtido.';

            // GUARDAR HORA DE SALIDA
            $vale->fecha_salida = now();
        }
        
        // --- CASO C: SALIDA VACÍO (Regresa a la fila) ---
        elseif ($request->accion === 'salida_vacio') {
            $nuevoEstatus = 'Vigente';
            $comentario = 'Salida SIN carga. El vale regresa a estatus Vigente.';
            
            // Opcional: Reiniciamos la entrada para que cuente bien la próxima vez
            $vale->fecha_entrada = null; 
        }

        // Aplicamos cambios al Vale
        $vale->estatus = $nuevoEstatus;
        $vale->save();

        // Guardamos Historial
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