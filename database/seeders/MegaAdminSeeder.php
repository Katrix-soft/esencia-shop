<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class MegaAdminSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear permisos básicos
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage settings'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        $superAdminRole->syncPermissions(Permission::all()); // Superadmin tiene todo
        $adminRole->syncPermissions(['view users', 'create users', 'edit users']); // Admin tiene acceso limitado

        // Crear un usuario superadmin por defecto
        $superadminUser = User::firstOrCreate([
            'email' => 'superadmin@katrix.com',
        ], [
            'name' => 'Super Administrador',
            'password' => bcrypt('password'), // Cambiar en producción
        ]);

        if (!$superadminUser->hasRole('superadmin')) {
            $superadminUser->assignRole('superadmin');
        }

        // Crear un usuario admin por defecto
        $adminUser = User::firstOrCreate([
            'email' => 'admin@katrix.com',
        ], [
            'name' => 'Administrador',
            'password' => bcrypt('password'),
        ]);

        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
