<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FilamentAdminSeeder extends Seeder
{
    /**
     * Seed a Filament admin user. Credentials can be overridden via env:
     * FILAMENT_ADMIN_NAME, FILAMENT_ADMIN_EMAIL, FILAMENT_ADMIN_PASSWORD
     */
    public function run(): void
    {
        $name = env('FILAMENT_ADMIN_NAME', 'Allan');
        $email = env('FILAMENT_ADMIN_EMAIL', 'jaeron.rivera@gmail.com');
        $password = env('FILAMENT_ADMIN_PASSWORD', '123456');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        // Optionally assign an 'admin' role if spatie/permission is fully set up.
        // This is wrapped in a try/catch so seeding won't fail if tables/traits aren't ready yet.
        try {
            if (class_exists(\Spatie\Permission\Models\Role::class) && method_exists($user, 'assignRole')) {
                $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
                $user->assignRole($role);
            }
        } catch (\Throwable $e) {
            // Silently ignore if permission tables are not migrated yet.
        }
    }
}
