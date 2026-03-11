<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class SubAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $user = User::firstOrCreate(
        ['email' => 'sub@example.com'],
        [
            'name' => 'subadmin',
            'password' => Hash::make('password123'),
        ]

       );
    }
}
