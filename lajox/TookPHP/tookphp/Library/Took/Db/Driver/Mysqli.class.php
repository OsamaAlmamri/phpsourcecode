<?php
/**
 * mysqli数据库驱动
 * @package     Db
 * @subpackage  Driver
 * @author      lajox <lajox@19www.com>
 */
namespace Took\Db\Driver;
class Mysqli extends \Took\Db\Db
{
    //是否连接
    static protected $isConnect = false;
    //数据库连接
    public $link = null;

    //获得数据库连接
    public function connect()
    {
        if (!self::$isConnect) {
            $link = new \mysqli(C("DB_HOST"), C("DB_USER"), C("DB_PASSWORD"), C("DB_DATABASE"), intval(C("DB_PORT")));
            //连接错误
            if (mysqli_connect_errno()) {
                $this->error(mysqli_connect_error());
                return false;
            }
            self::$isConnect = $link;
            self::setCharset();
        }
        $this->link = self::$isConnect;
        return true;
    }

    /**
     * 设置字符集
     */
    static private function setCharset()
    {
        self::$isConnect->set_charset(C("DB_CHARSET"));
    }

    //获得最后插入的ID号
    public function getInsertId()
    {
        return $this->link->insert_id;
    }

    //获得受影响的行数
    public function getAffectedRows()
    {
        return $this->link->affected_rows;
    }

    //遍历结果集(根据INSERT_ID)
    protected function fetch()
    {
        $res = $this->lastQuery->fetch_assoc();
        if (!$res) {
            $this->resultFree();
        }
        return $res;
    }

    //数据安全处理
    public function escapeString($str)
    {
        if ($this->link) {
            return $this->link->real_escape_string($str);
        } else {
            return addslashes($str);
        }
    }

    //执行SQL没有返回值
    public function exec($sql)
    {
        // 查询参数初始化
        $this->optInit();
        $this->querySql = $sql;
        // 调试开始
        $this->debug(true);
        $this->lastQuery = $this->link->query($sql);
        // 调试结束
        $this->debug(false);
        if ($this->lastQuery) {
            //自增id
            $insert_id = $this->link->insert_id;
            return $insert_id ? $insert_id : true;
        } else {
            $this->error($this->link->error . "\t" . $sql);
            return false;
        }
    }

    //发送查询 返回数组
    public function query($sql)
    {
        // 将__table_name__字符串替换成带前缀的表名
        $sql = $this->replaceTable($sql);
        // 缓存时间没有设置时使用配置项缓存时间
        $cacheTime = is_null($this->opt['cacheTime']) ? C("CACHE_SELECT_TIME") : $this->opt['cacheTime'];
        // 查询参数初始化
        $this->optInit();
        $cacheName = md5($sql . MODULE . CONTROLLER . ACTION);
        if ($cacheTime > -1) {
            $result = S($cacheName, FALSE, null, array("driver" => "file", "dir" => TEMP_CACHE_PATH, "zip" => false));
            if ($result) {
                return $result;
            }
        }
        // SQL发送失败
        if (!$this->exec($sql))
            return false;
        $list = array();
        while (($res = $this->fetch()) != false) {
            $list [] = $res;
        }
        if ($list && $cacheTime >= 0 && count($list) <= C("CACHE_SELECT_LENGTH")) {
            S($cacheName, $list, $cacheTime, array("driver" => "file", "dir" => TEMP_CACHE_PATH, "zip" => false));
        }
        return empty($list) ? array() : $list;
    }

    //释放结果集
    protected function resultFree()
    {
        if (isset($this->lastQuery)) {
            $this->lastQuery->close();
        }
    }


    //获得MYSQL版本信息
    public function getVersion()
    {
        return $this->link->server_info;
    }

    //自动提交模式true开启false关闭
    public function beginTrans()
    {
        $this->link->autocommit(0);
    }

    //提供一个事务
    public function commit()
    {
        $this->link->commit();
        $this->link->autocommit(1);
    }

    //回滚事务
    public function rollback()
    {
        $this->link->rollback();
        $this->link->autocommit(1);
    }

    /**
     * 字段和表名处理
     * @access protected
     * @param string $key
     * @return string
     */
    protected function parseKey(&$key) {
        $key   =  trim($key);
        if(!is_numeric($key) && !preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
            $key = '`'.$key.'`';
        }
        return $key;
    }

    //释放连接资源
    public function close()
    {
        if (self::$isConnect) {
            $this->link->close();
            self::$isConnect = false;
            $this->link = null;
        }
    }

    //析构函数  释放连接资源
    public function __destruct()
    {
        $this->close();
    }

}