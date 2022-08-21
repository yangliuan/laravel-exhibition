<?php

namespace App\Casts;

use App\Traits\StorageUtils;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FileUrlByDisk implements CastsAttributes
{
    use StorageUtils;

    /**
     * 根据磁盘 获取文件url 查询出的模型必须包含disk属性
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->getStorageUrl($value, $model->disk);
    }

    /**
     * 根据磁盘 设置文件路径 查询出的模型必须包含disk属性
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $this->setStorageUrl($value, $model->disk);
    }
}
