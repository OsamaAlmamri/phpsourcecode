<?php
// +----------------------------------------------------------------------
// | YunCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://www.yunalading.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jabber <2898117012@qq.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 写入配置文件
 * @param $save_path 保存路径
 * @param array $config 新的配置数据
 */
function writeJsonConfig($save_path, $config = array()) {
    file_put_contents($save_path, json_encode($config));
}

/**
 * 获取系统主题列表
 * @return array
 */
function themes() {
    $dirs = array();
    $dir_path = APP_PATH . 'home' . DS . 'view';
    if (is_dir($dir_path)) {
        $dir = opendir($dir_path);
        while (($file = readdir($dir)) !== false) {
            //过滤读取的目录
            if ($file == '.' || $file == '..' || substr($file, 0, 1) == '.') {
                continue;
            }
            $dirs[] = $file;
        }
        closedir($dir);
    }
    return $dirs;
}

/**
 * 获取模板列表
 * @return array
 */
function template() {
    $dirs = array();
    $dir_path = APP_PATH . 'home' . DS . 'view' . DS .config("app.theme");
    if (is_dir($dir_path)) {
        $dir = opendir($dir_path);
        while (($file = readdir($dir)) !== false) {
            //过滤读取的目录
            if ($file == '.' || $file == '..' || substr($file, 0, 1) == '.') {
                continue;
            }
            $dirs[] = str_replace('.'.config('url_html_suffix'),'',$file);
        }
        closedir($dir);
    }
    return $dirs;
}

/*
 * 获取图片地址
 */
function get_image_url($path){
   $config = \think\Config::get('upload.image');
   if($config['uploadType'] == 'Server'){
       return DS.$path;
   }else{
       return $path;
   }
}

/**
 * PHP 的字节格式化函数：byteFormat
 * echo byteFormat(1073741824, "B", 0) . "\n";
 * echo byteFormat(1073741824, "KB", 0) . "\n";
 * echo byteFormat(1073741824, "MB") . "\n";
 * echo byteFormat(1073741824) . "\n";
 * echo byteFormat(1073741824, "TB", 10) . "\n";
 * echo byteFormat(1099511627776, "PB", 6) . "\n";
 * @param $bytes
 * @param string $unit
 * @param int $decimals
 * @return string
 */
function byteFormat($bytes, $unit = "", $decimals = 2) {
    $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
    $value = 0;
    if ($bytes > 0) {
        if (!array_key_exists($unit, $units)) {
            $pow = floor(log($bytes) / log(1024));
            $unit = array_search($pow, $units);
        }

        $value = ($bytes / pow(1024, floor($units[$unit])));
    }

    if (!is_numeric($decimals) || $decimals < 0) {
        $decimals = 2;
    }
    return sprintf('%.' . $decimals . 'f' . $unit, $value);
}

/**
 * 对象转数组打印(调试用)
 */
function dd($obj){
    dump(json_decode(json_encode($obj),true));
}
