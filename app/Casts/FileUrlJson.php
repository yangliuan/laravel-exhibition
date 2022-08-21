<?php
/**
 * 文件存储 url和路径 自定义类型转换
 * 固定数量 存储json字符串
 */
namespace App\Casts;

use App\Traits\StorageUtils;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FileUrlJson implements CastsAttributes
{
    use StorageUtils;

    protected $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $value = json_decode($value, true);

        if (!is_array($value)) {
            return [];
        }

        //批量获取url
        $value = array_map(function ($item) {
            return $this->getStorageUrl($item, $this->disk);
        }, $value);

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (in_array($value, [null, '', 0])) {
            $value = [];
        } elseif (!is_array($value)) {
            $value = compact($value);
        }

        //批量设置路径
        $value = array_map(function ($item) {
            return $this->setStorageUrl($item, $this->disk);
        }, $value);

        return json_encode($value);
    }
}
