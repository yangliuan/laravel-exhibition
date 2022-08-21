<?php

namespace App\Http\Controllers\FilesCase;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PictureDemo;

class UploadsController extends Controller
{
    /**
     * 上传文件，可选择磁盘
     *
     * @param Request $request
     * @return json
     */
    public function upload(Request $request)
    {
        $request->validate([
            'disk'=>'bail|required|in:public,oss',
            'file'=>'bail|required|file',
        ]);

        $subpath = date('Y').'/'.date('m').'/'.date('d');
        $disk = $request->input('disk') ?? config('filesystems.default');
        $storage = Storage::disk($disk);

        try {
            if ($savePath = $storage->put($subpath, $request->file('file'), 'public')) {
                return response()->json(['url' => $storage->url($savePath), 'path' => $savePath]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['file' => [$th->getMessage()]]);
        }
    }
}
