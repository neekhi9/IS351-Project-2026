<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.menu')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menus = Menu::where('is_available', true)->orderBy('category')->orderBy('item_name')->get();
        return view('orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_amount' => 0,
                'notes' => $validated['notes'] ?? null,
                'ordered_at' => now(),
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $qty = (int) $item['quantity'];
                $unitPrice = (float) $menu->price;
                $subtotal = $qty * $unitPrice;

                $order->items()->create([
                    'menu_id' => $menu->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total_amount' => $total]);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.menu');
        return view('orders.show', compact('order'));
    }
}
