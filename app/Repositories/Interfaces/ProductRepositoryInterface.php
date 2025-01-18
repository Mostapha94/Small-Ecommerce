<?php

namespace App\Repositories\Interfaces;

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
}
