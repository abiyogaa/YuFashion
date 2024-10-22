<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pakaian Formal',
                'description' => 'Koleksi pakaian formal untuk acara resmi',
            ],
            [
                'name' => 'Pakaian Adat',
                'description' => 'Koleksi pakaian tradisional dari berbagai daerah',
            ],
            [
                'name' => 'Pakaian Pesta',
                'description' => 'Koleksi pakaian untuk acara pesta dan perayaan',
            ],
            [
                'name' => 'Kostum',
                'description' => 'Koleksi kostum untuk acara khusus dan cosplay',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
