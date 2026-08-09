<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'quantity' => 'double',
        'stock_before' => 'double',
        'stock_after' => 'double',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTransactionDateAttribute()
    {
        if ($this->reference) {
            if ($this->reference instanceof \App\Models\PurchaseItem && $this->reference->relationLoaded('purchase') && $this->reference->purchase) {
                return $this->reference->purchase->purchase_date;
            }
            if ($this->reference instanceof \App\Models\Purchase) {
                return $this->reference->purchase_date;
            }
            if ($this->reference instanceof \App\Models\SaleItem && $this->reference->relationLoaded('sale') && $this->reference->sale) {
                return $this->reference->sale->sale_date;
            }
            if ($this->reference instanceof \App\Models\Sale) {
                return $this->reference->sale_date;
            }
        }
        return $this->created_at;
    }

    /**
     * Record a stock movement.
     * Optionally updates $product->stock if $updateProductStock is true.
     */
    public static function log(
        Product $product,
        string $type,
        float $quantityChange,
        ?Model $reference = null,
        ?string $notes = null,
        ?int $userId = null,
        bool $updateProductStock = true,
        ?\DateTimeInterface $timestamp = null
    ): self {
        $stockBefore = (float) $product->stock;
        $stockAfter = $stockBefore + $quantityChange;

        if ($updateProductStock) {
            $product->stock = $stockAfter;
            $product->save();
        }

        $data = [
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantityChange,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->getKey() : null,
            'notes' => $notes,
            'created_by' => $userId ?? auth()->id(),
        ];

        if ($timestamp) {
            $data['created_at'] = $timestamp;
            $data['updated_at'] = $timestamp;
        }

        return self::create($data);
    }
}
