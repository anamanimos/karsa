<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockMovement::with([
            'product.buyUnit',
            'creator',
            'reference' => function ($morphTo) {
                $morphTo->morphWith([
                    PurchaseItem::class => ['purchase.supplier'],
                    SaleItem::class => ['sale.customer'],
                ]);
            }
        ]);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sort = $request->input('sort', 'desc');
        if ($sort === 'asc') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $movements = $query->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('reports.stock_movements', compact('movements', 'products', 'sort'));
    }
}
