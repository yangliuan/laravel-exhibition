<?php
/**
 * 文件存储 url和路径 自定义类型转换
 */

namespace App\Casts;

use App\Traits\StorageUtils;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FileUrl implements CastsAttributes
{
    use StorageUtils;

    protected $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    /**
     * 根据文件系统默认驱动 获取文件url
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->getStorageUrl($value, $this->disk);
    }

    /**
     * 根据文件系统默认驱动 设置文件路径
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $this->setStorageUrl($value, $this->disk);
    }
}
