<?php

namespace App\Http\Controllers\FilesCase;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AliyunOssController extends Controller
{
    /**
     * 上传文件
     * OSSDOC:https://github.com/iiDestiny/laravel-filesystem-oss
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $request->validate([
            'file'=>'bail|required|file',
        ]);
        $path = 'test/'.date('Y-m-d');
        $disk = Storage::disk('oss');
        $file_name = $disk->put($path, $request->file('file'));

        return response()->json(['file_name'=>$file_name,'url'=>$disk->url($file_name)]);
    }
}
