<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_number')->index(); // e.g. PAY-202609-001
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('period', 7); // Format: YYYY-MM
            $table->date('payment_date')->nullable();
            
            // Earnings (Penghasilan)
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->decimal('fixed_allowance', 15, 2)->default(0);
            $table->decimal('attendance_allowance', 15, 2)->default(0); // Kehadiran * uang makan harian
            $table->decimal('overtime_pay', 15, 2)->default(0); // Jam lembur * tarif
            $table->decimal('bonus_pay', 15, 2)->default(0); // Total bonus + komisi
            $table->decimal('total_allowances', 15, 2)->default(0); // total penghasilan sebelum potongan
            
            // Deductions (Potongan)
            $table->decimal('absence_deduction', 15, 2)->default(0); // Potongan alpa/tidak hadir
            $table->decimal('loan_deduction', 15, 2)->default(0); // Potongan kasbon / pinjaman
            $table->decimal('bpjs_deduction', 15, 2)->default(0); // Potongan BPJS / asuransi
            $table->decimal('other_deductions', 15, 2)->default(0); // Potongan lain
            $table->decimal('total_deductions', 15, 2)->default(0);

            // Net Take Home Pay
            $table->decimal('net_salary', 15, 2)->default(0);

            // Payment & Accounting Status
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->foreignId('cash_transaction_id')->nullable()->constrained('cash_transactions')->onDelete('set null');
            $table->foreignId('paid_from_account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['employee_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
