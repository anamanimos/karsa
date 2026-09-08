<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('period', 7); // Format: YYYY-MM
            $table->unsignedInteger('work_days')->default(26);
            $table->unsignedInteger('present_days')->default(26);
            $table->unsignedInteger('sick_days')->default(0);
            $table->unsignedInteger('permission_days')->default(0);
            $table->unsignedInteger('absent_days')->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};
