<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToInvestmentAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('investment_accounts', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('investment_accounts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
