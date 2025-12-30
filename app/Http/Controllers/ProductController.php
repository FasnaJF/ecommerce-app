<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;


class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => Product::paginate(12),
        ]);
    }

    public function show(Product $product)
    {
        return $product;
    }
}
