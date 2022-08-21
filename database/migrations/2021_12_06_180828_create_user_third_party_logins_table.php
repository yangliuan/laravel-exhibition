<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserThirdPartyLoginsTable extends Migration
{
    public function up()
    {
        Schema::create('user_third_party_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->default(0)->comment('用户id');
            $table->string('thirdparty', 20)->default('')->comment('第三方授权平台');
            $table->string('identifier', 64)->default('')->comment('用户在第三方平台唯一标识');
            $table->string('tnickname', 20)->default('')->comment('用户在第三方平台的昵称');
            $table->string('tavatar', 255)->default('')->comment('用户在第三方平台的头像');
            $table->timestamps();
            $table->index(['user_id','thirdparty','identifier'], 'default');
            $table->index(['thirdparty','identifier'], 'thirdparty_identifier');
        });
        DB::statement("ALTER TABLE user_third_party_logins comment '第三方用户登录绑定表'");
    }

    public function down()
    {
        Schema::drop('user_third_party_logins');
    }
}
