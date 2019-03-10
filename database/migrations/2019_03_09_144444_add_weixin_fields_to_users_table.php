<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeixinFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->comment('用户头像')->after('remember_token');
            $table->tinyInteger('gender')->default(0)->comment('用户的性别，值为1时是男性，值为2时是女性，值为0时是未知')->after('avatar_url');
            $table->string('city')->comment('用户所在城市')->default('')->after('gender');
            $table->string('province')->comment('用户所在省份')->default('')->after('city');
            $table->string('country')->comment('用户所在国家')->default('')->after('province');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_url');
            $table->dropColumn('gender');
            $table->dropColumn('city');
            $table->dropColumn('province');
            $table->dropColumn('country');
        });
    }
}
