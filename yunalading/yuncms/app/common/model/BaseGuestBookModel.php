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
namespace app\common\model;
/**
 * 留言
 * Class BaseGuestBookModel
 * @package app\common\model
 */
abstract class BaseGuestBookModel extends BaseModel {
    protected $name = 'guestbook';
    protected $auto = [
        'create_time'
    ];
}
