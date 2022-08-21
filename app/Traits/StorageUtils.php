<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait StorageUtils
{
    /**
     * 设置文件存储路径，将url转换成path路径
     *
     * @param string $url 文件完整url
     * @param string $disk 文件存储磁盘类型，对应filesystem配置文件
     * @return string
     */
    public function setStorageUrl(string $url, string $disk = 'public')
    {
        //url格式
        if (strpos($url, 'http') === 0) {
            //public磁盘
            if (in_array($disk, ['public']) && strpos($url, 'storage/')) {
                return explode('storage/', $url)[1];
            } elseif ($disk === 'oss') {
                //oss磁盘
                return parse_url($url, PHP_URL_PATH);
            } else {
                return $url;
            }
        } else {
            return $url;
        }
    }

    /**
     * 获取文件存储路径，对应的url
     *
     * @param string $path 文件路径
     * @param string $disk 文件存储磁盘类型，对应filesystem配置文件
     * @return string
     */
    public function getStorageUrl(string $path, string $disk = 'public')
    {
        //url格式直接返回
        if (strpos($path, 'http') === 0) {
            return $path;
        } else {
            //为路径 则返回url
            return Storage::disk($disk)->url($path);
        }
    }
}
