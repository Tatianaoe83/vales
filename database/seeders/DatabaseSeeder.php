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
        // 1. Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. CREAR TODOS LOS PERMISOS (Aquí faltaba 'manage clients')
        $permissions = [
            // --- GESTIÓN DEL SISTEMA ---
            'manage users',       
            'manage roles',
            'manage clients', // <--- ¡AQUÍ ESTABA EL FALTANTE! 🔑
            'view reports',       

            // --- OPERATIVOS (Ventas) ---
            'create tickets',     
            'edit tickets',       
            'print tickets',      
            
            // --- OPERATIVOS (Caseta) ---
            'validate exit',      
            'report issue',       
        ];

        // Este bucle crea los permisos en la base de datos
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
            'manage clients', // <--- Asignado al Admin
            'view reports'
        ]);

        // 5. ASIGNAR PERMISOS A VENTAS
        // Ventas también necesita gestionar clientes para poder venderles
        $roleVentas->syncPermissions([
            'manage clients', // <--- Asignado a Ventas
            'create tickets', 
            'print tickets'
        ]);

        // 6. ASIGNAR PERMISOS A CASETA
        $roleCaseta->syncPermissions(['validate exit']);

        // 7. CREAR USUARIOS
        
        // Admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@vales.com'],
            [
                'name' => 'Jefe Admin', 
                'password' => Hash::make('password')
            ]
        );
        $userAdmin->assignRole($roleAdmin);

        // Ventas
        $userVentas = User::firstOrCreate(
            ['email' => 'ventas@vales.com'],
            [
                'name' => 'Vendedor Luis', 
                'password' => Hash::make('password')
            ]
        );
        $userVentas->assignRole($roleVentas);

        // Caseta
        $userCaseta = User::firstOrCreate(
            ['email' => 'caseta@vales.com'],
            [
                'name' => 'Guardia Pedro', 
                'password' => Hash::make('password')
            ]
        );
        $userCaseta->assignRole($roleCaseta);

        $this->command->info('¡Listo! Permiso manage clients creado y asignado.');
    }
}