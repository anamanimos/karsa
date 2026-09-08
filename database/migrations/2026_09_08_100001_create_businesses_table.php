<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('business_type')->default('general'); // general, retail, fnb, service, agriculture, wholesale
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('tax_id', 50)->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->string('timezone', 50)->default('Asia/Jakarta');
            $table->string('currency_symbol', 10)->default('Rp');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('min_margin', 15, 2)->default(1000);
            $table->boolean('require_shift_for_pos')->default(false);
            $table->string('receipt_footer', 500)->default('Terima kasih atas kunjungan Anda!');
            $table->string('telegram_bot_token')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('business_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('staff'); // owner, manager, cashier, staff
            $table->timestamps();

            $table->unique(['business_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_users');
        Schema::dropIfExists('businesses');
    }
};
