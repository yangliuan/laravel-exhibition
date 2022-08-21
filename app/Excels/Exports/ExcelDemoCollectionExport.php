<?php
/**
 * https://phpspreadsheet.readthedocs.io/en/latest/references/features-cross-reference/
 */
namespace App\Excels\Exports;

use App\Models\ExcelDemo;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelDemoCollectionExport implements WithTitle, FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithColumnWidths, ShouldAutoSize, WithStyles, WithDrawings, WithEvents
{
    //DOC:https://docs.laravel-excel.com/3.1/exports/exportables.html
    use Exportable;

    /**
     * Excel 底部工作表 名称
     * DOC: https://docs.laravel-excel.com/3.1/exports/multiple-sheets.html#sheet-classes
     * @return string
     */
    public function title(): string
    {
        return '月份 '.date('m');
    }

    /**
     * 执行查询获取数据集合 适合小数据量导出，因为集合是数据结果,数据多的时候，会占用更多内存
     *
     * DOC:https://docs.laravel-excel.com/3.1/exports/collection.html
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $demos = ExcelDemo::query()
            ->where('id', '>', 0)
            ->limit(2000)
            //使用cursor可以有效降低内存使用,不导出图片的情况下，10万条测试没有问题
            //不能用于队列导出
            //DOC:https://learnku.com/laravel/t/42018#reply208957
            ->cursor();

        return $demos;
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
            $demo->created_at,//Date::dateTimeToExcel($demo->created_at),
        ];
    }

    /**
     * 字段 格式化数据类型
     * DOC:https://docs.laravel-excel.com/3.1/exports/column-formatting.html#formatting-columns
     * 其它类型查看 NumberFormat类的常量
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            //'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * 字段宽度设定，默认按ShouldAutoSize接口自动适应宽度
     * DOC:https://docs.laravel-excel.com/3.1/exports/column-formatting.html#column-widths
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            //'A'=>100,
            //'B'=>100,
            //'C'=>100,
            'D'=>10,
            'E'=>300,
            'F'=>20,
        ];
    }

    /**
     * 设置样式 版本v3.1.21 以后
     * DOC:https://docs.laravel-excel.com/3.1/exports/column-formatting.html#styling
     * DOC:https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#styles
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            //第一行
            1 => [
                //字体设置
                'font' => [
                    //设置粗体
                    'bold' => true ,
                    //字体颜色
                    'color'=>[
                        'argb'=> Color::COLOR_RED
                    ]
                ],
                //水平居中
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                //背景填充
                'fill' => [
                    //填充方式线性
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    //前景色
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    //背景色
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ],

            //第一列
            'A' => [
                //字体设置
                'font' => [
                    //设置斜体
                    'italic' => true,
                    //颜色设置
                    'color' => [
                        'argb'=> Color::COLOR_GREEN
                    ],
                    'size' => 16,
                ],
                //水平居中
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                //背景填充，前景色和背景色一致，就是设置背景颜色
                'fill' => [
                    'startColor' => [
                        'argb' => Color::COLOR_RED,
                    ],
                    'endColor' => [
                        'argb' => Color::COLOR_RED,
                    ]
                ],
            ]
        ];
    }

    /**
     * 插入图片到指定位置
     * DOC:https://docs.laravel-excel.com/3.1/exports/drawings.html#adding-a-single-drawing
     * @return void
     */
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('img');
        $drawing->setDescription('img');
        $drawing->setPath(public_path('avatar.jpeg'));
        $drawing->setHeight(33);
        $drawing->setCoordinates('A1');//表格列和对应行数

        return $drawing;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
            }
        ];
    }
}
