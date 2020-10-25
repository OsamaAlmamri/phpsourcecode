<?php
namespace Zan\Framework\Foundation\Core;

use Zan\Framework\Foundation\Exception\System\FileNotFound;

class ClassLoader
{
    private static $loadedMap = [];

    public static function load($file)
    {
        $fileHash = md5($file);
        if (isset(self::$loadedMap[$fileHash])) {
            return true;
        }

        if (!file_exists($file)) {
            throw new FileNotFound('No such file:' . $file);
        }

        self::$loadedMap[$fileHash] = 1;
        require $file;
    }

    public static function loadFiles(array $files)
    {
        if (!$files) return false;

        foreach ($files as $file) {
            self::load($file);
        }

        return true;
    }
}