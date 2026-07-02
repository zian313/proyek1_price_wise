<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed categories
        $categories = ['Jaket', 'T-shirt', 'Sepatu'];

        foreach ($categories as $name) {
            Category::firstOrCreate([
                'name' => $name
            ]);
        }

        // Seed admin account
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_admin' => 1,
                'bank_name' => null,
                'no_rekening' => null,
                'atas_nama' => null,
            ]
        );
    }
}