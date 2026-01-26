<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Vales</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REPORTE OPERATIVO DE VALES</h2>
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha Ent.</th>
                <th>Fecha Sal.</th>
                <th>Cliente</th>
                <th>Material</th>
                <th>Unidad</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vales as $vale)
            <tr>
                <td>{{ $vale->folio_vale }}</td>
                <td>{{ $vale->fecha_entrada ? $vale->fecha_entrada->format('d/m H:i') : '--' }}</td>
                <td>{{ $vale->fecha_salida ? $vale->fecha_salida->format('d/m H:i') : '--' }}</td>
                <td>{{ $vale->sale->client->name }}</td>
                <td>{{ $vale->material->name }}</td>
                <td>{{ $vale->unit ? $vale->unit->placa : 'EXT' }}</td>
                <td>{{ $vale->estatus }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistema de Logística - Página generada automáticamente
    </div>
</body>
</html>