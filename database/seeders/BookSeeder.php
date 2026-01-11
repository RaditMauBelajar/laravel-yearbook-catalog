<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book; // Import model Book

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Cara 1: Insert 1 data
        Book::create([
            'title' => 'Buku Tahunan 2024',
            'year' => 2024,
            'school_name' => 'SMA Negeri 1',
            'description' => 'Kenangan indah angkatan 2024',
            'cover_image' => 'covers/2024.jpg',
            'access_username' => 'yearbook2024',
            'access_password' => bcrypt('password'), // Hash otomatis
            'status' => 'show'
        ]);

        // Cara 2: Insert banyak data sekaligus (lebih cepat)
        Book::insert([
            [
                'title' => 'Buku Tahunan 2023',
                'year' => 2023,
                'school_name' => 'SMA Negeri 1',
                'cover_image' => 'covers/2023.jpg',
                'access_username' => 'yearbook2023',
                'access_password' => bcrypt('password'),
                'status' => 'show',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Buku Tahunan 2022',
                'year' => 2022,
                'school_name' => 'SMA Negeri 1',
                'cover_image' => 'covers/2022.jpg',
                'access_username' => 'yearbook2022',
                'access_password' => bcrypt('password'),
                'status' => 'show',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Cara 3: Pakai loop (untuk banyak data)
        for ($i = 2015; $i <= 2021; $i++) {
            Book::create([
                'title' => "Buku Tahunan $i",
                'year' => $i,
                'school_name' => 'SMA Negeri 1',
                'cover_image' => "covers/$i.jpg",
                'access_username' => "yearbook$i",
                'access_password' => bcrypt('password'),
                'status' => 'show'
            ]);
        }
    }
}