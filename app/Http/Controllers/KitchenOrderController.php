<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.menu'])->latest()->get();
        return view('kitchen.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,preparing,ready,completed,cancelled'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('kitchen.index')->with('success', 'Order status updated successfully.');
    }
}
