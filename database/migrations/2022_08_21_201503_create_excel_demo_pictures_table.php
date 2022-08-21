<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_demo_pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('str_column', 255)->default('')->comment('字符串字段');
            $table->bigInteger('int_column', false, true)->default(0)->comment('整数字段');
            $table->decimal('float_column', 20, 2, false)->default(0)->comment('浮点数字段');
            $table->string('pic_column', 255)->default('')->comment('图片字段');
            $table->text('text_column')->nullable()->comment('文本字段');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE excel_demo_pictures comment 'excel导入demo表-用于自动生成图片'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excel_demo_pictures');
    }
};
