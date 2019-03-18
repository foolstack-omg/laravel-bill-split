<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bill_id')->index()->comment('账单ID');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unique(['bill_id', 'user_id']);
            $table->decimal('split_money', 10, 2)->comment('分摊金额');
            $table->boolean('fixed')->default(0)->comment('是否固定金额');
            $table->boolean('paid')->default(0)->comment('是否已支付');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_participants');
    }
}
