<?php

namespace App\Excels\Imports;

use App\Models\ExcelDemo;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class ExcelDemoRowPictureImport implements OnEachRow, WithHeadingRow, WithEvents
{
    use Importable;

    public $disk;

    public $excel_path;

    public $drawing_collection;

    public function __construct(string $disk, string $excel_path)
    {
        $this->disk = $disk;
        $this->excel_path = $excel_path;

        $full_path = Storage::disk($disk)->path($excel_path);
        $this->drawing_collection = $this->loadDrawingCollection($full_path);
    }

    /**
     * 标题头行是第几行，可以指定数字，返回数字一定要和标题头的行数一致
     * DOC:https://docs.laravel-excel.com/3.1/imports/heading-row.html
     *
     * @return integer
     */
    public function headingRow(): int
    {
        //第一行是标题行
        return 1;
    }

    /**
     * 逐行处理
     * DOC:https://docs.laravel-excel.com/3.1/imports/model.html#handling-persistence-on-your-own
     *
     * 导入图片
     * REF:https://laracasts.com/discuss/channels/laravel/cant-import-images-using-laravel-excel
     * DOC:https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#reading-images-from-a-worksheet
     */
    public function onRow(Row $row)
    {
        $row_index = $row->getIndex();
        $row = $row->toArray();
        //计算，数据行索引对应的图片集合索引
        $drawing_index = $row_index - $this->headingRow() - 1;
        ExcelDemo::create([
            'int_column'=>$row['整数'],
            'pic_column'=>$this->storeExcelImage($drawing_index),
        ]);
    }

    /**
     * 分块读取，大数据量必须要用，否则会占用很多内存导致内存溢出
     * DOC:https://docs.laravel-excel.com/3.1/imports/chunk-reading.html
     *
     * @return integer
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * 加载图片集合
     *
     * @param string $full_path
     * @return array
     */
    public function loadDrawingCollection(string $full_path)
    {
        $spreadsheet = IOFactory::load($full_path);

        return $spreadsheet->getActiveSheet()->getDrawingCollection();
    }

    /**
     * 保存excel图片
     *
     * @param integer $drawing_index 每行数据对应的图片集合索引
     * @return string
     */
    public function storeExcelImage(int $drawing_index)
    {
        if (!isset($this->drawing_collection[$drawing_index])) {
            return '';
        }

        $drawing = $this->drawing_collection[$drawing_index];

        if ($drawing instanceof MemoryDrawing) {
            ob_start();
            call_user_func(
                $drawing->getRenderingFunction(),
                $drawing->getImageResource()
            );
            $image_contents = ob_get_contents();
            ob_end_clean();
            switch ($drawing->getMimeType()) {
                case MemoryDrawing::MIMETYPE_PNG:
                    $extension = 'png';
                    break;
                case MemoryDrawing::MIMETYPE_GIF:
                    $extension = 'gif';
                    break;
                case MemoryDrawing::MIMETYPE_JPEG:
                    $extension = 'jpg';
                    break;
            }
        } else {
            $zipReader = fopen($drawing->getPath(), 'r');
            $image_contents = '';

            while (!feof($zipReader)) {
                $image_contents .= fread($zipReader, 1024);
            }

            fclose($zipReader);
            $extension = $drawing->getExtension();
        }

        $my_file_name = 'excel_upload/'. md5(time().mt_rand(100000, 999999)) . '.' . $extension;
        $put_res = Storage::disk($this->disk)->put($my_file_name, $image_contents);

        if ($put_res) {
            return $my_file_name;
        }

        return '';
    }

    /**
     * 手动注册事件
     * DOC:https://docs.laravel-excel.com/3.1/imports/extending.html#events
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                Storage::disk($this->disk)->delete($this->excel_path);
            }
        ];
    }
}
