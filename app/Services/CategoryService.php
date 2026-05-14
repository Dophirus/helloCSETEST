<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    public function getAllCategories(): Collection
    {
        return Category::all();
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }

    public function getCategoriesWithOnlineProductsCount(): Collection
    {
        return \App\Models\Category::withCount(['products as products_count' => function ($query) {
            $query->where('status', \App\Enums\ProductStatus::ONLINE);
        }])->get();
    }
}
