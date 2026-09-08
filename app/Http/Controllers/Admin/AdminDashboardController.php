<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_superadmins' => User::where('role', 'superadmin')->count(),
            'total_owners' => User::where('role', 'user')->count(),
            'total_cashiers' => User::where('role', 'kasir')->count(),
            'total_businesses' => Business::count(),
            'active_businesses' => Business::where('is_active', true)->count(),
            'total_products' => Product::withoutGlobalScope('business')->count(),
            'total_sales_count' => Sale::withoutGlobalScope('business')->count(),
            'total_sales_amount' => Sale::withoutGlobalScope('business')->sum('total_amount'),
        ];

        $recentUsers = User::latest()->take(6)->get();
        $recentBusinesses = Business::with('owner')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBusinesses'));
    }
}
