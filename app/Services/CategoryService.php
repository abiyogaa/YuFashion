<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = $this->getCategoryById($id);
        return $category->update($data);
    }

    public function deleteCategory($id)
    {
        $category = $this->getCategoryById($id);
        return $category->delete();
    }
}