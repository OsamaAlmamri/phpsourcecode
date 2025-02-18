<?php
// +----------------------------------------------------------------------
// | Author: Zaker <49007623@qq.com>
// +----------------------------------------------------------------------

namespace app\common\validate;

/**
 * 权限分组验证器
 */
class AuthGroup extends ValidateBase
{

    // 验证规则
    protected $rule = [
        'title'       => 'require|length:1,10',
        'description' => 'length:0,50',
    ];

    // 验证提示
    protected $message = [
        'title.require'      => '权限组标题不能为空',
        'title.length'       => '权限组长度为1-10个字符之间',
        'description.length' => '权限组描述长度为0-50个字符之间',
    ];
}
