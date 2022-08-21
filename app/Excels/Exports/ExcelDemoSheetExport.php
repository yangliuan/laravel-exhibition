<?php

namespace App\Excels\Exports;

use App\Excels\Exports\Sheets\ExcelDemoByIDSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelDemoSheetExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct()
    {
    }

    /**
     * 多张工作表
     * DOC:https://docs.laravel-excel.com/3.1/exports/multiple-sheets.html
     * @return array
     */
    public function sheets(): array
    {
        $total = 100000;
        $step = 10000;
        $start = 0;
        $end = 10000;

        for ($i = 0 ; $i< $total ; $i+=$step) {
            $sheets[] = new ExcelDemoByIDSheet($start, $end);
            $start += $step;
            $end += $step;
        }

        return $sheets;
    }
}
