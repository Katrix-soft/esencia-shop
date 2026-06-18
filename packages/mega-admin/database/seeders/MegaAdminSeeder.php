<?php

namespace KatrixSoft\MegaAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Assuming default User model

class MegaAdminSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear permisos básicos (puedes agregar más)
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

        // Crear un usuario superadmin por defecto (opcional)
        $superadminUser = User::firstOrCreate([
            'email' => 'superadmin@katrix.com',
        ], [
            'name' => 'Super Administrador',
            'password' => bcrypt('password'), // Cambiar en producción
        ]);

        if (!$superadminUser->hasRole('superadmin')) {
            $superadminUser->assignRole('superadmin');
        }
    }
}
