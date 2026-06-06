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
        // Hanya membuat data akun Admin awal Clean Time
        User::create([
            'name' => 'Admin Clean Time',
            'email' => 'admin@cleantime.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}