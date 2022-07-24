<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateControlInstructionsTable extends Migration
{
	public function up()
	{
		Schema::create('control_instructions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dev_id')->default(0)->comment('设备id');
            $table->string('action_type', 50)->default('')->comment('动作类型move移动指令/play_video播放视频指令/play_audio播放音频/play_related_video播放画中画视频操作/resize_related_video切换画中画视频的大小和位置/show_related_video画中画是否显示');
            $table->string('action', 30)->default('')->comment('移动指令left/right/up/down/jump-id/reset|播放音视频指令play/stop/resume/replay|画中画位置top/bottom/left/right/middle|画中画显示show/hidden');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE control_instructions comment '控制指令表'");
	}

	public function down()
	{
		Schema::drop('control_instructions');
	}
}
