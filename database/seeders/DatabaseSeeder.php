<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiar caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. CREAR TODOS LOS PERMISOS
        $permissions = [
            // --- GESTIÓN DEL SISTEMA ---
            'manage users',       
            'manage roles',       
            'view reports',       

            // --- OPERATIVOS (Ventas) ---
            'create tickets',     
            'edit tickets',       
            'print tickets',      
            
            // --- OPERATIVOS (Caseta) ---
            'validate exit',      
            'report issue',       
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. CREAR ROLES
        $roleAdmin   = Role::firstOrCreate(['name' => 'administrador']);
        $roleVentas  = Role::firstOrCreate(['name' => 'ventas']);
        $roleCaseta  = Role::firstOrCreate(['name' => 'caseta']);

        // 4. ASIGNAR PERMISOS AL ADMIN
        $roleAdmin->syncPermissions([
            'manage users',
            'manage roles', 
            'view reports'
        ]);

        $roleVentas->syncPermissions(['create tickets', 'print tickets']);
        $roleCaseta->syncPermissions(['validate exit']);

        // 5. CREAR USUARIO ADMIN
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@vales.com'],
            [
                'name' => 'Jefe Admin', 
                'password' => Hash::make('password')
            ]
        );
        $userAdmin->assignRole($roleAdmin);

        // 6. CREAR USUARIO VENTAS
        $userVentas = User::firstOrCreate(
            ['email' => 'ventas@vales.com'],
            [
                'name' => 'Vendedor Luis', 
                'password' => Hash::make('password')
            ]
        );
        $userVentas->assignRole($roleVentas);

        // 7. CREAR USUARIO CASETA
        $userCaseta = User::firstOrCreate(
            ['email' => 'caseta@vales.com'],
            [
                'name' => 'Guardia Pedro', 
                'password' => Hash::make('password')
            ]
        );
        $userCaseta->assignRole($roleCaseta);

        $this->command->info('¡Listo! Usuarios creados: Admin, Ventas y Caseta.');
    }
}