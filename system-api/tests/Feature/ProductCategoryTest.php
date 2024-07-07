<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    public function test_can_create_a_product_category()
    {
        $response = $this->postJson('/api/product-categories', [
            'name' => 'Scanner',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Scanner',
            ]);

        $this->assertDatabaseHas('product_categories', ['name' => 'Scanner']);
    }

    public function test_can_update_a_product_category()
    {
        $productCategory = ProductCategory::factory()->create([
            'name' => 'Scanner',
        ]);

        $response = $this->putJson("/api/product-categories/{$productCategory->id}", [
            'name' => 'Scanner v2',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Scanner v2',
            ]);

        $this->assertDatabaseHas('product_categories', [
            'name' => 'Scanner v2',
        ]);
    }

    public function test_can_delete_a_product_category()
    {
        $productCategory = ProductCategory::factory()->create([
            'name' => 'Scanner',
        ]);

        $response = $this->deleteJson("/api/product-categories/{$productCategory->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('product_categories', ['id' => $productCategory->id]);
    }

    public function test_can_show_a_product_category_with_product()
    {
        $productCategory = ProductCategory::factory()->create([
            'name' => 'Scanner',
        ]);

        $product = Product::factory()->create([
            'name' => 'ScanTool',
            'product_category_id' => $productCategory->id,
            'price' => 1500,
            'description' => 'ScanTool use to scan system for error and show diagnostic.',
        ]);

        $response = $this->getJson("/api/product-categories/{$productCategory->id}/?with=products");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $productCategory->name,
            ])
            ->assertJsonFragment([
                'name' => $product->name,
                'product_category_id' => $product->product_category_id,
            ]);
    }

    public function test_can_list_product_category_with_products()
    {
        $productCategory = ProductCategory::factory()->create([
            'name' => 'Scanner',
        ]);

        Product::factory()->count(3)->create([
            'product_category_id' => $productCategory->id,
        ]);

        $response = $this->getJson('/api/product-categories?with=products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'products'],
            ]);
    }
}
