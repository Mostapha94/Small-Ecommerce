<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

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

    /**
     * Store a new product in the database.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->create($request->validated());
        return $this->success('Product created successfully', $product, 201);
    }

    /**
     * Display the specified product.
     *
     * @param int $id The ID of the product.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->show($id);
        return $this->success('Product retrieved successfully', $product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param \App\Http\Requests\UpdateProductRequest $request
     * @param int $id The ID of the product.
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productRepository->update($id, $request->validated());
        return $this->success('Product updated successfully', $product);
    }
}
