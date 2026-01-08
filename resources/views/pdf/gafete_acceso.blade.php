<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Gafete {{ $unit->placa }}</title>
    <style>
        @page { margin: 0px; }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        .card {
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .header {
            background-color: #111827; 
            color: white;
            padding: 15px 25px;
            border-bottom: 4px solid #3b82f6;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
            float: left;
        }

        .header-subtitle {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            float: right;
            margin-top: 4px;
        }

        .body-content { padding: 25px; }

        .layout-table { width: 100%; border-collapse: collapse; }
        
        .col-info { width: 65%; vertical-align: top; padding-right: 20px; }
        .col-qr { width: 35%; vertical-align: top; text-align: center; border-left: 1px solid #e5e7eb; padding-left: 20px; }

        .label {
            display: block;
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .value-large {
            font-size: 24px; /* Un poco más grande ahora que hay más espacio */
            font-weight: bold;
            color: #111827;
            margin-bottom: 25px; /* Más separación */
            display: block;
        }

        .value-small {
            font-size: 16px;
            color: #374151;
            margin-bottom: 25px;
            display: block;
        }

        .placa-container {
            border: 2px dashed #d1d5db;
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 10px 15px;
            display: inline-block;
            margin-top: 5px;
        }

        .placa-text {
            font-family: 'Courier New', monospace;
            font-size: 26px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 2px;
        }

        .qr-box {
            background-color: white;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .scan-text {
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-badge {
            margin-top: 15px;
            font-size: 10px;
            text-transform: uppercase;
            color: #10b981;
            font-weight: bold;
            text-align: center;
        }

        .decoration-circle {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            z-index: 0;
        }

    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="decoration-circle"></div>
            <span class="header-title">GAFETE</span>
            <span class="header-subtitle">PROSER 2026</span>
            <div style="clear: both;"></div>
        </div>

        <div class="body-content">
            <table class="layout-table">
                <tr>
                    <td class="col-info">
                        
                        <span class="label">Empresa / Cliente</span>
                        <span class="value-large">{{ $unit->client->name ?? 'Sin Cliente' }}</span>

                        <span class="label">Tipo / Capacidad</span>
                        <span class="value-small">
                            {{ $unit->tipo_vehiculo }} - <strong>{{ $unit->capacidad_maxima }} {{ $unit->unidad_medida }}</strong>
                        </span>

                        <span class="label">Placa de Identificación</span>
                        <div class="placa-container">
                            <span class="placa-text">{{ $unit->placa }}</span>
                        </div>
                    </td>

                    <td class="col-qr">
                        <div class="qr-box">
                            <img src="data:image/svg+xml;base64,{{ $qrImage }}" width="130" height="130" />
                        </div>
                        
                        <div class="scan-text">
                            Escanear en Caseta
                        </div>

                        <div class="status-badge">
                            *Estado: Activo
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="height: 6px; width: 100%; background-color: #1f2937; position: absolute; bottom: 0;"></div>
    </div>
</body>
</html>