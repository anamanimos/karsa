<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gallery extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'filename',
        'filepath',
        'mime_type',
        'file_size',
    ];

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'image', 'filepath');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'invoice_image', 'filepath');
    }

    public function getIsUsedAttribute(): bool
    {
        return $this->products()->exists() || $this->purchases()->exists();
    }

    public function getUsagesAttribute(): array
    {
        $usages = [];

        foreach ($this->products as $product) {
            $usages[] = [
                'type' => 'Produk',
                'name' => $product->name,
                'show_url' => route('products.show', $product),
                'edit_url' => route('products.edit', $product),
            ];
        }

        foreach ($this->purchases as $purchase) {
            $usages[] = [
                'type' => 'Nota Pembelian',
                'name' => $purchase->invoice_number,
                'show_url' => route('purchases.show', $purchase),
                'edit_url' => null,
            ];
        }

        return $usages;
    }
}
