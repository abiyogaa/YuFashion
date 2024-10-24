<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class ManageCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            Log::info('Retrieved all categories successfully');
            return view('admin.categories.index', compact('categories'));
        } catch (Exception $e) {
            Log::error('Error retrieving categories: ' . $e->getMessage());
            return view('admin.error', ['message' => 'An error occurred while loading categories.']);
        }
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'description' => 'required|string|max:1000',
            ]);

            $result = $this->categoryService->createCategory($validatedData);

            if ($result) {
                Log::info('Category created successfully', ['category' => $result]);
                return redirect()->route('categories.index')->with('success', 'Category created successfully.');
            } else {
                Log::warning('Failed to create category', ['data' => $validatedData]);
                return back()->with('error', 'Failed to create category. Please try again.')->withInput();
            }
        } catch (ValidationException $e) {
            if ($e->validator->errors()->has('name')) {
                return back()->with('error', 'A category with this name already exists.')->withInput();
            }
            return back()->with('error', 'Please check your input and try again.')->withInput();
        } catch (Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the category.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            if (!$category) {
                return redirect()->route('categories.index')->with('error', 'Category not found.');
            }
            return view('admin.categories.edit', compact('category'));
        } catch (Exception $e) {
            Log::error('Error retrieving category for edit: ' . $e->getMessage(), ['category_id' => $id]);
            return redirect()->route('categories.index')->with('error', 'An error occurred while retrieving the category.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'required|string|max:1000',
            ]);

            $result = $this->categoryService->updateCategory($id, $validatedData);

            if ($result) {
                Log::info('Category updated successfully', ['category_id' => $id]);
                return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
            } else {
                Log::warning('Failed to update category', ['category_id' => $id, 'data' => $validatedData]);
                return back()->with('error', 'Failed to update category. Please try again.')->withInput();
            }
        } catch (Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage(), ['category_id' => $id]);
            return back()->with('error', 'An error occurred while updating the category.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->categoryService->deleteCategory($id);

            if ($result) {
                Log::info('Category deleted successfully', ['category_id' => $id]);
                return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
            } else {
                Log::warning('Failed to delete category', ['category_id' => $id]);
                return back()->with('error', 'Failed to delete category. It may be in use or not found.');
            }
        } catch (Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage(), ['category_id' => $id]);
            return back()->with('error', 'An error occurred while deleting the category.');
        }
    }
}