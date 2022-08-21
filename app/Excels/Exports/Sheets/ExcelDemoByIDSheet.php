<?php

namespace App\Excels\Exports\Sheets;

use App\Models\ExcelDemo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExcelDemoByIDSheet implements FromQuery, WithMapping, WithHeadings, WithTitle
{
    protected $start;
    protected $end;

    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function query()
    {
        return ExcelDemo::query()->where('id', '>', $this->start)->where('id', '<=', $this->end);
    }

    /**
     * 设置工作表标题
     * DOC:https://docs.laravel-excel.com/3.1/exports/multiple-sheets.html#sheet-classes
     * @return string
     */
    public function title(): string
    {
        return $this->start.'——'.$this->end;
    }

    /**
    * excel表头
    * DOC:https://docs.laravel-excel.com/3.1/exports/mapping.html#adding-a-heading-row
    * @return array
    */
    public function headings(): array
    {
        return [
            '字符串字段',
            '整数字段',
            '浮点数字段',
            '图片',
            '文本字段',
            '创建时间',
        ];
    }

    /**
     * 表头 对应数据映射
     * DOC:https://docs.laravel-excel.com/3.1/exports/mapping.html#mapping-rows
     * @param obj $book
     * @return array
     */
    public function map($demo): array
    {
        return [
            $demo->str_column,
            $demo->int_column,
            $demo->float_column,
            $demo->pic_column,
            $demo->text_column,
            $demo->created_at,
        ];
    }
}
