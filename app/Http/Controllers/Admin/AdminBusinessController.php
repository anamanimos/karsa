<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminBusinessController extends Controller
{
    public function index(Request $request): View
    {
        $query = Business::with('owner')->withCount(['products', 'sales', 'users']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('business_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $businesses = $query->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total' => Business::count(),
            'active' => Business::where('is_active', true)->count(),
            'inactive' => Business::where('is_active', false)->count(),
        ];

        return view('admin.businesses.index', compact('businesses', 'stats'));
    }

    public function show(Business $business): View
    {
        $business->load(['owner', 'users']);
        $productsCount = Product::withoutGlobalScope('business')->where('business_id', $business->id)->count();
        $salesCount = Sale::withoutGlobalScope('business')->where('business_id', $business->id)->count();
        $salesTotal = Sale::withoutGlobalScope('business')->where('business_id', $business->id)->sum('total_amount');

        return view('admin.businesses.show', compact('business', 'productsCount', 'salesCount', 'salesTotal'));
    }

    public function toggleStatus(Business $business): RedirectResponse
    {
        $business->update(['is_active' => !$business->is_active]);
        $statusText = $business->is_active ? 'diaktifkan kembali' : 'dibekukan (dinonaktifkan)';

        return redirect()->back()->with('success', "Status usaha \"{$business->name}\" berhasil {$statusText}.");
    }

    public function impersonate(Business $business): RedirectResponse
    {
        Auth::user()->update(['active_business_id' => $business->id]);

        return redirect()
            ->route('dashboard')
            ->with('success', "Super Admin beralih tampilan ke bisnis: {$business->name}");
    }
}
