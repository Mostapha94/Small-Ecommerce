<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if an order can be created using the OrderRepository.
     *
     * @return void
     */
    public function test_create_order(): void
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

        // Act: Create the order via the repository
        $orderRepository = app(OrderRepositoryInterface::class); // Resolving the interface
        $order = $orderRepository->create($data);

        // Assert: Check if the order was created successfully
        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_product', ['product_id' => $product->id, 'quantity' => 2]);
        $this->assertEquals(8, $product->fresh()->stock); // Stock should have been decremented
    }

    /**
     * Test if an order can be retrieved using the OrderRepository.
     *
     * @return void
     */
    public function test_show_order(): void
    {
        // Arrange: Create an order and associate products
        $order = Order::factory()->hasProducts(3)->create();

        // Act: Retrieve the order via the repository
        $orderRepository = app(OrderRepositoryInterface::class); // Resolving the interface
        $retrievedOrder = $orderRepository->show($order->id);

        // Assert: Check if the retrieved order is correct
        $this->assertEquals($order->id, $retrievedOrder->id);
        $this->assertCount(3, $retrievedOrder->products); // Check if it has 3 products
    }

    /**
     * Test if creating an order with insufficient stock throws an exception.
     *
     * @return void
     */
    public function test_create_order_with_insufficient_stock(): void
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

        // Act & Assert: Try to create the order and expect an exception
        $orderRepository = app(OrderRepositoryInterface::class); // Resolving the interface

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Product {$product->name} does not have enough stock.");

        $orderRepository->create($data);
    }
}
