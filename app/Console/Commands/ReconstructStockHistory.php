<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconstructStockHistory extends Command
{
    protected $signature = 'stock:reconstruct-history {--force : Force execution without confirmation}';
    protected $description = 'Reconstruct stock movement history from all past purchases, sales, and deleted transactions chronologically.';

    public function handle(): int
    {
        if (!$this->option('force') && !$this->confirm('Perintah ini akan menghapus tabel stock_movements dan merelokasi ulang riwayat stok dari transaksi historis. Lanjutkan?')) {
            $this->info('Dibatalkan.');
            return 0;
        }

        $this->info('Memulai rekonstruksi riwayat pergerakan stok...');

        DB::transaction(function () {
            // Clear existing movements using DML delete to avoid MySQL implicit DDL commit
            StockMovement::query()->delete();

            $products = Product::all();
            $count = 0;

            foreach ($products as $product) {
                $events = [];

                // 1. Collect Purchase Items (including soft deleted purchases)
                $purchaseItems = PurchaseItem::where('product_id', $product->id)
                    ->whereHas('purchase', function ($query) {
                        $query->withTrashed();
                    })
                    ->with(['purchase' => function ($query) {
                        $query->withTrashed();
                    }])
                    ->get();

                foreach ($purchaseItems as $pItem) {
                    $purchase = $pItem->purchase;
                    if (!$purchase) continue;

                    // Creation event
                    $events[] = [
                        'timestamp' => $pItem->created_at ?? $purchase->created_at ?? $purchase->purchase_date,
                        'type' => 'purchase',
                        'quantity' => (float) $pItem->quantity,
                        'reference_type' => get_class($pItem),
                        'reference_id' => $pItem->id,
                        'notes' => "Pembelian {$purchase->invoice_number}",
                        'user_id' => $purchase->created_by,
                    ];

                    // Deletion event if purchase was soft-deleted
                    if ($purchase->trashed() && $purchase->deleted_at) {
                        $events[] = [
                            'timestamp' => $purchase->deleted_at,
                            'type' => 'purchase_delete',
                            'quantity' => -(float) $pItem->quantity,
                            'reference_type' => get_class($purchase),
                            'reference_id' => $purchase->id,
                            'notes' => "Hapus Nota Pembelian {$purchase->invoice_number}",
                            'user_id' => null,
                        ];
                    }
                }

                // 2. Collect Sale Items (including soft deleted sales)
                $saleItems = SaleItem::where('product_id', $product->id)
                    ->whereHas('sale', function ($query) {
                        $query->withTrashed();
                    })
                    ->with(['sale' => function ($query) {
                        $query->withTrashed();
                    }])
                    ->get();

                foreach ($saleItems as $sItem) {
                    $sale = $sItem->sale;
                    if (!$sale) continue;

                    $stockReduction = (float) ($sItem->quantity * $product->conversion_factor);

                    // Creation event
                    $events[] = [
                        'timestamp' => $sItem->created_at ?? $sale->created_at ?? $sale->sale_date,
                        'type' => 'sale',
                        'quantity' => -$stockReduction,
                        'reference_type' => get_class($sItem),
                        'reference_id' => $sItem->id,
                        'notes' => "Penjualan {$sale->invoice_number}",
                        'user_id' => $sale->created_by,
                    ];

                    // Deletion event if sale was soft-deleted
                    if ($sale->trashed() && $sale->deleted_at) {
                        $events[] = [
                            'timestamp' => $sale->deleted_at,
                            'type' => 'sale_delete',
                            'quantity' => $stockReduction,
                            'reference_type' => get_class($sale),
                            'reference_id' => $sale->id,
                            'notes' => "Hapus Nota Penjualan {$sale->invoice_number}",
                            'user_id' => null,
                        ];
                    }
                }

                // Sort events chronologically by timestamp, then by ID/type priority
                usort($events, function ($a, $b) {
                    $tA = strtotime((string) $a['timestamp']);
                    $tB = strtotime((string) $b['timestamp']);
                    return $tA <=> $tB;
                });

                // Calculate stock_before and stock_after step-by-step
                $currentStock = 0.0;
                foreach ($events as $event) {
                    $stockBefore = $currentStock;
                    $currentStock += $event['quantity'];
                    $stockAfter = $currentStock;

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => $event['type'],
                        'quantity' => $event['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'reference_type' => $event['reference_type'],
                        'reference_id' => $event['reference_id'],
                        'notes' => $event['notes'],
                        'created_by' => $event['user_id'],
                        'created_at' => $event['timestamp'],
                        'updated_at' => $event['timestamp'],
                    ]);
                    $count++;
                }

                // Sync current product stock to reconstructed final stock
                $product->stock = $currentStock;
                $product->save();
            }

            $this->info("Rekonstruksi selesai! Berhasil merekam {$count} mutasi stok untuk {$products->count()} produk.");
        });

        return 0;
    }
}
