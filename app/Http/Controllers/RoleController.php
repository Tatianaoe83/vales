<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // <--- OJO: Necesario para cargar la lista de checkboxes

class RoleController extends Controller
{
    // 1. VER TABLA 
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    // 2. MOSTRAR FORMULARIO
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // 3. GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array'
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este rol ya existe en el sistema.',
        ]);

        $role = Role::create(['name' => $request->name]);

        if($request->has('permissions')){
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado y permisos asignados exitosamente.');
    }

    // 4. MOSTRAR FORMULARIO DE EDITAR
    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    // 5. ACTUALIZAR ROL
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        if($request->has('permissions')){
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]); 
        }

        return redirect()->route('roles.index')->with('success', 'Rol y permisos actualizados correctamente.');
    }

    // 6. ELIMINAR ROL
    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado del sistema.');
    }
}