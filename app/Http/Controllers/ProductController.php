<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get products with search functionality for name and price range, with caching.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Collect the search filters from the query string
        $filters = $request->only(['name', 'min_price', 'max_price']);

        // Get the filtered, paginated results
        $products = $this->productRepository->searchProducts($filters);

        // Return the products in JSON format
        return $this->success('Products', new ProductCollection($products));
    }
}
