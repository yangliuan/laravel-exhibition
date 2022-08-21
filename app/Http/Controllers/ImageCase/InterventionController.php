<?php

namespace App\Http\Controllers\ImageCase;

use App\Http\Controllers\Controller;
use App\Traits\ImageUtils;
use Illuminate\Http\Request;
use Imagick;
use ImagickDraw;
use Intervention\Image\Facades\Image;

class InterventionController extends Controller
{
    use ImageUtils;

    //生成水印
    public function watermarker(Request $request)
    {
        $backgroudImg = Image::make(public_path('img/test.jpeg'));
        $backgroudImg->insert(public_path('img/watermark.png'), 'bottom', 200, mt_rand(10, 100))
            ->save(storage_path('app/public/test.jpeg'));

        return '<img src="'.asset('storage/test.jpeg').'"/>';
    }

    //图文混合水印，类似新浪微博，自动适配图片大小
    public function picwordWatermark(Request $request)
    {
        $fontFace = public_path('ttf/msyh.ttf');
        $fontSize = 36;
        $text = '@我是昵称';
        $originWartermarkImg = Image::make(public_path('img/watermark.png'));
        $imagick = $originWartermarkImg->getCore();
        $draw = new ImagickDraw;
        $draw->setFont($fontFace);
        $draw->setFontSize($fontSize);
        $fontInfo = $imagick->queryFontMetrics($draw, $text);
        dump($fontInfo);
        $fontInfo = $this->getFontWidthHeightInImage($fontSize, 0, $fontFace, $text);
        dump($fontInfo);
    }

    //基于背景图片添加图片和文字绘制
    public function drawText(Request $request)
    {
        $backgroudImg = Image::make(public_path('img/tips.png'));
        $fontFace = public_path('ttf/msyh.ttf');
        $fontSize = 14;
        $angel = 0; //旋转角度
        $width = 500; //换行宽度
        $text = '  家长最好在 6 个月以后，逐渐引导宝宝使用带吸口或吸管的杯子。这时候可以先培养宝宝用杯子喝水的习惯，等宝宝适应后，再慢慢过渡到用杯子喝奶。';

        $backgroudImg->text(
            $this->textAutowrapInImage($fontSize, $angel, $fontFace, $text, $width),
            80,
            350,
            function ($font) use ($fontFace, $fontSize) {
                $font->file($fontFace);
                $font->size($fontSize);
                $font->color('#000000');
            }
        )->save(storage_path('app/public/tips.jpeg'));

        return '<img src="'.asset('storage/tips.jpeg').'"/>';
    }

    //使用图层蒙版裁切成圆形,其它图形原理相同
    public function maskCutRound(Request $request)
    {
        $width = 154;
        $height = 154;

        $avatarImg = Image::make(public_path('img/avatar.jpeg'))->fit(154, 154)->encode('png');
        $maskImg = Image::canvas($width, $height)
            ->circle($width - 5, $width / 2, $height / 2, function ($draw) {
                $draw->background('#000000');
            });
        $avatarImg->mask($maskImg)->save(storage_path('app/public/cut-round.png'));

        return '<img src="'.asset('storage/cut-round.png').'"/>';
    }

    //海报绘制圆形头像
    public function drawGraphics(Request $request)
    {
        $width = 154;
        $height = 154;
        $backgroudImg = Image::make(public_path('img/draw-graphics.png'));
        $avatarImg = Image::make(public_path('img/avatar.jpeg'))->fit(154, 154)->encode('png');
        $maskImg = Image::canvas($width, $height)
            ->circle($width - 5, $width / 2, $height / 2, function ($draw) {
                $draw->background('#000000');
            });
        $avatarImg->mask($maskImg);
        $backgroudImg->insert($avatarImg, 'top-left', 29, 126)->save(storage_path('app/public/draw-graphics.png'));

        return '<img src="'.asset('storage/draw-graphics.png').'"/>';
    }

    //平铺填充图片
    public function tile(Request $request)
    {
        $img = Image::canvas(800, 600);
        $img->fill(public_path('img/avatar.jpeg'));
        $img->save(storage_path('app/public/tile.jpeg'));

        return '<img src="'.asset('storage/tile.jpeg').'"/>';
    }

    //绘制图形并响应为图片
    public function responseImg(Request $request)
    {
        $img = Image::canvas(800, 600, '#ddd');
        $img->circle(100, 100, 100, function ($draw) {
            $draw->background('#0000ff');
            $draw->border(3, '#f00');
        });

        return $img->response();
    }

    //读取base64图片
    public function readBase64(Request $request)
    {
        $avatar = file_get_contents(public_path('img/test.jpeg'));
        $avatar = base64_encode($avatar);
        Image::make($avatar)->save(storage_path('app/public/base64.jpeg'));

        return '<img src="'.asset('storage/base64.jpeg').'"/>';
    }

    //保持尺寸压缩图片大小
    //采用微信朋友圈压缩比例算法
    public function compress(Request $request)
    {
        $request->validate([
            'image' => 'bail|required|image',
            'compress' => 'bail|nullable|string|in:original,wechat',
        ]);

        $uploadImage = Image::make($request->image);
        $width = $uploadImage->width();
        $height = $uploadImage->height();

        if ($request->compress == 'wechat') {
            if ($width <= 1280 && $height <= 1280) {
                //宽高均 <= 1280，图片尺寸大小保持不变
                $uploadImage = $uploadImage->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } elseif (($width > 1280 || $height > 1280) && round($width / $height) <= 2) {
                if ($width > $height) {
                    $uploadImage = $uploadImage->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $uploadImage = $uploadImage->resize(null, 1280, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            } elseif (($width > 1280 || $height > 1280) && round($width / $height) > 2 && ($width < 1280 || $height < 1280)) {
                $uploadImage = $uploadImage->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } elseif (($width > 1280 && $height > 1280) && round($width / $height) > 2) {
                if ($width > $height) {
                    $uploadImage = $uploadImage->resize(null, 1280, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $uploadImage = $uploadImage->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
        } else {
            $uploadImage = $uploadImage->resize($width, $height);
        }

        $extension = $request->image->getClientOriginalExtension();
        $uploadImage->save(storage_path('app/public/upload-img.'.$extension), '75', $extension);

        return '<img src="'.asset('storage/upload-img.'.$extension).'"/>';
    }

    //适配裁切方式生成缩略图
    public function thumbnail(Request $request)
    {
        $request->validate([
            'image' => 'bail|required|image',
        ]);

        $width = 400;
        $height = 400;

        Image::make($request->image)
             ->fit($width, $height, function ($constraint) {
                 $constraint->upsize();
             }, 'center')
             ->save(storage_path('app/public/thumbnail.jpg'));

        return '<img src="'.asset('storage/thumbnail.jpg').'"/>';
    }

    //图片转换成格式
    public function convert(Request $request)
    {
        $request->validate([
            'image' => 'bail|required|image',
        ]);
        $fileName = time().'.webp';
        $savePath = storage_path('app/public/').$fileName;
        Image::make($request->image)->save($savePath, 75);

        return '<img src="'.asset('storage/'.$fileName).'"/>';
    }
}
