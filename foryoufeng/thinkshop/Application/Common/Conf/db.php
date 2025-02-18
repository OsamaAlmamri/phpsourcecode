<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------

/**
 * thinkshop数据库连接配置文件
 */
return array(
    //数据库配置
    'DB_TYPE'   => $_SERVER[ENV_PRE.'DB_TYPE'] ? : 'mysql', //数据库类型
    'DB_HOST'   => $_SERVER[ENV_PRE.'DB_HOST'] ? : '127.0.0.1', //服务器地址
    'DB_NAME'   => $_SERVER[ENV_PRE.'DB_NAME'] ? : 'thinkshop', //数据库名
    'DB_USER'   => $_SERVER[ENV_PRE.'DB_USER'] ? : 'root', //用户名
    'DB_PWD'    => $_SERVER[ENV_PRE.'DB_PWD']  ? : '',  //密码
    'DB_PORT'   => $_SERVER[ENV_PRE.'DB_PORT'] ? : '3306', //端口
    'DB_PREFIX' => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : 'ts_', //数据库表前缀
    'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL), //如果数据表字段名采用大小写混合需配置此项
);
