<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class ManageCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $result = $this->categoryService->createCategory($validatedData);

        if ($result) {
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } else {
            return back()->with('error', 'Failed to create category.')->withInput();
        }
    }

    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $result = $this->categoryService->updateCategory($id, $validatedData);

        if ($result) {
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } else {
            return back()->with('error', 'Failed to update category.')->withInput();
        }
    }

    public function destroy($id)
    {
        $result = $this->categoryService->deleteCategory($id);

        if ($result) {
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } else {
            return back()->with('error', 'Failed to delete category.');
        }
    }
}