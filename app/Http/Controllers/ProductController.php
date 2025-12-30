<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'name', 'price', 'image', 'stock')
            ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    public function show(Product $product)
    {
        return $product;
    }
}
