<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialPriceHistory;
use App\Models\StockMovement; // <--- NUEVO: Para registrar movimientos
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // <--- NUEVO: Para saber quién hizo el cambio

class MaterialController extends Controller
{
    /**
     * Constructor de Seguridad.
     */
    public function __construct()
    {
        $this->middleware('can:manage materials');
    }

    public function index()
    {
        $materials = Material::paginate(10);
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:materials,code',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer|min:0',
        ]);

        // 1. Crear el Material
        $material = Material::create($request->all());

        // 2. Registrar Precio Inicial
        MaterialPriceHistory::create([
            'material_id' => $material->id,
            'price' => $material->price,
            'changed_at' => now(),
        ]);

        // 3. Registrar Stock Inicial (Si es mayor a 0)
        if ($material->stock > 0) {
            StockMovement::create([
                'material_id' => $material->id,
                'user_id' => Auth::id(), // Usuario actual
                'type' => 'Entrada',
                'quantity' => $material->stock,
                'reason' => 'Inventario Inicial',
            ]);
        }

        return redirect()->route('materials.index')->with('success', 'Material registrado correctamente.');
    }

    public function edit(Material $material)
    {
        $history = $material->priceHistory;
        return view('materials.edit', compact('material', 'history'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['nullable', 'string', Rule::unique('materials')->ignore($material->id)],
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer|min:0',
        ]);

        // A. Historial de Precios
        if ($request->price != $material->price) {
            MaterialPriceHistory::create([
                'material_id' => $material->id,
                'price' => $request->price,
                'changed_at' => now(),
            ]);
        }

        // B. Historial de Stock (Cálculo automático)
        $nuevoStock = (int) $request->stock;
        $stockAnterior = $material->stock;
        $diferencia = $nuevoStock - $stockAnterior;

        if ($diferencia != 0) {
            StockMovement::create([
                'material_id' => $material->id,
                'user_id' => Auth::id(),
                'type' => $diferencia > 0 ? 'Entrada' : 'Salida', // Detecta automáticamente
                'quantity' => abs($diferencia), // Siempre guarda positivo
                'reason' => 'Ajuste Manual desde Catálogo',
            ]);
        }

        // Actualizar datos
        $material->update($request->all());

        return redirect()->route('materials.index')->with('success', 'Material actualizado correctamente.');
    }

    public function destroy(Material $material)
    {
        $material->update(['is_active' => false]);
        return redirect()->route('materials.index')->with('success', 'Material desactivado.');
    }

    public function history(Material $material)
    {
        // 1. Obtener Movimientos de Stock
        $stock = $material->stockMovements()
            ->with('user')
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($mov) {
                return [
                    'id' => $mov->id,
                    'date' => $mov->created_at->format('d/m/Y H:i'),
                    'type' => $mov->type,
                    'quantity' => $mov->quantity,
                    'reason' => $mov->reason ?? 'Sin motivo',
                    'user' => $mov->user->name ?? 'Sistema'
                ];
            });

        // 2. Obtener Historial de Precios
        $prices = $material->priceHistory()
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($price) {
                return [
                    'id' => $price->id,
                    'date' => $price->changed_at->format('d/m/Y H:i'),
                    'price' => number_format($price->price, 2),
                ];
            });

        // 3. Devolver ambos
        return response()->json([
            'stock' => $stock,
            'prices' => $prices
        ]);
    }
}