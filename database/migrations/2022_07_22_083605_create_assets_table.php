<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAssetsTable extends Migration 
{
	public function up()
	{
		Schema::create('assets', function(Blueprint $table) 
        {   
            $table->increments('id');
            $table->enum('asset_type', ['img', 'video', 'audio'])->default('img')->comment('资源类型');
            $table->string('path', 255)->default('')->comment('资源文件路径');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE assets comment '资源表'");
	}

	public function down()
	{
		Schema::drop('assets');
	}
}
