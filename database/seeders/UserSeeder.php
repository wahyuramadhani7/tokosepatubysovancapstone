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

        // Akun Pemilik
        User::create([
            'name' => 'Mas Sovan',
            'email' => 'ownertoko@gmail.com',
            'password' => Hash::make('superadmintoko'),
            'role' => 'owner',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}