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
        if($request->has('rfc')){
            $request->merge(['rfc' => strtoupper($request->rfc)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'rfc' => [
                'nullable', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/',
                Rule::unique('clients')->where(fn ($query) => $query->where('is_active', true))
            ],
            'email' => [
                'nullable', 'email',
                Rule::unique('clients')->where(fn ($query) => $query->where('is_active', true))
            ],
            'phone' => [
                'nullable', 'string', 'max:15',
                Rule::unique('clients')->where(fn ($query) => $query->where('is_active', true))
            ],
        ], [
            'rfc.unique' => 'Este RFC ya está en uso por un cliente activo.',
            'rfc.regex' => 'Formato de RFC inválido.',
            'email.unique' => 'Este correo ya está en uso por un cliente activo.',
            'phone.unique' => 'Este teléfono ya está en uso por un cliente activo.',
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
                'nullable', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/',
                Rule::unique('clients')->ignore($client->id)->where(fn ($query) => $query->where('is_active', true))
            ],
            'email' => [
                'nullable', 'email',
                Rule::unique('clients')->ignore($client->id)->where(fn ($query) => $query->where('is_active', true))
            ],
            'phone' => [
                'nullable', 'string', 'max:15',
                Rule::unique('clients')->ignore($client->id)->where(fn ($query) => $query->where('is_active', true))
            ],
        ], [
            'rfc.unique' => 'Este RFC ya está en uso por otro cliente activo.',
            'rfc.regex' => 'Formato de RFC inválido.',
            'email.unique' => 'Este correo ya está en uso por otro cliente activo.',
            'phone.unique' => 'Este teléfono ya está en uso por otro cliente activo.',
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