<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Vale;
use App\Models\Client;
use App\Models\Unit;
use App\Models\Material;
use App\Models\ValeHistory;
use App\Models\StockMovement; // <--- NUEVO: Para registrar movimientos de inventario
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <--- NUEVO: Para obtener el usuario actual
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
        $clients = Client::where('is_active', true)->get();
        $materials = Material::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();

        return view('sales.create', compact('clients', 'materials', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'material_id' => 'required|exists:materials,id',
            'cantidad_total' => 'required|numeric|min:0.1',
            'precio_unitario' => 'required|numeric|min:0',
            'tipo_venta' => 'required|in:Contado,Credito',
            'trips_configuration' => 'required', 
        ]);

        try {
            $saleId = DB::transaction(function () use ($request) {
                
                // 1. Bloquear y Verificar Material
                $material = Material::lockForUpdate()->find($request->material_id);

                if (!$material) {
                    throw new \Exception("El material seleccionado no existe.");
                }

                if ($material->stock < $request->cantidad_total) {
                    throw new \Exception("Stock insuficiente. Disponible: {$material->stock} {$material->unit}, Solicitado: {$request->cantidad_total}");
                }

                // 2. Calcular Fechas y Totales
                $vencimiento = Carbon::now();
                if ($request->tipo_venta === 'Credito') {
                    $vencimiento = Carbon::now()->addDays(15);
                }

                $subtotal = $request->cantidad_total * $request->precio_unitario;
                $descuentoMonto = $subtotal * ($request->descuento_porcentaje / 100);
                $subtotalFinal = $subtotal - $descuentoMonto;
                $iva = $subtotalFinal * 0.16;
                $total = $subtotalFinal + $iva;

                // 3. Crear la Venta
                $sale = Sale::create([
                    'client_id' => $request->client_id,
                    'user_id' => auth()->id(),
                    'tipo_venta' => $request->tipo_venta,
                    'fecha_vencimiento' => $vencimiento,
                    'subtotal' => $subtotalFinal,
                    'descuento' => $descuentoMonto,
                    'iva' => $iva,
                    'total' => $total,
                    'notas' => $request->notas
                ]);

                // 4. Descontar Stock y Registrar Historial
                $material->decrement('stock', $request->cantidad_total);

                // --- AQUÍ REGISTRAMOS EL MOVIMIENTO EN EL HISTORIAL ---
                StockMovement::create([
                    'material_id' => $material->id,
                    'user_id' => Auth::id(), // Usuario que hizo la venta
                    'type' => 'Salida',      // Tipo de movimiento
                    'quantity' => $request->cantidad_total,
                    'reason' => 'Venta Folio: ' . $sale->folio, // Referencia clara
                ]);
                // -----------------------------------------------------

                // 5. Generar Vales (Logística)
                $trips = json_decode($request->trips_configuration, true);
                
                if (!is_array($trips) || count($trips) === 0) {
                    throw new \Exception("No se recibieron viajes válidos.");
                }

                foreach ($trips as $index => $trip) {
                    $letra = chr(64 + ($index + 1)); 
                    $folioVale = $sale->folio . '-' . $letra;

                    $vale = Vale::create([
                        'sale_id' => $sale->id,
                        'folio_vale' => $folioVale,
                        'material_id' => $request->material_id,
                        'cantidad' => $trip['amount'], 
                        'unit_id' => $trip['unit_id'],
                        'estatus' => 'Vigente'
                    ]);

                    ValeHistory::create([
                        'vale_id' => $vale->id,
                        'user_id' => auth()->id(),
                        'estatus_nuevo' => 'Vigente',
                        'comentarios' => 'Vale emitido con asignación logística.'
                    ]);
                }

                return $sale->id;
            });

            return redirect()->route('sales.show', $saleId)->with('success', 'Venta y Logística registradas con éxito.');

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
        $pdf = Pdf::loadView('sales.pdf', compact('sale'));
        
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