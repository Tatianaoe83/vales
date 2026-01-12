<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Limpiar caché de permisos (Importante para evitar errores)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear Permisos (Lista completa de tu sistema)
        $permisos = [
            // Dashboard
            'ver dashboard',

            // Materiales
            'ver materiales',
            'crear materiales',
            'editar materiales',
            'eliminar materiales',

            // Clientes
            'ver clientes',
            'crear clientes',
            'editar clientes',
            'eliminar clientes',

            // Ventas y Vales
            'ver ventas',
            'crear ventas',     // Acceso al Wizard
            'cancelar ventas',  // Opcional
            'ver vales',
            'validar vales',    // Para el operador de planta
            
            // Unidades/Camiones
            'ver unidades',
            'crear unidades',
            'editar unidades',
            'eliminar unidades',
            
            // Usuarios (Solo Admin)
            'gestionar usuarios',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // 3. Crear Roles y Asignar Permisos

        // ROL: ADMIN (Tiene todo)
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // ROL: VENTAS (Puede vender y ver catálogos, pero no borrar ni gestionar usuarios)
        $roleVentas = Role::create(['name' => 'Vendedor']);
        $roleVentas->givePermissionTo([
            'ver dashboard',
            'ver materiales',
            'ver clientes', 'crear clientes', 'editar clientes',
            'ver ventas', 'crear ventas',
            'ver vales',
            'ver unidades', 'crear unidades'
        ]);

        // ROL: OPERADOR (Solo valida vales en puerta)
        $roleOperador = Role::create(['name' => 'Operador']);
        $roleOperador->givePermissionTo([
            'ver vales',
            'validar vales'
        ]);
    }
}