<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustmentItem extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'stock_adjustment_id',
        'product_id',
        'system_stock',
        'actual_stock',
        'difference',
        'unit_cost',
        'total_cost_impact',
        'notes',
    ];

    protected $casts = [
        'system_stock' => 'double',
        'actual_stock' => 'double',
        'difference' => 'double',
        'unit_cost' => 'double',
        'total_cost_impact' => 'double',
    ];

    public function stockAdjustment(): BelongsTo
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
