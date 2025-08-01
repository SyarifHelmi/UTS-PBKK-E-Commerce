<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@ifump.net',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'Helmi',
            'username' => 'helmi',
            'email' => 'helmi@ifump.net',
            'password' => Hash::make('password')
        ]);
    }
}
