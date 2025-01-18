<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get paginated products with search filters and caching.
     *
     * @param array $filters
     * @return CursorPaginator
     */
    public function searchProducts(array $filters): CursorPaginator
    {
        // Generate a cache key based on the filters
        $cacheKey = 'products_' . md5(json_encode($filters));

        // Try to get the result from cache, otherwise fetch and cache it
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters) {
            $query = Product::query();

            // Search by name if the filter is present
            if (!empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            // Search by price range if filters are provided
            if (isset($filters['min_price']) && isset($filters['max_price'])) {
                $query->whereBetween('price', [$filters['min_price'], $filters['max_price']]);
            }

            // Default sorting by ID (can be modified as needed)
            return $query->orderBy('id')->cursorPaginate(10);
        });
    }

    /**
     * Create a new product in the database.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

      /**
     * Retrieve a product by its ID.
     *
     * @param int $id The ID of the product.
     * @return Product|null The product instance or null if not found.
     */
    public function show(int $id): ?Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Update an existing product by its ID.
     *
     * @param int $id The ID of the product.
     * @param array $data The updated data.
     * @return Product The updated product instance.
     */
    public function update(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);

        return $product;
    }
}
