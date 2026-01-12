<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * Constructor de Seguridad.
     * Verifica que el usuario tenga el permiso 'manage materials'
     * antes de permitirle usar cualquier función de este controlador.
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

        $material = Material::create($request->all());

        MaterialPriceHistory::create([
            'material_id' => $material->id,
            'price' => $material->price,
            'changed_at' => now(),
        ]);

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

    
        if ($request->price != $material->price) {
            MaterialPriceHistory::create([
                'material_id' => $material->id,
                'price' => $request->price,
                'changed_at' => now(),
            ]);
        }

        $material->update($request->all());

        return redirect()->route('materials.index')->with('success', 'Material actualizado correctamente.');
    }

    public function destroy(Material $material)
    {
        $material->update(['is_active' => false]);
        return redirect()->route('materials.index')->with('success', 'Material desactivado.');
    }
}