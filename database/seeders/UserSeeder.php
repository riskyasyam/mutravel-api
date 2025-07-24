<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus user lama jika ada untuk menghindari duplikat
        User::truncate();

        // Buat user admin baru
        User::create([
            'name' => 'Admin MU Travel',
            'email' => 'admin@mutravel.com',
            'password' => Hash::make('password123'), // Enkripsi password
        ]);

        // Anda bisa menambahkan user lain di sini jika perlu
    }
}