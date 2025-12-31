<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    /**
     * Test that unauthenticated users cannot access orders
     */
    public function test_unauthenticated_user_cannot_view_orders()
    {
        $response = $this->get('/orders');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can view order history
     */
    public function test_authenticated_user_can_view_order_history()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/orders');
        
        $response->assertStatus(200);
    }

    /**
     * Test authenticated user sees only their orders
     */
    public function test_user_only_sees_their_orders()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Order::create([
            'user_id' => $user1->id,
            'status' => 'completed',
            'total' => 100.00,
        ]);
        
        Order::create([
            'user_id' => $user2->id,
            'status' => 'completed',
            'total' => 200.00,
        ]);
        
        $response = $this->actingAs($user1)->get('/orders');
        
        $response->assertStatus(200);
        // Only user1's orders should be visible
    }

    /**
     * Test unauthenticated user cannot checkout
     */
    public function test_unauthenticated_user_cannot_checkout()
    {
        $response = $this->post('/checkout');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test user cannot checkout without pending cart
     */
    public function test_user_cannot_checkout_without_pending_order()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/checkout');
        
        $response->assertStatus(404);
    }

    /**
     * Test user can checkout with valid pending order
     */
    public function test_user_can_checkout_with_valid_pending_order()
    {
        Mail::fake();
        
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 99.99]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 99.99,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 99.99,
        ]);
        
        $response = $this->actingAs($user)->post('/checkout');
        
        $response->assertRedirect('/orders');
        $response->assertSessionHas('success', 'Order placed successfully');
        
        // Verify order status changed to completed
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
        
        // Verify product stock was decremented
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 9,
        ]);
    }

    /**
     * Test checkout fails with insufficient stock
     */
    public function test_checkout_fails_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 2, 'price' => 99.99]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 199.98,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 5, // More than available stock
            'price' => 99.99,
        ]);
        
        $response = $this->actingAs($user)->post('/checkout');
        
        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
        
        // Order should remain pending
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test checkout with multiple items
     */
    public function test_checkout_with_multiple_items()
    {
        Mail::fake();
        
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['stock' => 10, 'price' => 50.00]);
        $product2 = Product::factory()->create(['stock' => 10, 'price' => 75.00]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 175.00,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 50.00,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 75.00,
        ]);
        
        $response = $this->actingAs($user)->post('/checkout');
        
        $response->assertRedirect('/orders');
        
        // Verify both products stock was decremented
        $this->assertDatabaseHas('products', [
            'id' => $product1->id,
            'stock' => 8,
        ]);
        
        $this->assertDatabaseHas('products', [
            'id' => $product2->id,
            'stock' => 9,
        ]);
    }

    /**
     * Test low stock alert is sent when stock falls below 5
     */
    public function test_low_stock_alert_sent_when_stock_below_five()
    {
        Mail::fake();
        
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 6, 'price' => 99.99]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 99.99,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2, // Will leave 4 in stock
            'price' => 99.99,
        ]);
        
        $this->actingAs($user)->post('/checkout');
        
        // Mail should have been sent for low stock alert
        Mail::assertQueued(\App\Mail\LowStockAlert::class);
    }

    /**
     * Test user can view order details
     */
    public function test_user_can_view_completed_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => 99.99,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 99.99,
        ]);
        
        $response = $this->actingAs($user)->get('/orders');
        
        $response->assertStatus(200);
    }

    /**
     * Test order total is correct
     */
    public function test_order_total_calculation()
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 50.00]);
        $product2 = Product::factory()->create(['price' => 75.00]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total' => 175.00,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 50.00,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 75.00,
        ]);
        
        // Total should be (50*2) + (75*1) = 175
        $this->assertEquals(175.00, $order->total);
    }
}
