<?php

namespace App\Http\Controllers\ExcelCase;

use App\Excels\Imports\ExcelDemoCollectionImport;
use App\Excels\Imports\ExcelDemoModelImport;
use App\Excels\Imports\ExcelDemoRowPictureImport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function normalCollection(Request $request)
    {
        $request->validate([
            'excel'=> [
                'bail', 'required',
                function ($attribute, $value, $fail) {
                    if (
                        $value->getClientOriginalExtension() !== 'xlsx'
                        ||
                        ! in_array($value->getClientMimeType(), [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/wps-office.xlsx',
                            'application/wps-office.xls',
                        ])
                    ) {
                        return $fail('不支持的文件类型,请使用xlsx后缀的excel文件');
                    }
                },
            ],
        ]);
        \set_time_limit(0);
        \ignore_user_abort(true);
        $pathInfo = pathinfo($request->file('excel')->getClientOriginalName());
        $path = $request->file('excel')->storeAs('excel', $pathInfo['filename'].time().'.'.$pathInfo['extension'], 'public');
        Excel::import(new ExcelDemoCollectionImport, $path, 'public');
        Storage::disk('public')->delete($path);

        return response()->json();
    }

    public function normalModel(Request $request)
    {
        $request->validate([
            'excel'=> [
                'bail', 'required', 'file',
            ],
        ]);

        \set_time_limit(0);
        \ignore_user_abort(true);
        $pathInfo = pathinfo($request->file('excel')->getClientOriginalName());
        $path = $request->file('excel')->storeAs('excel', $pathInfo['filename'].time().'.'.$pathInfo['extension'], 'public');
        Excel::import(new ExcelDemoModelImport, $path, 'public');
        Storage::disk('public')->delete($path);

        return response()->json();
    }

    public function queueRow(Request $request)
    {
        $request->validate([
            'excel'=> [
                'bail', 'required', 'file',
            ],
        ]);

        \set_time_limit(0);
        \ignore_user_abort(true);
        $pathInfo = pathinfo($request->file('excel')->getClientOriginalName());
        $disk = 'public';
        $path = $request->file('excel')->storeAs('excel', $pathInfo['filename'].time().'.'.$pathInfo['extension'], $disk);
        Excel::import(new ExcelDemoRowPictureImport($disk, $path), $path, $disk);

        return response()->json();
    }
}
