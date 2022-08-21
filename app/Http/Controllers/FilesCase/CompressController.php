<?php

namespace App\Http\Controllers\FilesCase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Zip;

class CompressController extends Controller
{
    public function downloadZip(Request $request)
    {
        $request->validate([
            'files' => 'bail|required|array',
            'files.*' => 'bail|nullable|required_with:files|file',
        ]);

        $zip_arr = [];

        foreach ($request->file('files') as $key => $file) {
            $zip_arr[] = $file->path();
        }

        //dd($zip_arr);
        return Zip::create('files.zip', $zip_arr);
    }
}
