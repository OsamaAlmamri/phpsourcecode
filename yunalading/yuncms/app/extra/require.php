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
/**
 * 配置要求检查配置
 */
return [
    'env' => [
        [
            'name' => 'phpversion',
            'min' => '5.5.9',
            'good' => '5.5.9',
            'class' => '\app\core\check\env\PhpVersionCheck'
        ], [
            'name' => 'os',
            'min' => '无限制',
            'good' => 'Linux',
            'class' => '\app\core\check\env\OsCheck'
        ], [
            'name' => 'gd',
            'min' => '2.0',
            'good' => '2.0',
            'class' => '\app\core\check\env\GdCheck'
        ], [
            'name' => 'upload',
            'min' => '未限制',
            'good' => '2M',
            'class' => '\app\core\check\env\UploadCheck'
        ], [
            'name' => 'disk',
            'min' => '100MB',
            'good' => '>100MB',
            'class' => '\app\core\check\env\DiskCheck'
        ]
    ],
    'file' => [
        [
            'name' => 'runtime',
            'path' => ROOT_PATH . 'runtime'
        ], [
            'name' => 'database',
            'path' => ROOT_PATH . 'app' . DS . 'extra' . DS .'database.json'
        ], [
            'name' => 'app',
            'path' => ROOT_PATH . 'app' . DS . 'extra' . DS . 'app.json'
        ], [
            'name' => 'upload',
            'path' => ROOT_PATH . 'app' . DS . 'extra' . DS . 'upload.json'
        ], [
            'name' => 'custom',
            'path' => ROOT_PATH . 'app' . DS . 'extra' . DS . 'custom.json'
        ], [
            'name' => 'sms',
            'path' => ROOT_PATH . 'app' . DS . 'extra' . DS . 'sms.json'
        ], [
            'name' => 'email',
            'path' => ROOT_PATH . 'app' . DS . 'extra' . DS . 'email.json'
        ], [
            'name' => 'data',
            'path' => ROOT_PATH . 'public' . DS . 'data'
        ]
    ],
    'funs' => [
        [
            'name' => 'json',
            'fun' => 'json_encode'
        ], [
            'name' => 'fsocker',
            'fun' => 'fsockopen'
        ]
    ]
];
