<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateImageDemosTable extends Migration
{
    public function up()
    {
        Schema::create('image_demos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 255)->default('')->comment('存储路径');
            $table->text('path_group')->nullable()->comment('存储路径组');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE image_demos comment 'image demo'");
    }

    public function down()
    {
        Schema::drop('image_demos');
    }
}
