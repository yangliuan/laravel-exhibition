<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDevicesTable extends Migration
{
	public function up()
	{
		Schema::create('devices', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('mac_address', 17)->default('')->comment('设备mac地址')->index('mac_address','mac_indx');
            $table->string('alias', 20)->default('')->comment('设备别名');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE devices comment '设备表'");
	}

	public function down()
	{
		Schema::drop('devices');
	}
}
