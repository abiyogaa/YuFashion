<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class CategoryService
{
    public function getAllCategories(): Collection
    {
        try {
            return Category::all();
        } catch (Exception $e) {
            Log::error('Error fetching all categories: ' . $e->getMessage());
            throw new Exception('Tidak dapat mengambil data kategori. Silakan coba lagi nanti.');
        }
    }

    public function getCategoryById(int $id): Category
    {
        try {
            return Category::findOrFail($id);
        } catch (Exception $e) {
            Log::error('Category not found: ' . $e->getMessage());
            throw new Exception('Kategori tidak ditemukan.');
        }
    }

    public function createCategory(array $data): Category
    {
        try {
            return Category::create($data);
        } catch (Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            throw new Exception('Tidak dapat membuat kategori baru. Silakan coba lagi nanti.');
        }
    }

    public function updateCategory(int $id, array $data): bool
    {
        try {
            $category = $this->getCategoryById($id);
            if (!$category->update($data)) {
                throw new Exception('Gagal memperbarui data kategori.');
            }
            return true;
        } catch (Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            throw new Exception('Tidak dapat memperbarui data kategori. Silakan coba lagi nanti.');
        }
    }

    public function deleteCategory(int $id): bool
    {
        try {
            $category = $this->getCategoryById($id);
            if (!$category->delete()) {
                throw new Exception('Gagal menghapus kategori.');
            }
            return true;
        } catch (Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            throw new Exception('Tidak dapat menghapus kategori. Silakan coba lagi nanti.');
        }
    }
}