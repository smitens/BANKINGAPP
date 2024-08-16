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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('investment_accounts')->onDelete('cascade');
            $table->enum('type', ['crypto', 'stock']);
            $table->string('name');
            $table->decimal('amount_invested', 15, 2);
            $table->decimal('quantity', 15, 2);
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('current_price', 15, 2)->nullable();
            $table->decimal('total_value', 15, 2)->nullable();
            $table->decimal('profit_loss', 15, 2)->nullable();
            $table->date('purchase_date');
            $table->enum('status', ['bought', 'sold']);
            $table->decimal('investment_fee', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
