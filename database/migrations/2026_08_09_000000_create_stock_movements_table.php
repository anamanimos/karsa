<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('type'); // purchase, purchase_update, purchase_delete, sale, sale_delete, manual_adjustment
            $table->double('quantity'); // positive for in, negative for out
            $table->double('stock_before');
            $table->double('stock_after');
            $table->nullableMorphs('reference'); // reference_type, reference_id
            $table->string('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
