<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Pro 15"',
                'description' => 'High-performance laptop with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
                'stock' => 25,
                'image' => 'laptop-pro.jpg',
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with 6 buttons',
                'price' => 29.99,
                'stock' => 150,
                'image' => 'wireless-mouse.jpg',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with blue switches',
                'price' => 89.99,
                'stock' => 75,
                'image' => 'keyboard.jpg',
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C hub with HDMI and card reader',
                'price' => 49.99,
                'stock' => 100,
                'image' => 'usb-hub.jpg',
            ],
            [
                'name' => '27" Monitor',
                'description' => '4K UHD monitor with HDR support',
                'price' => 399.99,
                'stock' => 40,
                'image' => 'monitor.jpg',
            ],
            [
                'name' => 'Webcam HD',
                'description' => '1080p webcam with built-in microphone',
                'price' => 79.99,
                'stock' => 60,
                'image' => 'webcam.jpg',
            ],
            [
                'name' => 'Laptop Stand',
                'description' => 'Aluminum adjustable laptop stand',
                'price' => 34.99,
                'stock' => 120,
                'image' => 'laptop-stand.jpg',
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Noise-cancelling Bluetooth headphones',
                'price' => 199.99,
                'stock' => 50,
                'image' => 'headphones.jpg',
            ],
            [
                'name' => 'External SSD 1TB',
                'description' => 'Portable external SSD with USB 3.2',
                'price' => 129.99,
                'stock' => 80,
                'image' => 'external-ssd.jpg',
            ],
            [
                'name' => 'Desk Lamp LED',
                'description' => 'Adjustable LED desk lamp with touch control',
                'price' => 45.99,
                'stock' => 90,
                'image' => 'desk-lamp.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
