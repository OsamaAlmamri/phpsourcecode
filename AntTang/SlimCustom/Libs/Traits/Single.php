<?php
/**
 * 单例
 * @package     single.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年4月5日
 */

namespace SlimCustom\Libs\Traits;

/**
 * 单例Trait
 * 
 * @author Jing <tangjing3321@gmail.com>
 */
trait Single {
    
    /**
     * 实例
     * 
     * @var object
     */
    public static $instance;
    
    /**
     * 单例
     * 
     * @param array $args
     * @return object
     */
    public static function single($args = [])
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        else {
            self::$instance = new static($args);
            return self::$instance;
        }
    }
}