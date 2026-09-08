<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('period', 7); // Format: YYYY-MM
            $table->date('date');
            $table->string('title'); // e.g. Bonus Capai Target, Komisi Kasir, THR, Insentif Khusus
            $table->enum('type', ['bonus', 'commission', 'thr', 'incentive', 'other'])->default('bonus');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_bonuses');
    }
};
