<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number')->index(); // e.g. ADJ-202609-001
            $table->date('adjustment_date');
            $table->enum('type', ['in_manual', 'out_manual', 'opname', 'damaged', 'expired', 'internal_use', 'initial_stock'])->default('opname');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->double('system_stock')->default(0);
            $table->double('actual_stock')->default(0);
            $table->double('difference'); // actual - system (positive for gain, negative for loss)
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost_impact', 15, 2)->default(0); // difference * unit_cost
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
        Schema::dropIfExists('stock_adjustments');
    }
};
