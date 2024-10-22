<?php

namespace App\Services;

use App\Models\ClothingItem;
use App\Models\ClothingImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ClothingItemService
{
    public function getAllClothingItems(): Collection
    {
        try {
            return ClothingItem::with('categories', 'images')->get();
        } catch (Exception $e) {
            Log::error('Error fetching all clothing items: ' . $e->getMessage());
            throw new Exception('Unable to fetch clothing items.');
        }
    }

    public function getClothingItemById(int $id): ClothingItem
    {
        try {
            return ClothingItem::with('categories', 'images')->findOrFail($id);
        } catch (Exception $e) {
            Log::error('Error fetching clothing item: ' . $e->getMessage());
            throw new Exception('Unable to fetch clothing item.');
        }
    }

    public function createClothingItem(array $data): ClothingItem
    {
        DB::beginTransaction();
        try {
            $clothingItem = ClothingItem::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'stock' => $data['stock'],
                'price' => $data['price'],
            ]);
            
            if (isset($data['categories'])) {
                $clothingItem->categories()->attach($data['categories']);
            }

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $image->store('clothing_images', 'public');
                    $clothingItem->images()->create(['image_path' => $path]);
                }
            }

            DB::commit();
            return $clothingItem;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating clothing item: ' . $e->getMessage());
            throw new Exception('Unable to create clothing item.');
        }
    }

    public function updateClothingItem(int $id, array $data): bool
    {
        DB::beginTransaction();
        try {
            $clothingItem = $this->getClothingItemById($id);
            $updated = $clothingItem->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'stock' => $data['stock'],
                'price' => $data['price'],
            ]);

            if (isset($data['categories'])) {
                $clothingItem->categories()->sync($data['categories']);
            }

            if (isset($data['images'])) {
                foreach ($clothingItem->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_path);
                    $oldImage->delete();
                }

                foreach ($data['images'] as $image) {
                    $path = $image->store('clothing_images', 'public');
                    $clothingItem->images()->create(['image_path' => $path]);
                }
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating clothing item: ' . $e->getMessage());
            throw new Exception('Unable to update clothing item.');
        }
    }

    public function deleteClothingItem(int $id): bool
    {
        DB::beginTransaction();
        try {
            $clothingItem = $this->getClothingItemById($id);
            foreach ($clothingItem->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $clothingItem->categories()->detach();
            $result = $clothingItem->delete();
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting clothing item: ' . $e->getMessage());
            throw new Exception('Unable to delete clothing item.');
        }
    }
}
