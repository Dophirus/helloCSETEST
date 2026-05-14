<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductService
{
    public function listProducts(?int $categoryId = null): Collection
    {
        return Product::when($categoryId, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        })->get();
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }
}
