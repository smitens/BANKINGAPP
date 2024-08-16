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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('transaction_accounts')->onDelete('cascade');
            $table->enum('type', ['transfer_out', 'transfer_in']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->foreignId('recipient_account_id')->nullable()->constrained('transaction_accounts')->onDelete('set null');
            $table->text('description')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->decimal('transaction_fee', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
