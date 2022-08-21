<?php

namespace Database\Seeders;

use App\Models\ExcelDemoPicture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ExcelDemoPictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Storage::disk('public')->exists('excel_pic')) {
            Storage::disk('public')->makeDirectory('excel_pic');
        }

        $fontFace = public_path('ttf/msyh.ttf');
        $fontSize = 72;
        $at = now();
        $data = [];

        for ($i = 1; $i <= 10000; $i++) {
            $pic_name = 'excel_pic/avatar_'.$i.'.jpeg';
            $text = 'ID:'.$i;
            //根据序号生成图片，并增加序号水印
            Image::make(public_path('avatar.jpeg'))
                ->text(
                    $text,
                    20,
                    166,
                    function ($font) use ($fontFace, $fontSize) {
                        $font->file($fontFace);
                        $font->size($fontSize);
                        $font->color('#000000');
                    }
                )
                ->save(storage_path('app/public/'.$pic_name));

            $data[] = [
                'int_column'=>\mt_rand(10000000, 99999999),
                'pic_column'=>$pic_name,
                'created_at'=>$at,
                'updated_at'=>$at,
            ];
        }

        ExcelDemoPicture::insert($data);
    }
}
