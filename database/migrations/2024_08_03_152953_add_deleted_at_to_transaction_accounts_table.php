<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToTransactionAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('transaction_accounts', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('transaction_accounts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
