<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSplitMoneyFormatInTableBillParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_participants', function (Blueprint $table) {
            $table->decimal('split_money', 12, 2)->comment('分摊金额')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_participants', function (Blueprint $table) {
            $table->decimal('split_money', 10, 2)->comment('分摊金额')->change();
        });
    }
}
