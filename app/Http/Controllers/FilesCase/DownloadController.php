<?php

namespace App\Http\Controllers\FilesCase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'storage_path' => 'bail|required|string',
            'disk' => 'bail|nullable|string',
        ]);
        $realPath = Storage::disk($request->input('disk') ?? 'public')->path($request->input('storage_path'));

        return response()->download($realPath)->deleteFileAfterSend();
    }
}
