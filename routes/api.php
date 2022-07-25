<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DisplayController;
use App\Http\Controllers\Api\ControlController;

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
    Route::post('login', [DisplayController::class,'login']);//显示端设备登录
    Route::get('asset', [DisplayController::class,'asset'])->middleware(['auth:device','scope:display']);//获取资源
});

Route::prefix('control')->group(function () {
    Route::any('send-instruction', [ControlController::class,'sendInstruction']); //发送控制指令
});
