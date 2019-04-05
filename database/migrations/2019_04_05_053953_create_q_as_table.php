<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQAsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q_as', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question')->comment('问题');
            $table->text('answer')->comment('回答');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `q_as` COMMENT 'Q&A'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('q_as');
    }
}
