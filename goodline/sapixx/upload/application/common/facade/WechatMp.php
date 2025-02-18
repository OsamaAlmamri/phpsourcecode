<?php
/**
 * @copyright   Copyright (c) 2017 https://www.sapixx.com All rights reserved.
 * @license     Licensed (http://www.apache.org/licenses/LICENSE-2.0).
 * @author      pillar<ltmn@qq.com>
 * 微信公众号管理静态代理
 */
namespace app\common\facade;
use think\Facade;

class WechatMp extends Facade{

    protected static function getFacadeClass()
    {
        return 'app\common\facade\library\WechatMp';
    }
}