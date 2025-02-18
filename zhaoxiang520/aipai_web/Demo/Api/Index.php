<?php
namespace Demo\Api;
use PhalApi\Core\Db;

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Index {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username', 'default' => 'PHPer', ),
            ),
        );
	}
	
	/**
	 * 默认接口服务
	 * @return string title 标题
	 * @return string content 内容
	 * @return string version 版本，格式：X.X.X
	 * @return int time 当前时间戳
	 */
	public function index() {
        return array(
            'title' => 'Hello World!',
            'content' => 'sadadasdasd',
            'version' => APP_VERSION,
            'time' => $_SERVER['REQUEST_TIME'],
        );
	}
}
