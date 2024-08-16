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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('investment_accounts')->onDelete('cascade');
            $table->string('crypto_name');
            $table->decimal('total_quantity', 16, 8)->default(0); // Adjust precision if needed
            $table->decimal('total_invested', 16, 2)->default(0);
            $table->decimal('aver_price', 16, 2)->default(0);
            $table->decimal('total_value', 16, 2)->default(0);
            $table->decimal('aver_profit_loss', 16, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
