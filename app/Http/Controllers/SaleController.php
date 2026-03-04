<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Vale;
use App\Models\Client;
use App\Models\Unit;
use App\Models\Material;
use App\Models\ValeHistory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaleTicketMail;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['client', 'vales.unit', 'vales.material'])
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('folio', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $sales = $query->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $clients   = Client::where('is_active', true)->get();
        $materials = Material::where('is_active', true)->get();
        $units     = Unit::where('is_active', true)->get();

        return view('sales.create', compact('clients', 'materials', 'units'));
    }

    public function store(Request $request)
    {
        // ── Validación base ────────────────────────────────────────────────
        $request->validate([
            'client_id'            => 'required|exists:clients,id',
            'tipo_venta'           => 'required|in:Contado,Credito',
            'trips_configuration'  => 'required',
            'lines_configuration'  => 'required',
        ]);

        // ── Decodificar líneas y viajes ────────────────────────────────────
        $lines = json_decode($request->lines_configuration, true);
        $trips = json_decode($request->trips_configuration, true);

        if (!is_array($lines) || count($lines) === 0) {
            return back()->withErrors('Debes agregar al menos un material a la venta.');
        }

        if (!is_array($trips) || count($trips) === 0) {
            return back()->withErrors('Debes configurar la logística de entregas.');
        }

        // ── Validar cada línea ─────────────────────────────────────────────
        foreach ($lines as $i => $line) {
            $num = $i + 1;
            if (empty($line['material_id'])) {
                return back()->withErrors("Línea {$num}: falta el material.");
            }
            if (!isset($line['cantidad_total']) || floatval($line['cantidad_total']) <= 0) {
                return back()->withErrors("Línea {$num}: la cantidad debe ser mayor a 0.");
            }
            if (!isset($line['precio_unitario']) || floatval($line['precio_unitario']) < 0) {
                return back()->withErrors("Línea {$num}: precio unitario inválido.");
            }
        }

        try {
            $saleId = DB::transaction(function () use ($request, $lines, $trips) {

                // ── 1. Calcular totales globales ───────────────────────────
                $importeBruto   = 0;
                $montoDescuento = 0;

                foreach ($lines as $line) {
                    $bruto           = floatval($line['cantidad_total']) * floatval($line['precio_unitario']);
                    $importeBruto   += $bruto;
                    $montoDescuento += $bruto * (floatval($line['descuento'] ?? 0) / 100);
                }

                $subtotalFinal = $importeBruto - $montoDescuento;
                $iva           = $subtotalFinal * 0.16;
                $total         = $subtotalFinal + $iva;

                // ── 2. Fechas ──────────────────────────────────────────────
                $vencimiento = $request->tipo_venta === 'Credito'
                    ? Carbon::now()->addDays(15)
                    : Carbon::now();

                // ── 3. Crear la Venta ──────────────────────────────────────
                $sale = Sale::create([
                    'client_id'              => $request->client_id,
                    'user_id'                => Auth::id(),
                    'tipo_venta'             => $request->tipo_venta,
                    'fecha_vencimiento'      => $vencimiento,
                    'subtotal'               => $subtotalFinal,
                    'descuento'              => $montoDescuento,
                    'iva'                    => $iva,
                    'total'                  => $total,
                    'notas'                  => $request->notas,
                    'pendiente_calificacion' => true,  // activa pantalla kiosco
                ]);

                // ── 4. Descontar stock por cada material ───────────────────
                foreach ($lines as $line) {
                    $material = Material::lockForUpdate()->find($line['material_id']);

                    if (!$material) {
                        throw new \Exception("El material ID {$line['material_id']} no existe.");
                    }

                    if ($material->stock < floatval($line['cantidad_total'])) {
                        throw new \Exception(
                            "Stock insuficiente para «{$material->name}». " .
                            "Disponible: {$material->stock} {$material->unit}, " .
                            "Solicitado: {$line['cantidad_total']}."
                        );
                    }

                    $material->decrement('stock', floatval($line['cantidad_total']));

                    StockMovement::create([
                        'material_id' => $material->id,
                        'user_id'     => Auth::id(),
                        'type'        => 'Salida',
                        'quantity'    => floatval($line['cantidad_total']),
                        'reason'      => 'Venta Folio: ' . $sale->folio,
                    ]);
                }

                // ── 5. Generar Vales: 1 vale por viaje × por material ─────────
                // Cada viaje lleva una porción proporcional de cada material.
                // Ejemplo: 2 materiales × 6 viajes = 12 vales.
                $totalCantidad = collect($lines)->sum(fn($l) => floatval($l['cantidad_total']));

                $valeIndex = 0;
                foreach ($trips as $trip) {
                    foreach ($lines as $line) {
                        $valeIndex++;
                        $letra     = chr(64 + $valeIndex <= 26 ? $valeIndex : 26); // A-Z
                        $folioVale = $sale->folio . '-' . str_pad($valeIndex, 2, '0', STR_PAD_LEFT);

                        // Cantidad proporcional de este material en este viaje
                        $proporcion  = $totalCantidad > 0
                            ? floatval($line['cantidad_total']) / $totalCantidad
                            : 1 / count($lines);
                        $cantidadVale = round(floatval($trip['amount']) * $proporcion, 2);

                        $vale = Vale::create([
                            'sale_id'     => $sale->id,
                            'folio_vale'  => $folioVale,
                            'material_id' => $line['material_id'],
                            'cantidad'    => $cantidadVale,
                            'unit_id'     => $trip['unit_id'] ?? null,
                            'estatus'     => 'Vigente',
                        ]);

                        ValeHistory::create([
                            'vale_id'       => $vale->id,
                            'user_id'       => Auth::id(),
                            'estatus_nuevo' => 'Vigente',
                            'comentarios'   => 'Vale emitido con asignación logística.',
                        ]);
                    }
                }

                return $sale->id;
            });

            return redirect()
                ->route('sales.show', $saleId)
                ->with('success', 'Venta y Logística registradas con éxito.');

        } catch (\Exception $e) {
            return back()->withErrors('Error al procesar la venta: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $sale = Sale::with(['vales.material', 'vales.unit', 'client'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function ticket($id)
    {
        $sale = Sale::with(['vales.material', 'client'])->findOrFail($id);
        return view('sales.ticket', compact('sale'));
    }

    public function pdf($id)
    {
        $sale = Sale::with(['vales.material', 'client'])->findOrFail($id);
        $pdf  = Pdf::loadView('sales.pdf', compact('sale'));

        return $pdf->stream("Remision_{$sale->folio}.pdf");
    }

    public function email($id)
    {
        try {
            $sale = Sale::with(['vales.material', 'client'])->findOrFail($id);

            if (!$sale->client->email) {
                return back()->withErrors('El cliente no tiene un correo electrónico registrado.');
            }

            Mail::to($sale->client->email)->send(new SaleTicketMail($sale));

            return back()->with('success', 'Correo enviado correctamente a ' . $sale->client->email);

        } catch (\Exception $e) {
            return back()->withErrors('Error al enviar correo: ' . $e->getMessage());
        }
    }
}