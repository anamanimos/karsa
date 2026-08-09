<?php

namespace App\Observers;

use App\Models\SaleItem;
use App\Models\StockMovement;

class SaleItemObserver
{
    /**
     * Handle the SaleItem "created" event.
     *
     * When a sale item is created:
     * 1. Convert quantity from sell_unit to buy_unit using conversion_factor
     * 2. Validate sufficient stock
     * 3. Decrease product stock and log movement
     */
    public function created(SaleItem $saleItem): void
    {
        $product = $saleItem->product;
        $sale = $saleItem->sale;

        // 1. Convert quantity from sell_unit to buy_unit
        $stockReduction = (float) ($saleItem->quantity * $product->conversion_factor);

        // 2. Validate stock availability
        if ($product->stock < $stockReduction) {
            throw new \RuntimeException(
                "Stok tidak mencukupi untuk produk {$product->name}. " .
                "Stok tersedia: {$product->stock} {$product->buyUnit->symbol}, " .
                "dibutuhkan: {$stockReduction} {$product->buyUnit->symbol}."
            );
        }

        // 3. Decrease product stock and log movement
        $invoiceNo = $sale ? $sale->invoice_number : '';
        $saleDate = ($sale && $sale->sale_date) ? \Carbon\Carbon::parse($sale->sale_date)->setTimeFrom(\Carbon\Carbon::now()) : null;

        StockMovement::log(
            product: $product,
            type: 'sale',
            quantityChange: -$stockReduction,
            reference: $saleItem,
            notes: "Penjualan {$invoiceNo}",
            userId: $sale ? $sale->created_by : auth()->id(),
            updateProductStock: true,
            timestamp: $saleDate
        );
    }
}
