<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ClothingItemService;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Exception;

class ManageClothingItemController extends Controller
{
    protected $clothingItemService;
    protected $categoryService;

    public function __construct(ClothingItemService $clothingItemService, CategoryService $categoryService)
    {
        $this->clothingItemService = $clothingItemService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $clothingItems = $this->clothingItemService->getAllClothingItems();
            return view('admin.clothing_items.index', compact('clothingItems'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return view('admin.clothing_items.create', compact('categories'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $this->clothingItemService->createClothingItem($validatedData);
            return redirect()->route('clothing_items.index')->with('success', 'Clothing item created successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $clothingItem = $this->clothingItemService->getClothingItemById($id);
            $categories = $this->categoryService->getAllCategories();
            return view('admin.clothing_items.edit', compact('clothingItem', 'categories'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $this->clothingItemService->updateClothingItem($id, $validatedData);
            return redirect()->route('clothing_items.index')->with('success', 'Clothing item updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->clothingItemService->deleteClothingItem($id);
            return redirect()->route('clothing_items.index')->with('success', 'Clothing item deleted successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}