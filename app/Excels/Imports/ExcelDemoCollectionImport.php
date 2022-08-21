<?php

namespace App\Excels\Imports;

use App\Models\ExcelDemo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ExcelDemoCollectionImport implements ToCollection, WithChunkReading, WithHeadingRow
{
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
     * 集合导入适用于，有关联数据需要插入多表，或者需要做特殊处理的情况
     *
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $handle_method = 'one_by_one_upsert';

        if ($handle_method == 'one_by_one') {
            //一条接一条导入，适用于复杂关联表的场景
            foreach ($rows as $row) {
                ExcelDemo::create([
                    'str_column' => $row['字符串字段'],
                    'int_column' => $row['整数字段'],
                    'float_column' => $row['浮点数字段'],
                    'pic_column' => $row['图片'],
                    'text_column' => $row['文本字段'],
                ]);
            }
        } elseif ($handle_method == 'batch_insert') {
            //在集合中手动处理批量写入,使用此方式需要在 config/excel.php配置中关闭事务配置,
            // transactions=>['db'=>null]否则可能会导致数据库长事务阻塞

            //标题头和数据库表字段映射
            $column_map = [
                '字符串字段'=>'str_column','整数字段'=>'int_column','浮点数字段'=>'float_column','图片'=>'pic_column','文本字段'=>'text_column'
            ];
            $rows = $this->covertRowData($rows, $column_map);
            //写入数据，注意chunkSize的数值不能太大，导致inset into语句 数据包过大报错
            ExcelDemo::insert($rows);
        } elseif ($handle_method == 'one_by_one_upsert') {
            //一条接一条导入，存在数据更新
            foreach ($rows as $row) {
                //使用模型更新或创建方法
                ExcelDemo::updateOrCreate(
                    //以int_column为标识
                    ['int_column'=>$row['整数字段']],
                    //更新字段
                    [
                        'str_column' => $row['字符串字段'],
                        'int_column' => $row['整数字段'],
                        'float_column' => $row['浮点数字段'],
                        'pic_column' => $row['图片'],
                        'text_column' => $row['文本字段']
                    ]
                );
            }
        }
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
     * 自定义方法取出需要的数据
     *
     * @param collect $rows
     * @param array $column_map 标题头和表字段映射
     *
     * @return array
     */
    public function covertRowData($rows, $column_map)
    {
        $result = [];
        $i = 0;
        $now = now();

        foreach ($rows as $key => & $row) {
            foreach ($column_map as $key => $value) {
                $result[$i][$value] = $row[$key];
            }

            $result[$i]['created_at'] = $now;
            $result[$i]['updated_at'] = $now;
            $i++;
        }

        return $result;
    }
}
