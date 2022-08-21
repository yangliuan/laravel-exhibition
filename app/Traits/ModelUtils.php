<?php
namespace App\Traits;

trait ModelUtils
{
    public function deleteFile($file_column, $disk = '')
    {
        if (!$file_column) {
            return false;
        }

        if (!is_array($file_column)) {
            $file_column = compact('file_column');
        }

        $disk = $disk ? $disk : config('filesystems.default');
    }
}
