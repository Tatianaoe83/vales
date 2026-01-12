<?php

namespace App\Http\Controllers;

use App\Models\Vale;
use App\Models\ValeHistory;
use Illuminate\Http\Request;
use App\Exports\ValesExport;
use Maatwebsite\Excel\Facades\Excel;

class ValeController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $vale = Vale::findOrFail($id);
        
        $request->validate([
            'estatus' => 'required|in:En Planta,Surtido,Cancelado'
        ]);

        $estatusAnterior = $vale->estatus;
        $estatusNuevo = $request->estatus;

        if ($estatusAnterior == 'Surtido' && $estatusNuevo != 'Cancelado') {
             return back()->withErrors('Este vale ya fue surtido, no se puede modificar.');
        }

        $vale->update(['estatus' => $estatusNuevo]);

        ValeHistory::create([
            'vale_id' => $vale->id,
            'user_id' => auth()->id(),
            'estatus_anterior' => $estatusAnterior,
            'estatus_nuevo' => $estatusNuevo,
            'comentarios' => $request->comentarios ?? 'Cambio de estatus manual desde panel.'
        ]);

        return back()->with('success', "Estatus actualizado a: $estatusNuevo");
    }

    public function restore($id)
    {
        $vale = Vale::findOrFail($id);

        if ($vale->estatus != 'Vencido') {
            return back()->withErrors('Solo se pueden restablecer vales vencidos.');
        }

        $vale->update(['estatus' => 'Vigente']);

        $vale->sale->update([
            'fecha_vencimiento' => \Carbon\Carbon::now()->addDays(15)
        ]);

        ValeHistory::create([
            'vale_id' => $vale->id,
            'user_id' => auth()->id(),
            'estatus_anterior' => 'Vencido',
            'estatus_nuevo' => 'Vigente',
            'comentarios' => 'Restablecido manualmente. Se extendió el plazo 15 días.'
        ]);

        return back()->with('success', 'Vale restablecido y vigencia extendida.');
    }

    public function history($id)
    {
        $historial = ValeHistory::with('user')
                        ->where('vale_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return response()->json($historial);
    }

    public function export($format)
    {
        $extension = $format == 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
        $nombreArchivo = 'Reporte_Vales_' . date('Y-m-d') . '.' . $format;

        return Excel::download(new ValesExport, $nombreArchivo, $extension);
    }
}