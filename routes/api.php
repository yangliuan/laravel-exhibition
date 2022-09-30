<?php

use App\Http\Controllers\Api\ControlController;
use App\Http\Controllers\Api\DisplayController;
use App\Http\Controllers\Doc\WordController;
use App\Http\Controllers\ExcelCase\ExportController;
use App\Http\Controllers\ExcelCase\ImportController;
use App\Http\Controllers\FilesCase\AliyunOssController;
use App\Http\Controllers\FilesCase\CompressController;
use App\Http\Controllers\FilesCase\DownloadController;
use App\Http\Controllers\FilesCase\UploadsController;
use App\Http\Controllers\FilesCase\UrlHandleController;
use App\Http\Controllers\ImageCase\InterventionController;
use App\Http\Controllers\Lbs\GeoIpController;
use App\Http\Controllers\Wechat\MiniProgramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('display')->group(function () {
    Route::post('login', [DisplayController::class, 'login']); //显示端设备登录
    Route::get('asset', [DisplayController::class, 'asset'])->middleware(['auth:device']); //获取资源
});

Route::prefix('control')->group(function () {
    Route::post('devices', [ControlController::class, 'allDevice']); //控制全部设备
    Route::post('device/{devId}', [ControlController::class, 'device']); //控制单个设备
});

Route::prefix('files')->group(function () {
    Route::post('upload', [UploadsController::class, 'upload']); //上传文件
    Route::get('download', [DownloadController::class, 'store']); //下载文件
    Route::post('download-zip', [CompressController::class, 'downloadZip']); //批量上传文件，压缩成zip下载
    Route::apiResource('oss', AliyunOssController::class); //oss
    Route::apiResource('url-handle', UrlHandleController::class); //url处理方式
});

Route::prefix('excel')->group(function () {
    Route::prefix('export')->group(function () {
        Route::any('download', [ExportController::class, 'download']); //下载导出
        Route::any('store', [ExportController::class, 'storeDisk']); //存储到磁盘
        Route::any('queue', [ExportController::class, 'queue']); //队列导出
        Route::any('download-sheets', [ExportController::class, 'downloadMutilSheet']); //导出多个工作表
        Route::any('download-images', [ExportController::class, 'downloadImages']); //下载导出图片
        Route::any('queue-images', [ExportController::class, 'queueImages']); //队列导出图片并发送广播
        Route::any('test', [ExportController::class, 'test']);
    });
    Route::prefix('import')->group(function () {
        Route::post('normal-collection', [ImportController::class, 'normalCollection']); //使用集合导入
        Route::post('normal-model', [ImportController::class, 'normalModel']); //使用模型导入
        Route::post('queue-row', [ImportController::class, 'queueRow']); //使用Row导入
    });
});

Route::prefix('doc')->group(function () {
    Route::group(['prefix' => 'word'], function () {
        Route::post('word-to-html', [WordController::class, 'wordConvertHtml']); //word转html
        Route::any('html-to-word', [WordController::class, 'htmlConvertWord']); //转换成word
    });
});

Route::prefix('image')->group(function () {
    Route::group(['prefix' => 'intervertion'], function () {
        Route::any('water-marker', [InterventionController::class, 'waterMarker']); //生成水印
        Route::any('picword-watermark', [InterventionController::class, 'picwordWatermark']); //图文混合水印
        Route::any('drawtext', [InterventionController::class, 'drawText']); //基于背景图片添加图片和文字绘制,海报
        Route::any('mask-cut-round', [InterventionController::class, 'maskCutRound']); //使用图层蒙版裁切成圆形,其它图形原理相同
        Route::any('draw-graphics', [InterventionController::class, 'drawGraphics']); //海报绘制圆形头像
        Route::any('response-img', [InterventionController::class, 'responseImg']); //绘制图形并响应为图片
        Route::any('tile', [InterventionController::class, 'tile']); //平铺填充图片
        Route::any('readbase64', [InterventionController::class, 'readBase64']); //读取base64格式图片
        Route::any('compress', [InterventionController::class, 'compress']); //压缩图片
        Route::any('thumbnail', [InterventionController::class, 'thumbnail']); //缩略图
        Route::any('convert', [InterventionController::class, 'convert']); //转换为webp
    });
});

Route::prefix('wechat')->group(function () {
    Route::prefix('miniprogram')->group(function () {
        Route::get('qrcode', [MiniProgramController::class, 'qrCode']); //小程序码
    });
});

Route::prefix('lbs')->group(function () {
    Route::prefix('geoip')->group(function () {
        Route::get('demo', [GeoIpController::class, 'demo']);
    });
});
