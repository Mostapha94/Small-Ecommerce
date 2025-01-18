<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ProductUnitTest
 *
 * Unit tests for the ProductRepository methods.
 */
class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ProductRepository
     *
     * The instance of the ProductRepository to be tested.
     */
    protected $productRepository;

    /**
     * Set up the test environment.
     *
     * Initializes the ProductRepository instance.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new ProductRepository();
    }

    /**
     * Test creating a product using the repository.
     *
     * Verifies that a product is created in the database and is an instance of the Product model.
     */
    public function test_create_product(): void
    {
        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
            'category_id' => 1,
            'description' => 'Test description',
            'stock' => 10,
        ];

        $product = $this->productRepository->create($data);

        $this->assertInstanceOf(Product::class, $product); // Verify the returned instance.
        $this->assertDatabaseHas('products', ['name' => 'Test Product']); // Confirm database entry.
    }

    /**
     * Test retrieving a product by ID using the repository.
     *
     * Verifies that the correct product is retrieved from the database.
     */
    public function test_show_product(): void
    {
        $product = Product::factory()->create();

        $retrievedProduct = $this->productRepository->show($product->id);

        $this->assertNotNull($retrievedProduct); // Ensure a product is returned.
        $this->assertEquals($product->id, $retrievedProduct->id); // Check if IDs match.
    }

    /**
     * Test updating a product using the repository.
     *
     * Verifies that a product's details are updated in the database.
     */
    public function test_update_product(): void
    {
        $product = Product::factory()->create();

        $data = ['price' => 199.99, 'stock' => 5];

        $updatedProduct = $this->productRepository->update($product->id, $data);

        $this->assertEquals(199.99, $updatedProduct->price); // Verify updated price.
        $this->assertEquals(5, $updatedProduct->stock); // Verify updated stock.
    }
}
