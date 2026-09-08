<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code')->index(); // e.g., EMP-001
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('position'); // e.g. Kasir, Staff Gudang, Manager, Akuntan, Sales
            $table->enum('employment_status', ['permanent', 'contract', 'probation', 'daily'])->default('permanent');
            $table->date('join_date')->nullable();
            
            // Salary Structure
            $table->decimal('base_salary', 15, 2)->default(0); // Gaji Pokok
            $table->decimal('fixed_allowance', 15, 2)->default(0); // Tunjangan Tetap (Jabatan, dll)
            $table->decimal('daily_allowance', 15, 2)->default(0); // Uang Makan/Transport per hari hadir
            $table->decimal('overtime_rate_per_hour', 15, 2)->default(0); // Tarif lembur per jam
            $table->decimal('commission_rate', 5, 2)->default(0); // % Komisi Penjualan Kasir (optional)

            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
