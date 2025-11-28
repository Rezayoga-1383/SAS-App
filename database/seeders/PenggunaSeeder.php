<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::create([
            'email' => 'admin@gmail.com',
            'nama' => 'Reza',
            'password' => bcrypt('admin1234'),
        ]);
    }
}
