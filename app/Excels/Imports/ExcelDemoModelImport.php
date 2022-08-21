<?php

namespace App\Excels\Imports;

use App\Models\ExcelDemo;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ExcelDemoModelImport implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns, WithChunkReading, WithBatchInserts
{
    use RemembersRowNumber;

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
     * 模型插入
     * DOC:https://docs.laravel-excel.com/3.1/imports/model.html
     * @param array $rows
     *
     * @return array
     */
    public function model(array $rows)
    {
        //记住行号，仅支持ToModel,可以用来记录第几行报错
        //DOC:https://docs.laravel-excel.com/3.1/imports/chunk-reading.html#keep-track-of-the-row-number
        $current_row_number = $this->getRowNumber();
        Log::info('行号:'. $current_row_number);

        //过滤空行
        //DOC:https://docs.laravel-excel.com/3.1/imports/model.html#skipping-rows
        if (!isset($row[0])) {
            return null;
        }

        return new ExcelDemo([
            'str_column' => $rows[0]['字符串字段'],
            'int_column' => $rows[0]['整数字段'],
            'float_column' => $rows[0]['浮点数字段'],
            'pic_column' => $rows[0]['图片'],
            'text_column' => $rows[0]['文本字段'],
        ]);
    }

    /**
     * 更新插入，不存在创建，存在更新，仅支持toModel模式
     *
     * DOC:https://docs.laravel-excel.com/3.1/imports/model.html#upserting-models
     * @return void
     */
    public function uniqueBy()
    {
        return 'int_column';
    }

    /**
     * 存在数据更新时，指定可以更新的字段
     * 必须配合 WithUpserts interface 一起使用 仅支持toModel模式
     * DOC:https://docs.laravel-excel.com/3.1/imports/model.html#upserting-with-specific-columns
     *
     * @return array
     */
    public function upsertColumns()
    {
        return [
            'str_column','float_column','pic_column','text_column'
        ];
    }

    /**
     * 分块读取，大数据量必须要用，否则会占用很多内存导致内存溢出
     * DOC:https://docs.laravel-excel.com/3.1/imports/chunk-reading.html
     *
     * @return integer
     */
    public function chunkSize(): int
    {
        return 23560;
    }

    /**
     * 批量插入，仅支持toModel,最佳实践和chunkSize一起使用，并设置相同的数值
     * DOC:
     *
     * @return integer
     */
    public function batchSize(): int
    {
        return 23560;
    }
}
