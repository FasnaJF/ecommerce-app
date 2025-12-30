<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $order = $request->user()
            ->orders()
            ->where('status', 'pending')
            ->with('items.product')
            ->firstOrFail();

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    abort(422, 'Insufficient stock for ' . $item->product->name);
                }

                $item->product->decrement('stock', $item->quantity);
            }

            $order->update(['status' => 'completed']);
        });

        return response()->json(['message' => 'Order placed successfully']);
    }

    public function history(Request $request)
    {
        return $request->user()
            ->orders()
            ->where('status', '!=', 'pending')
            ->with('items.product')
            ->latest()
            ->get();
    }

    public function show(Order $order)
    {
        return $order->load('items.product');
    }
}
