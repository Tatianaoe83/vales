<?php
// app/Http/Controllers/UnitController.php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'placa' => 'required|unique:units,placa|max:10',
            'tipo_vehiculo' => 'required|in:Olla,Volteo,Plataforma,Gondola',
            'capacidad_maxima' => 'required|numeric|min:1',
            'unidad_medida' => 'required|in:m3,toneladas',
        ]);

        Unit::create($validated);

        return redirect()->back()->with('success', 'Unidad registrada correctamente.');
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
        $unit = Unit::where('uuid', $uuid)->firstOrFail();
        
        return response()->json([
            'estado' => $unit->is_active ? 'AUTORIZADO' : 'DENEGADO / BLOQUEADO',
            'vehiculo' => $unit->tipo_vehiculo,
            'placa' => $unit->placa,
            'capacidad' => $unit->capacidad_maxima . ' ' . $unit->unidad_medida
        ]);
    }
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $clients = \App\Models\Client::all();
        return view('units.edit', compact('unit', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'placa' => 'required|max:10|unique:units,placa,' . $unit->id, 
            'tipo_vehiculo' => 'required|in:Olla,Volteo,Plataforma,Gondola',
            'capacidad_maxima' => 'required|numeric|min:1',
            'unidad_medida' => 'required|in:m3,toneladas',
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
    public function index()
{
    $units = Unit::with('client')->latest()->paginate(10);
    
    $clients = \App\Models\Client::all(); 

    return view('units.index', compact('units', 'clients'));
}
}

