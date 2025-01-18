<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Create a new order.
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
            ]);

            foreach ($data['products'] as $productData) {
                $product = Product::findOrFail($productData['product_id']);

                if ($product->stock < $productData['quantity']) {
                    throw new \Exception("Product {$product->name} does not have enough stock.");
                }

                $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);
                $product->decrement('stock', $productData['quantity']);
            }

            return $order;
        });
    }

    /**
     * Retrieve order details by ID.
     *
     * @param int $id
     * @return Order
     */
    public function show(int $id): Order
    {
        return Order::with('products')->findOrFail($id);
    }
}
