<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'phone' => '912 123 1234',
            'address' => 'Philippines',
            'password' => '12345678',
        ]);

        User::factory()->create([
            'name' => 'Tesr User',
            'email' => 'test@example.com',
            'role' => 'user',
            'phone' => '912 123 1234',
            'address' => 'Philippines',
            'password' => '12345678',
        ]);
    }
}
