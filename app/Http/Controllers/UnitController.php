<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('client')->latest()->paginate(10);
        
        $clients = Client::where('is_active', true)->get(); 

        return view('units.index', compact('units', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'placa' => 'required|string|max:20|unique:units,placa',
            
            'tipo_vehiculo' => 'required|in:Olla,Volteo,Plataforma,Gondola,Caja Seca,Torton,Rabón', 
            
            'capacidad_maxima' => 'required|numeric|min:0.1',
            'unidad_medida' => 'required|string', 
        ], [
            'placa.unique' => 'Esta placa ya está registrada.',
            'tipo_vehiculo.required' => 'Debes seleccionar el tipo de vehículo.',
        ]);

        Unit::create($validated);

        return redirect()->back()->with('success', 'Unidad registrada correctamente.');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $clients = Client::where('is_active', true)->get();
        return view('units.edit', compact('unit', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'placa' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('units')->ignore($unit->id)
            ],
            
            'tipo_vehiculo' => 'required|in:Olla,Volteo,Plataforma,Gondola,Caja Seca,Torton,Rabón',
            
            'capacidad_maxima' => 'required|numeric|min:0.1',
            'unidad_medida' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        
        return redirect()->route('units.index')->with('success', 'Unidad eliminada del sistema.');
    }


    public function descargarGafete($uuid)
    {
        $unit = Unit::where('uuid', $uuid)->with('client')->firstOrFail();

        $contenidoQr = route('units.validate_access', $unit->uuid);

        $qrImage = base64_encode(QrCode::format('svg')->size(200)->generate($contenidoQr));

        $pdf = Pdf::loadView('pdf.gafete_acceso', compact('unit', 'qrImage'));

        return $pdf->setPaper('a6', 'landscape')->download("Gafete_{$unit->placa}.pdf");
    }

    public function validateAccess($uuid)
    {
        $unit = Unit::where('uuid', $uuid)->with('client')->firstOrFail();
        
        return response()->json([
            'estado' => $unit->is_active ? 'AUTORIZADO' : 'DENEGADO / BLOQUEADO',
            
            'vehiculo' => $unit->tipo_vehiculo, 
            
            'cliente' => $unit->client->name ?? 'Sin Cliente',
            'placa' => $unit->placa,
            'capacidad' => $unit->capacidad_maxima . ' ' . $unit->unidad_medida,
            'mensaje' => $unit->is_active ? 'La unidad puede ingresar.' : 'Unidad dada de baja o bloqueada.'
        ]);
    }
}