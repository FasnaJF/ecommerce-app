<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    /**
     * Test that unauthenticated users are redirected from cart
     */
    public function test_unauthenticated_user_cannot_view_cart()
    {
        $response = $this->get('/cart');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can view empty cart
     */
    public function test_authenticated_user_can_view_empty_cart()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/cart');
        
        $response->assertStatus(200);
    }

    /**
     * Test authenticated user can view cart with items
     */
    public function test_authenticated_user_can_view_cart_with_items()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 99.99]);
        
        // Create a pending order with items
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 99.99,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
        
        $response = $this->actingAs($user)->get('/cart');
        
        $response->assertStatus(200);
    }

    /**
     * Test unauthenticated user cannot add to cart
     */
    public function test_unauthenticated_user_cannot_add_to_cart()
    {
        $product = Product::factory()->create();
        
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can add product to cart
     */
    public function test_authenticated_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 99.99]);
        
        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        
        $response->assertRedirect();
    }

    /**
     * Test cannot add product with insufficient stock
     */
    public function test_cannot_add_product_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 2]);
        
        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
        
        // Should redirect back with error message
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Test cannot add product that doesn't exist
     */
    public function test_cannot_add_nonexistent_product_to_cart()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => 9999,
            'quantity' => 1,
        ]);
        
        $response->assertSessionHasErrors();
    }

    /**
     * Test quantity validation when adding to cart
     */
    public function test_quantity_validation_when_adding_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        
        // Test with zero quantity
        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 0,
        ]);
        
        $response->assertSessionHasErrors();
    }

    /**
     * Test authenticated user cannot update other users' cart items
     */
    public function test_user_cannot_update_other_users_cart()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();
        
        $order = Order::create([
            'user_id' => $user1->id,
            'status' => 'pending',
            'total' => 0,
        ]);
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 99.99,
        ]);
        
        $response = $this->actingAs($user2)->put("/cart/item/{$item->id}", [
            'quantity' => 2,
        ]);
        
        // Should redirect to login or deny access
        $response->assertStatus(302);
    }

    /**
     * Test authenticated user can remove item from cart
     */
    public function test_authenticated_user_can_remove_cart_item()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 99.99,
        ]);
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 99.99,
        ]);
        
        $response = $this->actingAs($user)->delete("/cart/item/{$item->id}");
        
        $response->assertRedirect();
    }
}
