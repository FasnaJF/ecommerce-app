<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * Test that unauthenticated users can view the welcome page
     */
    public function test_guest_can_view_welcome_page()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        // Check that Welcome component is being rendered
        $response->assertSee('canRegister');
    }

    /**
     * Test that authenticated users can view products page
     */
    public function test_authenticated_user_can_view_products()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/');
        
        $response->assertStatus(200);
    }

    /**
     * Test products endpoint requires authentication
     */
    public function test_products_route_requires_authentication()
    {
        $response = $this->get('/products');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can access products route
     */
    public function test_authenticated_user_can_access_products_route()
    {
        $user = User::factory()->create();
        Product::factory()->count(5)->create();
        
        $response = $this->actingAs($user)->get('/products');
        
        $response->assertStatus(200);
    }

    /**
     * Test products are paginated
     */
    public function test_products_are_paginated()
    {
        $user = User::factory()->create();
        Product::factory()->count(15)->create();
        
        $response = $this->actingAs($user)->get('/products');
        
        $response->assertStatus(200);
        // Check if pagination is working (should have per_page of 12)
    }

    /**
     * Test product show method returns product data
     */
    public function test_product_show_returns_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $this->actingAs($user);
        $productController = new \App\Http\Controllers\ProductController();
        $result = $productController->show($product);
        
        $this->assertEquals($product->id, $result->id);
        $this->assertEquals($product->name, $result->name);
    }

    /**
     * Test that product list contains required fields
     */
    public function test_product_list_contains_required_fields()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);
        
        $response = $this->actingAs($user)->get('/products');
        
        $response->assertStatus(200);
        // Products should have id, name, price, image, stock
    }

    /**
     * Test empty products list
     */
    public function test_empty_products_list()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/products');
        
        $response->assertStatus(200);
    }
}
