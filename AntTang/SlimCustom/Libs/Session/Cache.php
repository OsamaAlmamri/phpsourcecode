<?php
/**
 * @package     Cache.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月18日
 */

namespace SlimCustom\Libs\Session;

use SlimCustom\Libs\Cache\Cache as CacheSupport;

/**
 * Cache
 * 
 * @author Jing <tangjing3321@gmail.com>
 */
class Cache
{
    /**
     * 缓存对象
     * 
     * @var \SlimCustom\Libs\Cache\Cache
     */
    private $cache;
    
    /**
     * 生存时长
     * 
     * @var integer
     */
    private $lifetime;
    
    /**
     * sessionId
     * 
     * @var string
     */
    private $sessionid;
    
    /**
     * 初始化session
     * 
     * @param CacheSupport $cache
     */
    public function __construct(CacheSupport $cache)
    {
        $this->cache = $cache;
    }
    
    /**
     * 开启session
     * 
     * @param array $config
     * @param unknown $sessionid
     * @return \SlimCustom\Libs\Session\Cache
     */
    public function start(array $config, $sessionid = null)
    {
        if (! $sessionid && session_status() != 2) {
            session_start();
            $sessionid = session_id();
        }
        $this->sessionid = $sessionid;
        $this->lifetime = isset($config['lifetime']) ? $config['lifetime'] * 60 : 120 * 60;
        return $this;
    }
    
    /**
     * Return All
     *
     * @return NULL|unknown
     */
    public function all()
    {
        return $this->cache->get('Session:' . $this->sessionid, []);
    }
    
    /**
     * 写入session
     *
     * @param mix $data
     */
    public function write($data)
    {
        if (! $this->cache->put('Session:' . $this->sessionid, $data, $this->lifetime)) {
            return false;
        }
        return true;
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
        return $this->cache->forget('Session:' . $this->sessionid);
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