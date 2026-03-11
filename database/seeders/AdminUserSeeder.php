<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder


{
    public function run(): void
    {
        // Create with fillable fields
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password123'), // explicit hashing
                // do not include 'role' here since it's not fillable
            ]
        );

        // Ensure role is admin (bypass mass-assignment)
        if ($user->role !== 'admin') {
            $user->forceFill(['role' => 'admin'])->save();
        }
    }
}


