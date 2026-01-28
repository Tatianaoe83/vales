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

    // PASO 1: Lookup del Vale
    public function lookup(Request $request)
    {
        if (!$request->code) {
            return $this->error('CODIGO_VACIO', 'Escanee o capture un código');
        }

        $vale = Vale::with(['material', 'unit', 'sale.client'])
            ->where('uuid', $request->code)
            ->orWhere('folio_vale', $request->code)
            ->first();

        if (!$vale) {
            return $this->error('VALE_NO_EXISTE', 'Vale no encontrado');
        }

        switch ($vale->estatus) {
            case 'Vigente':
                $context = 'entrada';
                break;

            case 'En Planta':
                $context = 'salida';
                break;

            case 'Surtido':
                return $this->error('VALE_SURTIDO', 'Este vale ya fue surtido');

            case 'Vencido':
                return $this->error('VALE_VENCIDO', 'Vale vencido. No permitir acceso');

            case 'Cancelado':
                return $this->error('VALE_CANCELADO', 'Vale cancelado');

            default:
                return $this->error('ESTATUS_INVALIDO', 'Estatus del vale no válido');
        }

        return response()->json([
            'status' => 'success',
            'data' => $vale,
            'context' => $context
        ]);
    }

    // PASO 2: Registro de acciones
    public function register(Request $request)
    {
        if (!$request->vale_id || !$request->accion) {
            return $this->error('DATOS_INCOMPLETOS', 'Información incompleta');
        }

        $vale = Vale::with('unit')->find($request->vale_id);

        if (!$vale) {
            return $this->error('VALE_NO_EXISTE', 'Vale no encontrado');
        }

        $estatusAnterior = $vale->estatus;
        $nuevoEstatus = '';
        $comentario = '';

        // ENTRADA
        if ($request->accion === 'confirmar_entrada') {

            if ($vale->estatus !== 'Vigente') {
                return $this->error('ENTRADA_DUPLICADA', 'La entrada ya fue registrada');
            }

            if ($vale->unit) {

                if (!$request->unit_code) {
                    return $this->error('QR_UNIDAD_REQUERIDO', 'Escanee el QR de la unidad');
                }

                $unidadEscaneada = Unit::where('uuid', $request->unit_code)
                    ->orWhere('placa', $request->unit_code)
                    ->first();

                if (!$unidadEscaneada) {
                    return $this->error('UNIDAD_NO_EXISTE', 'Unidad no registrada en el sistema');
                }

                if ($unidadEscaneada->id !== $vale->unit_id) {
                    return $this->error(
                        'UNIDAD_NO_COINCIDE',
                        "Unidad incorrecta. Vale asignado a {$vale->unit->placa}"
                    );
                }
            }

            $nuevoEstatus = 'En Planta';
            $comentario = 'Entrada registrada correctamente';
            $vale->fecha_entrada = now();
        }

        // SALIDA SURTIDO
        elseif ($request->accion === 'salida_surtido') {

            if ($vale->estatus !== 'En Planta') {
                return $this->error('SALIDA_INVALIDA', 'La unidad no está en planta');
            }

            $nuevoEstatus = 'Surtido';
            $comentario = 'Salida con carga';
            $vale->fecha_salida = now();
        }

        // SALIDA VACÍO
        elseif ($request->accion === 'salida_vacio') {

            if ($vale->estatus !== 'En Planta') {
                return $this->error('SALIDA_INVALIDA', 'La unidad no está en planta');
            }

            $nuevoEstatus = 'Vigente';
            $comentario = 'Salida sin carga';
            $vale->fecha_entrada = null;
        }

        else {
            return $this->error('ACCION_INVALIDA', 'Acción no reconocida');
        }

        $vale->estatus = $nuevoEstatus;
        $vale->save();

        ValeHistory::create([
            'vale_id' => $vale->id,
            'user_id' => auth()->id(),
            'estatus_anterior' => $estatusAnterior,
            'estatus_nuevo' => $nuevoEstatus,
            'comentarios' => $comentario
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Movimiento registrado correctamente'
        ]);
    }

    private function error($code, $message)
    {
        return response()->json([
            'status' => 'error',
            'code' => $code,
            'message' => $message
        ]);
    }
}
