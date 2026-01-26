<?php

namespace App\Exports;

use App\Models\Vale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ValesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * Trae todos los vales con sus relaciones
    */
    public function collection()
    {
        return Vale::with(['sale.client', 'material', 'unit'])->orderBy('id', 'desc')->get();
    }

    /**
    * Define los títulos de las columnas en el Excel
    */
    public function headings(): array
    {
        return [
            'ID',
            'Folio Vale',
            'Folio Venta',
            'Fecha Creación',
            'Cliente',
            'Material',
            'Cantidad',
            'Unidad',
            'Placas',
            'Estatus',
            'Hora Entrada',    // NUEVO
            'Hora Salida',     // NUEVO
            'Tiempo Estancia', // NUEVO (Calculado)
            'UUID (QR)',
        ];
    }

    /**
    * Mapea cada fila: Aquí decides qué dato va en qué columna
    */
    public function map($vale): array
    {
        $tiempoEstancia = '---';
        
        if ($vale->fecha_entrada && $vale->fecha_salida) {
            $tiempoEstancia = $vale->fecha_entrada->diff($vale->fecha_salida)->format('%H hrs %I min');
        } elseif ($vale->fecha_entrada && !$vale->fecha_salida) {
            $tiempoEstancia = 'En proceso...';
        }

        return [
            $vale->id,
            $vale->folio_vale,
            $vale->sale->folio, 
            $vale->created_at->format('d/m/Y H:i'),
            $vale->sale->client->name,
            $vale->material->name,
            $vale->cantidad . ' ' . $vale->material->unit,
            $vale->unit ? $vale->unit->tipo_vehiculo : 'Externa',
            $vale->unit ? $vale->unit->placa : 'N/A',
            $vale->estatus,
            
            // COLUMNAS NUEVAS DE TIEMPO
            $vale->fecha_entrada ? $vale->fecha_entrada->format('d/m/Y H:i') : '--',
            $vale->fecha_salida ? $vale->fecha_salida->format('d/m/Y H:i') : '--',
            $tiempoEstancia,

            $vale->uuid,
        ];
    }
}