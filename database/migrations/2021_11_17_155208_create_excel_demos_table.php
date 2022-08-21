<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateExcelDemosTable extends Migration
{
    public function up()
    {
        Schema::create('excel_demos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('str_column', 255)->default('')->comment('字符串字段');
            $table->bigInteger('int_column', false, true)->default(0)->comment('整数字段');
            $table->decimal('float_column', 20, 2, false)->default(0)->comment('浮点数字段');
            $table->string('pic_column', 255)->default('')->comment('图片字段');
            $table->text('text_column')->nullable()->comment('文本字段');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE excel_demos comment 'excel导入demo表'");
    }

    public function down()
    {
        Schema::drop('excel_demos');
    }
}
