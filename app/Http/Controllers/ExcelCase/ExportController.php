<?php

namespace App\Http\Controllers\ExcelCase;

use App\Excels\Exports\ExcelDemoCollectionExport;
use App\Excels\Exports\ExcelDemoQueryExport;
use App\Excels\Exports\ExcelDemoPictureCollectionExport;
use App\Excels\Exports\ExcelDemoPictureQueryExport;
use App\Excels\Exports\ExcelDemoSheetExport;
use App\Http\Controllers\Controller;
use App\Jobs\ExcelNotifyJob;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * 下载导出
     * DOC:https://docs.laravel-excel.com/3.1/exports/collection.html
     * @param Request $request
     * @return binary
     */
    public function download(Request $request)
    {
        \set_time_limit(0);
        //下载10万条需要内存
        \ini_set('memory_limit', '1024M');
        return Excel::download(new ExcelDemoCollectionExport, 'excel-demo-'.date('YmdHis').'.xlsx');
    }

    /**
     * 存储到磁盘
     * https://docs.laravel-excel.com/3.1/exports/collection.html
     *
     * @param Request $request
     * @return void
     */
    public function storeDisk(Request $request)
    {
        $file_name = 'excel-demo-'.date('YmdHis').'.xlsx';
        Excel::store(new ExcelDemoCollectionExport, $file_name, 'public');

        return redirect()->away(config('app.url').'/storage/'.$file_name);
    }

    /**
     * queue导出
     * DOC:https://docs.laravel-excel.com/3.1/exports/queued.html
     * DOC:https://docs.laravel-excel.com/3.1/exports/from-query.html
     *
     * @param Request $request
     * @return void
     */
    public function queue(Request $request)
    {
        //大数据导出，一定要使用from query 和 queue 导出，占用内存小,并且可以解决响应超时问题
        Excel::queue(new ExcelDemoQueryExport, 'excel-demo-'.date('YmdHis').'.xlsx', 'public');

        return redirect()->away('/horizon/jobs/completed');
    }

    /**
     * 字段导出图片 并响应下载
     * 导出图片非常耗内存，excel文件也会很大，没必要的时候不要导出图片
     * @param Request $request
     * @return void
     */
    public function downloadImages(Request $request)
    {
        //\set_time_limit(0);
        //\ini_set('memory_limit', '1024M');
        return Excel::download(new ExcelDemoPictureCollectionExport, 'excel-demo-'.date('YmdHis').'.xlsx');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function downloadMutilSheet(Request $request)
    {
        return Excel::download(new ExcelDemoSheetExport, 'excel-demo-'.date('YmdHis').'.xlsx');
    }

    /**
     * 字段导出图片 使用队列 并接受广播通知
     *
     * @param Request $request
     * @return void
     */
    public function queueImages(Request $request)
    {
        $file_name = 'excel-demo-'.date('YmdHis').\mt_rand(100000, 999999).'.xlsx';
        $disk = 'public';
        Excel::queue(new ExcelDemoPictureQueryExport(), $file_name, $disk)
        //导出成功后,使用任务链调用excel通知job
        //DOC:https://learnku.com/docs/laravel/8.5/queues/10395#dispatching-jobs
        //DOC:https://docs.laravel-excel.com/3.1/exports/queued.html#appending-jobs
        ->chain([
            new ExcelNotifyJob(compact('file_name', 'disk'))
        ]);

        return response()->json();
    }

    public function test(Request $request)
    {
    }
}
