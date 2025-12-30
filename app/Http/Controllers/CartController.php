<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $order = $this->getUserCart($request);

        return $order
            ? $order->load('items.product')
            : response()->json(['message' => 'Cart is empty'], 200);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock'], 422);
        }

        $order = $this->getOrCreateCart($request);

        $item = $order->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $order->items()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);
        }

        $this->updateOrderTotal($order);

        return response()->json(['message' => 'Product added to cart']);
    }

    public function update(Request $request, OrderItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($item->product->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock'], 422);
        }

        $item->update(['quantity' => $request->quantity]);

        $this->updateOrderTotal($item->order);

        return response()->json(['message' => 'Cart updated']);
    }

    public function remove(OrderItem $item)
    {
        $order = $item->order;

        $item->delete();
        $this->updateOrderTotal($order);

        return response()->json(['message' => 'Item removed']);
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
        $total = $order->items->sum(fn ($item) =>
            $item->quantity * $item->price
        );

        $order->update(['total' => $total]);
    }
}
