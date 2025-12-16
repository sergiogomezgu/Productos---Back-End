<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ADMIN
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
]);

// HOTEL 1
User::create([
    'name' => 'Hotel Demo',
    'email' => 'hotel@example.com',
    'password' => Hash::make('hotel123'),
    'role' => 'hotel',
    'hotel_id' => 1,
]);

// HOTEL 2 (si quieres un usuario normal, lo tratamos como hotel sin hotel_id)
User::create([
    'name' => 'Usuario',
    'email' => 'user@example.com',
    'password' => Hash::make('user123'),
    'role' => 'hotel',
    'hotel_id' => null,
]);
    }
}
