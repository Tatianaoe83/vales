<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Remisión {{ $sale->folio }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { width: 100%; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .client-info { margin-bottom: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 5px; text-align: left; vertical-align: middle; }
        .table th { background-color: #333; color: white; text-align: center; }
        .total-box { text-align: right; margin-top: 20px; font-size: 12px; }
        .total-row { font-size: 14px; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
        .qr-cell { text-align: center; width: 60px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td style="border:none;">
                <h1 style="margin:0;">MATERIALES AGREGADOS</h1>
                <p>Nota de Venta / Remisión</p>
            </td>
            <td style="border:none; text-align:right;">
                <h3>Folio: {{ $sale->folio }}</h3>
                <p>Fecha: {{ $sale->created_at->format('d/m/Y') }}</p>
                <p>Vencimiento: {{ \Carbon\Carbon::parse($sale->fecha_vencimiento)->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <div class="client-info">
        <strong>Cliente:</strong> {{ $sale->client->name }} <br>
        <strong>RFC:</strong> {{ $sale->client->rfc }} <br>
        <strong>Dirección:</strong> {{ $sale->client->address ?? 'N/A' }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Folio Vale</th>
                <th>Descripción</th>
                <th>Unidad</th>
                <th>Cant.</th>
                <th>P. Unitario</th>
                <th>Importe</th>
                <th>QR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->vales as $vale)
            <tr>
                <td>{{ $vale->folio_vale }}</td>
                <td>{{ $vale->material->name }}</td>
                <td>{{ $vale->material->unit }}</td>
                <td style="text-align: center;">{{ $vale->cantidad }}</td>
                <td>${{ number_format($vale->material->price, 2) }}</td>
                <td>${{ number_format($vale->material->price * $vale->cantidad, 2) }}</td>
                
                <td class="qr-cell">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(50)->generate($vale->uuid)) }}">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <p>Subtotal: ${{ number_format($sale->subtotal, 2) }}</p>
        <p>IVA (16%): ${{ number_format($sale->iva, 2) }}</p>
        <p class="total-row">TOTAL: ${{ number_format($sale->total, 2) }}</p>
    </div>

    <div style="margin-top: 50px;">
        <p>Observaciones: {{ $sale->notas }}</p>
    </div>

    <div class="footer">
        Este documento es una representación impresa de una venta. 
        Pagos a cuenta de cheques S.A. de C.V.
    </div>

</body>
</html>