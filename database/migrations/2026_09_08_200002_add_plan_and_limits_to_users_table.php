<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('role')->constrained('plans')->nullOnDelete();
            $table->integer('custom_max_businesses')->nullable()->after('plan_id');
            $table->integer('custom_max_employees')->nullable()->after('custom_max_businesses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'custom_max_businesses', 'custom_max_employees']);
        });
    }
};
