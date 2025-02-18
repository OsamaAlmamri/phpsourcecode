<?php
/*
 *  @author myf
 *  @date 2014-11-15 
 *  @Description myfmvc http请求类
 *  @web http://www.minyifei.cn
 */
namespace Myf\Mvc;

class Log {

    const EMERG = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR = 'ERR';  // 一般错误: 一般性错误
    const WARN = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO = 'INFO';  // 信息: 程序输出信息
    const DEBUG = 'DEBUG';  // 调试: 调试信息
    const SQL = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效
    // 日志记录方式
    const SYSTEM = 0;
    const MAIL = 1;
    const TCP = 2;
    const FILE = 3;

    // 日志信息
    static $log = array();
    // 日期格式
    static $format = '[ c ]';
    static $log_level = "EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL";
    static $log_size = 2097152;

    /**
      +----------------------------------------------------------
     * 记录日志 并且会过滤未经设置的级别
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param boolean $record  是否强制记录
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     */
    public static function record($message, $level = self::ERR, $record = false) {
        if ($record || strpos(self::$log_level, $level)) {
            $now = date(self::$format);
            self::$log[] = "{$now} {$level}: {$message}\r\n";
        }
    }

    /**
      +----------------------------------------------------------
     * 日志保存
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param integer $type 日志记录方式
     * @param string $destination  写入目标
     * @param string $extra 额外参数
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     */
    public static function save($type = self::FILE, $destination = '', $extra = '') {
        if (empty($destination)){
            $destination = LOG_PATH . date('y_m_d') . ".log";
        }
        if (self::FILE == $type) { // 文件方式记录日志信息
            //检测日志文件大小，超过配置大小则备份日志文件重新生成
            if (is_file($destination) && floor(self::$log_size) <= filesize($destination)){
                rename($destination, dirname($destination) . '/' . time() . '-' . basename($destination));
            }
        }
        error_log(implode("", self::$log), $type, $destination, $extra);
        // 保存后清空日志缓存
        self::$log = array();
    }

    /**
      +----------------------------------------------------------
     * 日志直接写入
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param integer $type 日志记录方式
     * @param string $destination  写入目标
     * @param string $extra 额外参数
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     */
    public static function write($message, $level = self::DEBUG, $type = self::FILE, $destination = '', $extra = '') {
        $now = date(self::$format);
        if (empty($destination)){
            $destination = LOG_PATH . date('y_m_d') . ".log";
        }
        if (self::FILE == $type) { // 文件方式记录日志
            //检测日志文件大小，超过配置大小则备份日志文件重新生成
            if (is_file($destination) && floor(self::$log_size) <= filesize($destination)){
                rename($destination, dirname($destination) . '/' . time() . '-' . basename($destination));
            }
        }
        error_log("{$now} {$level}: {$message}\r\n", $type, $destination, $extra);
    }

}
