<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDeviceAssetsTable extends Migration
{
	public function up()
	{
		Schema::create('device_asset', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dev_id')->default(0)->comment('设备id');
            $table->integer('asset_id')->default(0)->comment('资源id');
            $table->integer('sort')->default(0)->comment('排序值');
            $table->timestamps();
            $table->index(['dev_id','asset_id'],'dev_asset_indx');
            $table->index('sort','sort');
        });
        DB::statement("ALTER TABLE device_asset comment '设备资源关联表'");
	}

	public function down()
	{
		Schema::drop('device_asset');
	}
}
