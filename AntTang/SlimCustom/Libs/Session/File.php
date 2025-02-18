<?php
/**
 * @package     File.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月18日
 */

namespace SlimCustom\Libs\Session;

/**
 * session文件驱动
 * 
 * @author Jing Tang <tangjing3321@gmail.com>
 */
class File
{
    /**
     * 开启session
     * 
     * @param array $config
     * @return \SlimCustom\Libs\Session\File
     */
    public function start(array $config)
    {
        // 获取配置变量
        $sessionName = isset($config['name']) ? $config['name'] : 'PHP_SESSION';
        $savePath = isset($config['save_path']) ? $config['save_path'] : '/tmp/';
        $lifetime = isset($config['lifetime']) ? $config['lifetime'] : 3600 * 24;
        $path = isset($config['path']) ? $config['path'] : '/';
        $domain = isset($config['domain']) ? $config['domain'] : $_SERVER['HTTP_HOST'];
        $secure = isset($config['secure']) ? $config['secure'] : false;
        $httponly = isset($config['httponly']) ? $config['httponly'] : false;
        // 配置session
        //session_name($sessionName);
        //session_save_path($savePath);
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        // 开启session
        if (session_status() != 2) {
            session_start();
        }
        return $this;
    }
    
    /**
     * Return All
     * 
     * @return $_SESSION
     */
    public function all()
    {
        return $_SESSION;
    }
    
    /**
     * 写入session
     * 
     * @param mix $data
     */
    public function write($data)
    {
        $_SESSION = $data;
    }
    
    /**
     * close
     *
     * @return boolean
     */
    public function close()
    {
        return true;
    }
    
    /**
     * 销毁session
     * 
     * @return boolean
     */
    public function destroy()
    {
        return session_destroy();
    }
    
    /**
     * 回收
     * 
     * @return boolean
     */
    public function gc()
    {
        return $this->destroy();
    }
}