<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface ProductRepositoryInterface
{
    /**
     * Get paginated products with search filters and caching.
     *
     * @param array $filters
     * @return CursorPaginator
     */
    public function searchProducts(array $filters): CursorPaginator;

    /**
     * Create a new product.
     *
     * @param array $data The data for the new product.
     * @return Product The created product instance.
     */
    public function create(array $data): Product;

    /**
     * Retrieve a product by ID.
     *
     * @param int $id The ID of the product.
     * @return Product|null The product instance or null if not found.
     */
    public function show(int $id): ?Product;

    /**
     * Update a product by ID.
     *
     * @param int $id The ID of the product.
     * @param array $data The data to update.
     * @return Product The updated product instance.
     */
    public function update(int $id, array $data): Product;
}
