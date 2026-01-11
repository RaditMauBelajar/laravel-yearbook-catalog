<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder yang mau dijalankan
        $this->call([
            UserSeeder::class,      // Isi user admin dulu
            BookSeeder::class,      // Baru isi buku
            BookPageSeeder::class,  // Terakhir isi halaman buku
        ]);
    }
}