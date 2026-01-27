<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        // Convertir RFC a mayúsculas antes de validar
        if($request->has('rfc')){
            $request->merge(['rfc' => strtoupper($request->rfc)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'rfc' => [
                'required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/',
                Rule::unique('clients')->where(fn ($query) => $query->where('is_active', true))
            ],
            'email' => [
                'required', 'email',
                Rule::unique('clients')->where(fn ($query) => $query->where('is_active', true))
            ],
            'phone' => [
                'required', 'string', 'max:20',
                Rule::unique('clients')->where(fn ($query) => $query->where('is_active', true))
            ],
            'address' => 'required|string',
        ], [
            // Mensajes personalizados
            'name.required' => 'El nombre es obligatorio.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.unique' => 'Este RFC ya está en uso por un cliente activo.',
            'rfc.regex' => 'Formato de RFC inválido.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya está en uso por un cliente activo.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.unique' => 'Este teléfono ya está en uso por un cliente activo.',
            'address.required' => 'La dirección es obligatoria.',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if($request->has('rfc')){
            $request->merge(['rfc' => strtoupper($request->rfc)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'rfc' => [
                'required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/',
                Rule::unique('clients')->ignore($client->id)->where(fn ($query) => $query->where('is_active', true))
            ],
            'email' => [
                'required', 'email',
                Rule::unique('clients')->ignore($client->id)->where(fn ($query) => $query->where('is_active', true))
            ],
            'phone' => [
                'required', 'string', 'max:20',
                Rule::unique('clients')->ignore($client->id)->where(fn ($query) => $query->where('is_active', true))
            ],
            'address' => 'required|string', 
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.unique' => 'Este RFC ya está en uso por otro cliente activo.',
            'rfc.regex' => 'Formato de RFC inválido.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya está en uso por otro cliente activo.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.unique' => 'Este teléfono ya está en uso por otro cliente activo.',
            'address.required' => 'La dirección es obligatoria.',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Client $client)
    {
        $client->update(['is_active' => false]);
        return redirect()->route('clients.index')->with('success', 'Cliente desactivado.');
    }

    public function forceDelete($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente eliminado permanentemente.');
    }
}