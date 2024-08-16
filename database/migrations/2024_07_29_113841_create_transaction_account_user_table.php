<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAccountUserTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_account_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('transaction_accounts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('access_type', ['view', 'transfer', 'full'])->default('view');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_account_user');
    }
}

