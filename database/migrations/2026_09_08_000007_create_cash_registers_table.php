<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->decimal('initial_cash', 15, 2)->default(0);
            $table->decimal('total_cash_sales', 15, 2)->default(0);
            $table->decimal('total_non_cash_sales', 15, 2)->default(0);
            $table->decimal('total_cash_in', 15, 2)->default(0); // Tambahan kas masuk selama shift
            $table->decimal('total_cash_out', 15, 2)->default(0); // Pengeluaran kas selama shift
            $table->decimal('expected_cash', 15, 2)->default(0); // Saldo kas menurut sistem
            $table->decimal('actual_cash', 15, 2)->nullable(); // Uang fisik di laci saat tutup
            $table->decimal('difference', 15, 2)->default(0); // actual - expected (selisih lebih/kurang)
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
