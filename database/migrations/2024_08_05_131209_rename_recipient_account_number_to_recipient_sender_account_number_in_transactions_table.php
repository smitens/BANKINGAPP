<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('recipient_account_number', 'recipient_sender_account_number');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('recipient_sender_account_number', 'recipient_account_number');
        });
    }
};
