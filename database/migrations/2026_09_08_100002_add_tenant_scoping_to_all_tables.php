<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users table updates
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('active_business_id')->nullable()->after('password')->constrained('businesses')->nullOnDelete();
            $table->string('role')->default('user')->change();
        });

        // 2. Add business_id to all tenant-scoped tables
        $tables = [
            'categories',
            'units',
            'products',
            'suppliers',
            'customers',
            'purchases',
            'purchase_items',
            'purchase_price_histories',
            'supplier_payments',
            'sales',
            'sale_items',
            'customer_payments',
            'cash_transactions',
            'accounts',
            'employees',
            'employee_attendances',
            'employee_bonuses',
            'payrolls',
            'stock_adjustments',
            'stock_adjustment_items',
            'stock_movements',
            'cash_registers',
            'galleries',
            'labels',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('business_id')->nullable()->index()->constrained('businesses')->cascadeOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'categories',
            'units',
            'products',
            'suppliers',
            'customers',
            'purchases',
            'purchase_items',
            'purchase_price_histories',
            'supplier_payments',
            'sales',
            'sale_items',
            'customer_payments',
            'cash_transactions',
            'accounts',
            'employees',
            'employee_attendances',
            'employee_bonuses',
            'payrolls',
            'stock_adjustments',
            'stock_adjustment_items',
            'stock_movements',
            'cash_registers',
            'galleries',
            'labels',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'business_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['business_id']);
                    $table->dropColumn('business_id');
                });
            }
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'active_business_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['active_business_id']);
                $table->dropColumn('active_business_id');
            });
        }
    }
};
