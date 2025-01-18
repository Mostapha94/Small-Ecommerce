<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating an order through the API.
     *
     * @return void
     */
    public function test_create_order_api(): void
    {
        // Arrange: Create the necessary data for the order
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $data = [
            'user_id' => $user->id,
            'products' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ];

        // Act: Send a POST request to create the order
        $response = $this->postJson('/api/orders', $data);

        // Assert: Check if the response is successful and has the correct structure
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'products',
                    'created_at',
                    'updated_at',
                ],
            ]);

        // Assert: Check if the order and product were saved correctly
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_product', ['product_id' => $product->id, 'quantity' => 2]);
    }

    /**
     * Test retrieving an order by ID through the API.
     *
     * @return void
     */
    public function test_show_order_api(): void
    {
        // Arrange: Create an order with associated products
        $order = Order::factory()->hasProducts(3)->create();

        // Act: Send a GET request to retrieve the order
        $response = $this->getJson("/api/orders/{$order->id}");

        // Assert: Check if the response is successful and has the correct structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'products',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test creating an order with insufficient stock through the API.
     *
     * @return void
     */
    public function test_create_order_with_insufficient_stock_api(): void
    {
        // Arrange: Create the necessary data for the order
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);

        $data = [
            'user_id' => $user->id,
            'products' => [
                ['product_id' => $product->id, 'quantity' => 2], // Quantity exceeds stock
            ],
        ];

        // Act: Send a POST request to create the order and expect failure
        $response = $this->postJson('/api/orders', $data);

        // Assert: Check if the response is a failure and returns the appropriate error message
        $response->assertStatus(400)
            ->assertJson(['error' => "Product {$product->name} does not have enough stock."]);
    }
}
