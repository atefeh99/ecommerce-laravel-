<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_product()
    {
        $productData = [
            'name' => 'example product',
            'price' => 120000,
            'inventory' => 10
        ];

        $response = $this->post('/api/products', $productData);
        $response->assertOk();
        $response->assertStatus(201);
        $this->assertDatabaseHas('products', $productData);
    }
}
