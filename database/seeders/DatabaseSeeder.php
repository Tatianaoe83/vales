<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Limpiar caché de permisos (Crucial para evitar errores al modificar permisos)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. CREAR TODOS LOS PERMISOS
        // Definimos la lista maestra de permisos del sistema
        $permissions = [
            // --- GESTIÓN DEL SISTEMA (Admin) ---
            'manage users',       // Crear/Editar usuarios
            'manage roles',       // Gestionar roles y permisos
            'view reports',       // Ver reportes globales
            
            // --- GESTIÓN DE NEGOCIO (Admin + Ventas) ---
            'manage clients',     // <--- EL PERMISO QUE FALTABA (Crear/Ver Clientes)
            'manage materials',   // Crear/Editar materiales
            'manage units',       // Crear/Editar camiones

            // --- OPERATIVOS (Ventas) ---
            'create tickets',     // Crear nuevas ventas/vales
            'edit tickets',       // Editar ventas existentes (si aplica)
            'print tickets',      // Imprimir o enviar PDF
            'view tickets',       // Ver historial de ventas
            
            // --- OPERATIVOS (Caseta/Vigilancia) ---
            'validate exit',      // Escanear QR y dar salida
            'report issue',       // Reportar incidencias en puerta
        ];

        // Creamos los permisos en la base de datos si no existen
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. CREAR ROLES
        $roleAdmin  = Role::firstOrCreate(['name' => 'administrador']);
        $roleVentas = Role::firstOrCreate(['name' => 'ventas']);
        $roleCaseta = Role::firstOrCreate(['name' => 'caseta']);

        // 4. ASIGNAR PERMISOS A LOS ROLES

        // A) ADMINISTRADOR: Tiene acceso a todo
        $roleAdmin->syncPermissions(Permission::all());

        // B) VENTAS: Puede gestionar clientes, materiales y hacer ventas
        $roleVentas->syncPermissions([
            'manage clients',    // Necesario para seleccionar cliente en el wizard
            'manage materials',  // Para ver precios y stock
            'manage units',      // Para logística
            'create tickets',
            'print tickets',
            'view tickets',
            'view reports'       // Opcional: si quieres que vean sus propios reportes
        ]);

        // C) CASETA: Solo valida salidas
        $roleCaseta->syncPermissions([
            'validate exit',
            'report issue',
            'view tickets'       // Para ver detalles básicos al escanear
        ]);

        // 5. CREAR USUARIOS POR DEFECTO

        // Usuario Admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@vales.com'],
            [
                'name' => 'Jefe Admin', 
                'password' => Hash::make('password') // Contraseña: password
            ]
        );
        $userAdmin->assignRole($roleAdmin);

        // Usuario Ventas
        $userVentas = User::firstOrCreate(
            ['email' => 'ventas@vales.com'],
            [
                'name' => 'Vendedor Luis', 
                'password' => Hash::make('password')
            ]
        );
        $userVentas->assignRole($roleVentas);

        // Usuario Caseta
        $userCaseta = User::firstOrCreate(
            ['email' => 'caseta@vales.com'],
            [
                'name' => 'Guardia Pedro', 
                'password' => Hash::make('password')
            ]
        );
        $userCaseta->assignRole($roleCaseta);

        // Mensaje de éxito en consola
        $this->command->info('Seeders ejecutados correctamente.');
        $this->command->info('Usuario Admin: admin@vales.com / password');
        $this->command->info('Usuario Ventas: ventas@vales.com / password');
    }
}