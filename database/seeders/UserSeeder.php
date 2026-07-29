<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole     = Role::findByName('admin', 'sanctum');
        $moderatorRole = Role::findByName('moderator', 'sanctum');

        $admin = User::firstOrCreate(
            ['email' => 'admin@cms.test'],
            ['name' => 'Admin User', 'password' => Hash::make('Option101#')]
        );
        $admin->assignRole($adminRole);

        $moderator = User::firstOrCreate(
            ['email' => 'moderator@cms.test'],
            ['name' => 'Moderator User', 'password' => Hash::make('Option101#')]
        );
        $moderator->assignRole($moderatorRole);
    }
}
