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

        // Primary admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@cms.test'],
            ['name' => 'Alexandra Chen', 'password' => Hash::make('Option101#')]
        );
        $admin->assignRole($adminRole);

        // Additional admins
        $admins = [
            ['name' => 'Marcus Webb',    'email' => 'marcus.webb@cms.test'],
            ['name' => 'Priya Nair',     'email' => 'priya.nair@cms.test'],
        ];
        foreach ($admins as $data) {
            $u = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('Option101#')]
            );
            $u->assignRole($adminRole);
        }

        // Primary moderator
        $moderator = User::firstOrCreate(
            ['email' => 'moderator@cms.test'],
            ['name' => 'Jordan Blake', 'password' => Hash::make('Option101#')]
        );
        $moderator->assignRole($moderatorRole);

        // Additional moderators
        $moderators = [
            ['name' => 'Sofia Reyes',    'email' => 'sofia.reyes@cms.test'],
            ['name' => 'Daniel Okafor',  'email' => 'daniel.okafor@cms.test'],
            ['name' => 'Emma Hartmann',  'email' => 'emma.hartmann@cms.test'],
            ['name' => 'Liam Fitzgerald','email' => 'liam.fitzgerald@cms.test'],
        ];
        foreach ($moderators as $data) {
            $u = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('Option101#')]
            );
            $u->assignRole($moderatorRole);
        }
    }
}
