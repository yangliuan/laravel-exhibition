<?php

namespace App\Http\Controllers\FilesCase;

use App\Http\Controllers\Controller;
use App\Models\ImageDemo;
use App\Models\PictureDemo;
use Illuminate\Http\Request;

class UrlHandleController extends Controller
{
    public function index(Request $request)
    {
        $pic_demos = PictureDemo::get();
        $image_demos = ImageDemo::get();

        return compact('pic_demos', 'image_demos');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_demo_file'=>'bail|required|url',
            'image_demo_file_group'=>'bail|required|array',
            'picture_demo_disk'=>'bail|required|in:public,oss',
            'picture_demo_file'=>'bail|required|url'
        ]);

        //存储路径值为url，测试自定义类型转换，根据disk判断
        PictureDemo::create([
            'disk' => $request->input('picture_demo_disk'),
            'path' => $request->input('picture_demo_file')
        ]);

        //存储路径值为url,测试自定已类型转换，根据默认驱动判断
        ImageDemo::create([
            'path' => $request->input('image_demo_file'),
            'path_group' => $request->input('image_demo_file_group'),
        ]);

        return response()->json();
    }

    public function destroy(Request $request, $id)
    {
        $picture_demo = PictureDemo::first();
        $picture_demo->delete();

        $image_demo = ImageDemo::first();
        $image_demo->delete();

        return response()->json();
    }
}
