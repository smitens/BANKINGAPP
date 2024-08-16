<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentAccountUserTable extends Migration
{
    public function up()
    {
        Schema::create('investment_account_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('investment_accounts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('access_type', ['view', 'full'])->default('view');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('investment_account_user');
    }
}
