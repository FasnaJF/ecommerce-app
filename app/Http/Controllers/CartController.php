<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()
            ->orders()
            ->where('status', 'pending')
            ->with('orderItems.product')
            ->first();

        return Inertia::render('Cart/Index', [
            'cart' => $cart
                ? [
                    'id' => $cart->id,
                    'total' => $cart->total,
                    'orderItems' => $cart->orderItems ?? [],
                ]
                : null,
        ]);
    }


    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not enough stock'], 422);
            }
            return back()->with('error', 'Not enough stock')->withInput();
        }

        $order = $this->getOrCreateCart($request);

        $item = $order->orderItems()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        $this->updateOrderTotal($order);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Product added to cart']);
        }

        return redirect('/cart')->with('success', 'Product added to cart');
    }

    public function update(Request $request, OrderItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($item->product->stock < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not enough stock'], 422);
            }
            return back()->with('error', 'Not enough stock')->withInput();
        }

        $item->update(['quantity' => $request->quantity]);

        $this->updateOrderTotal($item->order);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Cart updated']);
        }

        return redirect('/cart')->with('success', 'Cart updated');
    }

    public function remove(OrderItem $item)
    {
        $order = $item->order;

        $item->delete();
        $this->updateOrderTotal($order);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Item removed']);
        }

        return redirect('/cart')->with('success', 'Item removed');
    }

    /* ---------------- HELPERS ---------------- */

    private function getUserCart(Request $request)
    {
        return $request->user()
            ->orders()
            ->where('status', 'pending')
            ->first();
    }

    private function getOrCreateCart(Request $request)
    {
        return $request->user()
            ->orders()
            ->firstOrCreate(
                ['status' => 'pending'],
                ['total' => 0]
            );
    }

    private function updateOrderTotal(Order $order)
    {
        $total = $order->orderItems->sum(
            fn($item) =>
            $item->quantity * $item->price
        );

        $order->update(['total' => $total]);
    }
}
