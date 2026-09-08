<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount_amount', 15, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('discount_amount');
            $table->foreignId('cash_register_id')->nullable()->after('created_by')->constrained('cash_registers')->onDelete('set null');
            $table->foreignId('cashier_employee_id')->nullable()->after('cash_register_id')->constrained('employees')->onDelete('set null');
        });

        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('amount')->constrained('accounts')->onDelete('set null');
            $table->nullableMorphs('reference');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['cash_register_id']);
            $table->dropForeign(['cashier_employee_id']);
            $table->dropColumn(['discount_amount', 'tax_amount', 'cash_register_id', 'cashier_employee_id']);
        });

        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropMorphs('reference');
            $table->dropColumn(['account_id']);
        });
    }
};
