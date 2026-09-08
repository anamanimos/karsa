<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockAdjustment extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'adjustment_number',
        'adjustment_date',
        'type', // in_manual, out_manual, opname, damaged, expired, internal_use, initial_stock
        'reason',
        'created_by',
    ];

    protected $casts = [
        'adjustment_date' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
