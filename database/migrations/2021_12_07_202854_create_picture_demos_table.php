<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePictureDemosTable extends Migration
{
    public function up()
    {
        Schema::create('picture_demos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disk', 20)->default('')->comment('磁盘')->index('disk', 'disk');
            $table->string('path', 255)->default('')->comment('存储路径');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE picture_demos comment 'picture demo'");
    }

    public function down()
    {
        Schema::drop('picture_demos');
    }
}
