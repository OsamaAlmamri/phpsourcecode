<?php
include('_pre.php');
include('Common/Common/function.php');
include('helper.php');
include('fill_function.php');
include('filter_function.php');

/**
 * 生成验证码
 * @param  array  $config
 * @return
 */
function create_verify_code(array $config) {
    $Verify = new \Think\Verify($config);
    return $Verify->entry();
}

/**
 * 检查验证码
 * @param  string $code
 * @param  int $verify_code_id
 * @return boolean
 */
function check_verify_code($code, $verify_code_id = '') {
    $Verify = new \Think\Verify();
    return $Verify->check($code, $verify_code_id);
}

/**
 * 格式化的文件大小
 * @param  int $bytes
 * @return string
 */
function bytes_format($bytes) {
    // 单位
    $unit = array(' B', ' KB', ' MB',
                  ' GB', ' TB', ' PB',
                  ' EB', ' ZB', ' YB');

    // bytes的对数
    $log_bytes = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $log_bytes), 2) . $unit[$log_bytes];
}



/**
 * 生成随机码
 * @param  int $length
 * @param  int $type
 * @return string
 */
function rand_code($length, $type) {
    $rand_factor = array("0123456789",
                        "abcdefghijklmnopqrstuvwxyz",
                        "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                        "~@#$%^&*(){}[]|");

    if (($type < 0 && $type != -1) || $type > 4) {
        $type = 0;
    }

    if (0 == $type) {
        array_pop($rand_factor);
        $rand_src = implode("", $rand_factor);
    } else if (-1 == $type) {
        $rand_src = implode("", $rand_factor);
    } else {
        $rand_src = $rand_factor[$type];
    }

    $code = '';
    $count = strlen($rand_src) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $rand_src[rand(0, $count)];
    }

    return $code;
}

/**
 * 删除目录或者文件
 * @param  string  $path
 * @param  boolean $is_del_dir
 * @return fixed
 */
function del_dir_or_file($path, $is_del_dir = FALSE) {
    $handle = opendir($path);
    if ($handle) {
        // $path为目录路径
        while (false !== ($item = readdir($handle))) {
            // 除去..目录和.目录
            if ($item != '.' && $item != '..') {
                if (is_dir("$path/$item")) {
                    // 递归删除目录
                    del_dir_or_file("$path/$item", $is_del_dir);
                } else {
                    // 删除文件
                    unlink("$path/$item");
                }
            }
        }
        closedir($handle);
        if ($is_del_dir) {
            // 删除目录
            return rmdir($path);
        }
    }else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return false;
        }
    }
}




/**
 * 检查是否包含特殊字符
 * @param  string  $subject 需要检查的字符串
 * @return boolean          是否包含
 */
function hasSpecialChar($subject) {
    $pattern = "/^(([^\^\.<>%&',;=?$\"':#@!~\]\[{}\\/`\|])*)$/";

    if (preg_match($pattern, $subject)) {
        return false;
    }

    return true;
}

/**
 * 是否整数
 * @param  mixed   $var
 * @return boolean
 */
function isint($var) {
    return (preg_match('/^\d*$/', $var) == 1);
}

/**
 * 是否浮点型
 * @param  mixed   $var 需要检查的值
 * @return boolean
 */
function isdouble($var) {
    return (preg_match('/^[+-]?(\d*\.\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/', $var));
}

/**
 * 检验是否有效日期
 * @param  string  $date    需要检验的日期
 * @param  array   $formats 有效的日期格式
 * @return boolean
 */
function is_valid_date($date, $formats = array("Y-m-d", "Y/m/d")) {
    $unixtime = strtotime($date);
    if (!$unixtime) {
        return false;
    }

    foreach ($formats as $format) {
        if (date($format, $unixtime) == $date) {
            return true;
        }
    }

    return false;
}

/**
 * 得到指定值在数组中的位置，未找到返回false
 * @param  array  $search 被查找的数组
 * @param  mixed  $target 目标值
 * @return mixed
 */
function array_pos(array $search, $target) {
    $i = 0;
    foreach ($search as $item) {
        if ($item == $target) {
            return $i;
        }

        $i += 1;
    }

    return false;
}

/**
 * 只对字符串进行trim
 * @param  mixed $val 需要trim的值
 * @return mixed
 */
function trim_value($val) {
    if (is_string($val)) {
        return trim($val);
    }

    return $val;
}

/**
 * 得到操作系统信息
 * @return string
 */
function get_system() {
    $sys = $_SERVER['HTTP_USER_AGENT'];

    if (stripos($sys, "NT 6.1")) {
        $os = "Windows 7";
    } else if (stripos($sys, "NT 6.0")) {
        $os = "Windows Vista";
    }  else if (stripos($sys, "NT 5.1")) {
        $os = "Windows XP";
    } else if (stripos($sys, "NT 5.2")) {
        $os = "Windows Server 2003";
    } else if (stripos($sys, "NT 5")) {
        $os = "Windows 2000";
    } else if (stripos($sys, "NT 4.9")) {
        $os = "Windows ME";
    } else if (stripos($sys, "NT 4")) {
        $os = "Windows NT 4.0";
    } else if (stripos($sys, "98")) {
        $os = "Windows 98";
    } else if (stripos($sys, "95")) {
        $os = "Windows 95";
    } else if (stripos($sys, "Mac")) {
        $os = "Mac";
    } else if (stripos($sys, "Linux")) {
        $os = "Linux";
    } else if (stripos($sys, "Unix")) {
        $os = "Unix";
    } else if (stripos($sys, "FreeBSD")) {
        $os = "FreeBSD";
    } else if (stripos($sys, "SunOS")) {
        $os = "SunOS";
    } else if (stripos($sys, "BeOS")) {
        $os = "BeOS";
    } else if (stripos($sys, "OS/2")) {
        $os = "OS/2";
    } else if (stripos($sys, "PC")) {
        $os = "Macintosh";
    } else if(stripos($sys, "AIX")) {
        $os = "AIX";
    } else {
        $os = "未知操作系统";
    }

    return $os;
}

