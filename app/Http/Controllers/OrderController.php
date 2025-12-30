<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $order = $request->user()
            ->orders()
            ->where('status', 'pending')
            ->with('orderItems.product')
            ->firstOrFail();

        // Validate stock before performing the transaction
        foreach ($order->orderItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect('/cart')->with('error', 'Insufficient stock for ' . $item->product->name);
            }
        }

        DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            $order->update(['status' => 'completed']);
        });

        return redirect('/orders')->with('success', 'Order placed successfully');
    }

    public function history(Request $request)
    {
        return Inertia::render('Orders/Index', [
            'orders' => $request->user()
                ->orders()
                ->where('status', '!=', 'pending')
                ->with('orderItems.product')
                ->latest()
                ->get(),
        ]);
    }


    public function show(Order $order)
    {
        $order->load('orderItems.product');

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }
}
