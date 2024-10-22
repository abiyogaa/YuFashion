<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClothingItem;
use App\Models\ClothingImage;

class ClothingItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clothingItems = [
            [
                'name' => 'Jas Hitam Premium',
                'description' => 'Jas formal hitam dengan bahan wool berkualitas tinggi',
                'stock' => 5,
                'price' => 200000,
                'categories' => [1], 
                'images' => [
                    'images/jas-hitam-1.jpg',
                    'images/jas-hitam-2.jpg',
                ]
            ],
            [
                'name' => 'Kebaya Modern Hijau',
                'description' => 'Kebaya modern dengan detail bordir dan payet',
                'stock' => 3,
                'price' => 150000,
                'categories' => [2], 
                'images' => [
                    'images/kebaya-hijau-1.jpg',
                    'images/kebaya-hijau-2.jpg',
                ]
            ],
            [
                'name' => 'Gaun Pesta Merah',
                'description' => 'Gaun pesta panjang dengan detail sequin',
                'stock' => 4,
                'price' => 300000,
                'categories' => [1, 3], 
                'images' => [
                    'images/gaun-merah-1.jpg',
                    'images/gaun-merah-2.jpg',
                ]
            ],
            [
                'name' => 'Kostum Superhero',
                'description' => 'Kostum superhero lengkap dengan aksesoris',
                'stock' => 2,
                'price' => 175000,
                'categories' => [4], 
                'images' => [
                    'images/kostum-superhero-1.jpg',
                    'images/kostum-superhero-2.jpg',
                ]
            ],
        ];

        foreach ($clothingItems as $item) {
            $images = $item['images'];
            $categories = $item['categories'];
            
            unset($item['images']);
            unset($item['categories']);
            
            $clothingItem = ClothingItem::create($item);

            // Hubungkan dengan kategori
            $clothingItem->categories()->attach($categories);

            // Tambahkan gambar-gambar
            foreach ($images as $imagePath) {
                ClothingImage::create([
                    'clothing_item_id' => $clothingItem->id,
                    'image_path' => $imagePath,
                ]);
            }
        }
    }
}
