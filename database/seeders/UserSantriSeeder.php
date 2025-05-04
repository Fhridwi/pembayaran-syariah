<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSantriSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $userId = (string) Str::uuid();
            $namaLengkap = fake('id_ID')->name();
            $username = 'user' . $i;
            $email = 'user' . $i . '@example.com';

            // Insert ke tabel users
            DB::table('users')->insert([
                'id' => $userId,
                'nama_lengkap' => $namaLengkap,
                'username' => $username,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'no_hp' => '08' . rand(100000000, 999999999),
                'alamat' => fake('id_ID')->address(),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert ke tabel santris
            DB::table('santris')->insert([
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'nis' => rand(100000, 999999),
                'nama' => $namaLengkap,
                'jenis_kelamin' => fake()->randomElement(['L', 'P']),
                'tempat_lahir' => fake('id_ID')->city(),
                'tanggal_lahir' => fake()->date('Y-m-d', '-13 years'),
                'alamat' => fake('id_ID')->address(),
                'program' => fake()->randomElement(['Itensif', 'Reguler']),
                'angkatan' => rand(2020, 2025),
                'sekolah' => fake()->company(),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
