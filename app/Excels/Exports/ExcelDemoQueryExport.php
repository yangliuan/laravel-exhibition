<?php
/**
 * https://phpspreadsheet.readthedocs.io/en/latest/references/features-cross-reference/
 */
namespace App\Excels\Exports;

use App\Models\ExcelDemo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Illuminate\Contracts\Translation\HasLocalePreference;
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

class ExcelDemoQueryExport implements WithTitle, FromQuery, WithCustomQuerySize, WithMapping, WithHeadings, WithColumnFormatting, WithColumnWidths, ShouldAutoSize, WithStyles, WithDrawings, WithEvents
{
    use Exportable;

    /**
     * 可以通过注入 其他参数 增加query查询条件 或者通过asGetter方式增加查询条件
     * DOC:https://docs.laravel-excel.com/3.1/exports/from-query.html#as-constructor-parameter
     * @param Request $request
     */
    public function __construct()
    {
    }

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
     * 通过使用 FromQuery 接口，我们可以为导出准备查询。在这个场景下，这个查询是分块执行的,适合大数据量导出
     *
     * DOC:https://docs.laravel-excel.com/3.1/exports/from-query.html
     * @return
     */
    public function query()
    {
        return ExcelDemo::query()->where('id', '>', 0);
    }

    /**
     * 自定义导出总数量，不需要限制数量时，不设置
     * DOC:https://docs.laravel-excel.com/3.1/exports/queued.html#when-to-use
     *
     * @return integer
     */
    public function querySize():int
    {
        return $this->query()->count();
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
     *
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
            }
        ];
    }
}
