<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['items.product', 'creator']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('adjustment_date', [$request->start_date, $request->end_date]);
        }

        $adjustments = $query->latest('adjustment_date')->latest('id')->paginate(15)->withQueryString();

        return view('stock-adjustments.index', compact('adjustments'));
    }

    public function create(Request $request)
    {
        $defaultType = $request->get('type', 'opname');
        $categories = Category::orderBy('name')->get();
        $products = Product::where('is_active', true)->with(['category', 'sellUnit'])->orderBy('name')->get();

        return view('stock-adjustments.create', compact('defaultType', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adjustment_date' => 'required|date',
            'type' => 'required|in:in_manual,out_manual,opname,damaged,expired,internal_use,initial_stock',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.actual_stock' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $today = Carbon::parse($validated['adjustment_date'])->format('Ymd');
            $prefix = 'ADJ-' . $today . '-';
            $lastAdj = StockAdjustment::where('adjustment_number', 'like', $prefix . '%')
                ->orderBy('adjustment_number', 'desc')
                ->first();
            $nextNum = $lastAdj ? str_pad(intval(substr($lastAdj->adjustment_number, -4)) + 1, 4, '0', STR_PAD_LEFT) : '0001';
            $adjNumber = $prefix . $nextNum;

            $adjustment = StockAdjustment::create([
                'adjustment_number' => $adjNumber,
                'adjustment_date' => $validated['adjustment_date'],
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $product = Product::withoutGlobalScope('business')->findOrFail($itemData['product_id']);
                $systemStock = (float) $product->stock;
                $actualStock = (float) $itemData['actual_stock'];
                $diff = $actualStock - $systemStock;

                $unitCost = (float) ($product->avg_purchase_price > 0 ? $product->avg_purchase_price : $product->last_purchase_price);
                $totalCostImpact = $diff * $unitCost;

                $adjItem = StockAdjustmentItem::create([
                    'stock_adjustment_id' => $adjustment->id,
                    'product_id' => $product->id,
                    'system_stock' => $systemStock,
                    'actual_stock' => $actualStock,
                    'difference' => $diff,
                    'unit_cost' => $unitCost,
                    'total_cost_impact' => $totalCostImpact,
                    'notes' => $itemData['notes'] ?? null,
                ]);

                // Log movement and update product stock
                $movementType = 'stock_' . $adjustment->type;
                StockMovement::log(
                    $product,
                    $movementType,
                    $diff,
                    $adjItem,
                    "Penyesuaian Stok ({$adjustment->type}): " . ($adjustment->reason ?: 'Penyesuaian manual'),
                    auth()->id(),
                    true,
                    Carbon::parse($validated['adjustment_date'])
                );
            }
        });

        return redirect()->route('stock-adjustments.index')
            ->with('success', 'Penyesuaian stok berhasil disimpan dan stok produk telah diselaraskan.');
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->load(['items.product.sellUnit', 'creator']);
        return view('stock-adjustments.show', compact('stockAdjustment'));
    }
}
