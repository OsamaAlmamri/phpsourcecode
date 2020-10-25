<?php

/*
 * This file is part of Piplin.
 *
 * Copyright (C) 2016-2017 piplin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    'name'              => '站点名称',
    'about'             => '站点介绍',
    'signout'           => '退出',
    'dashboard'         => '控制台',
    'admin'             => '系统设置',
    'projects'          => '项目',
    'groups'            => '项目分组',
    'keys'              => '部署密钥',
    'users'             => '用户管理',
    'tips'              => '小贴士',
    'links'             => '友情链接',
    'created'           => '创建于',
    'edit'              => '编辑',
    'confirm'           => '确认',
    'confirm_title'     => '操作确认',
    'confirm_text'      => '你确定要执行该操作么?',
    'not_applicable'    => 'N/A',
    'date'              => '日期',
    'status'            => '状态',
    'details'           => '详情',
    'delete'            => '删除',
    'save'              => '保存',
    'close'             => '关闭',
    'cancel'            => '取消',
    'copied'            => '已复制',
    'never'             => '从未',
    'none'              => '无',
    'yes'               => '是',
    'no'                => '否',
    'actions'           => '操作',
    'warning'           => '警告',
    'awesome'           => '恭喜！',
    'whoops'            => '糟糕！',
    'socket_error'      => 'Websocket通信失败',
    'socket_error_info' => '浏览器与webcocket服务器 <strong>' . config('piplin.socket_url') . '</strong> 连接失败。'
                            . ' Piplin通过Websocket进行状态通信。'
                            . ' 请刷新页面重试 或 联系系统管理员。',
    'not_down'          => '请将Piplin切换到维护模式。',
    'switch_down'       => 'Switch to maintenance mode now? The app will switch back to live mode once cleanup is finished',
    'update_available'  => 'Piplin有新版本发布!',
    'outdated'          => 'Piplin有新的更新版本 <a href=":link" rel="noreferrer">:latest</a> 发布! 当前运行的系统版本是 :current 。',

];
