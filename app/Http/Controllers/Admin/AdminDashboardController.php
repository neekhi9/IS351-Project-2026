<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'menus' => Menu::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
