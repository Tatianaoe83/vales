<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Vale;
use App\Models\Material;
use App\Models\Unit;
use Spatie\Permission\Models\Role;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return back();
        }

        // 1. Buscar en Clientes (por nombre, RFC o correo)
        $clients = Client::where('name', 'LIKE', "%{$query}%")
                         ->orWhere('rfc', 'LIKE', "%{$query}%")
                         ->orWhere('email', 'LIKE', "%{$query}%")
                         ->get();

        // 2. Buscar en Vales (por folio_vale o uuid)
        $vales = Vale::where('folio_vale', 'LIKE', "%{$query}%")
                     ->orWhere('uuid', 'LIKE', "%{$query}%")
                     ->get();

        // 3. Buscar en Materiales (por nombre o código)
        $materials = Material::where('name', 'LIKE', "%{$query}%")
                             ->orWhere('code', 'LIKE', "%{$query}%")
                             ->get();

        // 4. Buscar en Unidades (por placa)
        $units = Unit::where('placa', 'LIKE', "%{$query}%")->get();

        // 5. Buscar en Roles
        $roles = Role::where('name', 'LIKE', "%{$query}%")->get();

        return view('search.results', compact('query', 'clients', 'vales', 'materials', 'units', 'roles'));
    }
}