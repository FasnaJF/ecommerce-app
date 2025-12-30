<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please ensure users and products exist before seeding orders.');
            return;
        }

        // Create 15 random orders
        foreach (range(1, 15) as $index) {
            $user = $users->random();
            $orderProducts = $products->random(rand(1, 4)); // 1-4 products per order
            
            $total = 0;
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0, // Will update after adding items
                'status' => ['pending', 'processing', 'completed', 'cancelled'][rand(0, 3)],
            ]);

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $total += $price * $quantity;
            }

            // Update order total
            $order->update(['total' => $total]);
        }

        $this->command->info('Orders created successfully!');
    }
}