<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class ProductFeatureTest
 *
 * Feature tests for product-related API endpoints.
 */
class ProductFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test listing products using the API.
     *
     * Verifies that the endpoint returns paginated products in JSON format.
     */
    public function test_list_products(): void
    {
        Product::factory(10)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200) // Confirm the response status is 200.
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price', 'category_id', 'description', 'stock', 'created_at', 'updated_at'],
                ],
                'links',
                'meta',
            ]); // Validate the JSON structure.
    }

    /**
     * Test retrieving a specific product using the API.
     *
     * Verifies that the correct product details are returned.
     */
    public function test_show_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200) // Confirm the response status is 200.
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                ],
            ]); // Validate the returned product data.
    }

    /**
     * Test creating a product using the API.
     *
     * Verifies that the product is created and returns a success message.
     */
    public function test_create_product(): void
    {
        $data = [
            'name' => 'New Product',
            'price' => 49.99,
            'category_id' => 1,
            'description' => 'New product description',
            'stock' => 20,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201) // Confirm the response status is 201.
            ->assertJson([
                'message' => 'Product created successfully!',
            ]); // Validate the success message.

        $this->assertDatabaseHas('products', ['name' => 'New Product']); // Confirm database entry.
    }

    /**
     * Test updating a product using the API.
     *
     * Verifies that the product details are updated and returns a success message.
     */
    public function test_update_product(): void
    {
        $product = Product::factory()->create();

        $data = ['price' => 59.99, 'stock' => 15];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200) // Confirm the response status is 200.
            ->assertJson([
                'message' => 'Product updated successfully!',
            ]); // Validate the success message.

        $this->assertDatabaseHas('products', ['id' => $product->id, 'price' => 59.99, 'stock' => 15]); // Confirm database update.
    }
}
