<?php

namespace App\Excels\Exports;

use App\Models\ExcelDemo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelDemoPictureQueryExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    public function __construct()
    {
    }

    /**
    * 通过使用 FromQuery 接口，我们可以为导出准备查询。在这个场景下，这个查询是分块执行的,适合大数据量导出
    *
    * DOC:https://docs.laravel-excel.com/3.1/exports/from-query.html
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return ExcelDemo::query()
            ->where('id', '<=', 10000);
    }

    /**
    * excel表头
    * DOC:https://docs.laravel-excel.com/3.1/exports/mapping.html#adding-a-heading-row
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            '图片',
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
            $demo->id,
            '',
        ];
    }

    /**
     * 将字段url自动转为图片
     *
     * REF:https://learnku.com/laravel/t/49171
     * REF:https://laravelquestions.com/2021/03/01/export-images-with-laravel-excel/
     * DOC:https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#add-a-drawing-to-a-worksheet
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                //设置图片列的宽度等于图片宽度，和取消自动设置尺寸
                $event->sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(5);
                $count = $this->query()->count();//列数量

                //基于行数迭代
                for ($i=0;$i<$count;$i++) {
                    //设置行高
                    $event->sheet->getRowDimension($i+2)->setRowHeight(33);
                }

                //遍历数据 取图片字段并设置位置生成图片
                foreach ($this->query()->get() as $key => $value) {
                    $drawing = new Drawing();
                    $drawing->setName('image');
                    $drawing->setDescription('image');
                    //如果图片是远程地址需要先下载到本地，生成完成后删除
                    $drawing->setPath(public_path($value->pic_column));
                    //设置图片高度
                    $drawing->setHeight(33);
                    //x轴偏移量
                    $drawing->setOffsetX(5);
                    //y轴偏移量
                    $drawing->setOffsetY(5);
                    //设置列和行
                    $drawing->setCoordinates('B'.($key+2));
                    //
                    $drawing->setWorksheet($event->sheet->getDelegate());
                }
            }
        ];
    }
}
