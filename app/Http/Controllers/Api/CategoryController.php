<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getCategoriesWithOnlineProductsCount();
        return CategoryResource::collection($categories);
    }

    public function __construct(protected CategoryService $categoryService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());
        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, Category $category): CategoryResource
    {
        $updated = $this->categoryService->updateCategory($category, $request->validated());
        return new CategoryResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);
        return response()->json(true, 204);
    }
}
