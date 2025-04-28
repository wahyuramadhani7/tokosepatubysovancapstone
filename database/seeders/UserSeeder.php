<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Karyawan
        User::create([
            'name' => 'Karyawan Test',
            'email' => 'karyawan@test.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Akun Pemilik
        User::create([
            'name' => 'Pemilik Test',
            'email' => 'pemilik@test.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}