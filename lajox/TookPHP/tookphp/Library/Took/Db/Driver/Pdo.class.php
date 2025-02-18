<?php
/**
 * PDO数据库操作类
 * @package     Db
 * @subpackage  Driver
 * @author      lajox <lajox@19www.com>
 */
namespace Took\Db\Driver;
class Pdo extends \Took\Db\Db
{

    static protected $isConnect = null; //是否连接
    public $link = null; //数据库连接
    private $PDOStatement = null; //预准备
    public $affectedRows; //受影响条数

    function connect()
    {
        if (is_null(self::$isConnect)) {
            $dsn = "mysql:host=" . C("DB_HOST") . ';dbname=' . C("DB_DATABASE");
            $username = C("DB_USER");
            $password = C("DB_PASSWORD");
            try {
                self::$isConnect = new Pdo($dsn, $username, $password);
                self::setCharts();
            } catch (\PDOException $e) {
                return false;
            }
        }
        $this->link = self::$isConnect;
        return true;
    }

    /**
     * 设置字符集
     */
    static private function setCharts()
    {
        $character = C("DB_CHARSET");
        $sql = "SET character_set_connection=$character,character_set_results=$character,character_set_client=binary";
        self::$isConnect->query($sql);
    }

    //获得最后插入的ID号
    public function getInsertId()
    {
        return $this->link->lastInsertId();
    }

    //获得受影响的行数
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    //数据安全处理
    public function escapeString($str)
    {
        return addslashes($str);
    }

    //执行SQL没有返回值
    public function exec($sql)
    {
        // 查询参数初始化
        $this->optInit();
        $this->querySql = $sql;
        // 调试开始
        $this->debug(true);
        //释放结果
        if (!$this->PDOStatement)
            $this->resultFree();
        $this->PDOStatement = $this->link->prepare($sql);
        // 调试结束
        $this->debug(false);
        //预准备失败
        if ($this->PDOStatement === false) {
            $this->error($this->link->errorCode() . "\t" . $this->lastSql);
            return false;
        }
        $result = $this->PDOStatement->execute();
        //执行SQL失败
        if ($result === false) {
            $this->error($this->link->errorCode() . "\t" . $this->lastSql);
            return false;
        } else {
            $insert_id = $this->link->lastInsertId();
            return $insert_id ? $insert_id : TRUE;
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
        $cacheName =md5($sql . APP . CONTROLLER . ACTION);
        if ($cacheTime > -1) {
            $result = S($cacheName, FALSE, null, array("driver" => "file", "dir" => TEMP_CACHE_PATH, "zip" => false));
            if ($result) {
                //查询参数初始化
                $this->optInit();
                return $result;
            }
        }
        //发送SQL
        if (!$this->exec($sql)) {
            return false;
        }
        $list = $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        //受影响条数
        $this->affectedRows = count($list);
        if ($list && $cacheTime >= 0 && count($list) <= C("CACHE_SELECT_LENGTH")) {
            S($cacheName, $list, $cacheTime, array("driver" => "file", "dir" => TEMP_CACHE_PATH, "zip" => false));
        }
        return empty($list) ? array() : $list;
    }

    //遍历结果集(根据INSERT_ID)
    protected function fetch()
    {
        $res = $this->lastquery->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            $this->resultFree();
        }
        return $res;
    }

    //释放结果集
    protected function resultFree()
    {
        $this->PDOStatement = NULL;
    }


    // 获得MYSQL版本信息
    public function getVersion()
    {
        return $this->link->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    //开启事务处理
    public function beginTrans()
    {
        $this->link->beginTransaction();
    }

    //提供一个事务
    public function commit()
    {
        $this->link->commit();
    }

    //回滚事务
    public function rollback()
    {
        $this->link->rollback();
    }

    // 释放连接资源
    public function close()
    {
        if (is_object($this->link)) {
            $this->link = NULL;
            self::$isConnect = NULL;
        }
    }

    //析构函数  释放连接资源
    public function __destruct()
    {
        $this->close();
    }

}