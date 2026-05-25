<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * Admins are redirected to the admin registrations dashboard.
     * Regular users see the normal home view.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->route('admin.registrations.index');
        }

        return view('home');
    }
}
