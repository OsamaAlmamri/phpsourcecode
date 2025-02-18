# http://www.kplphp.com
Kplphp是一款基于ThinkPHP6底层的极简极速后台开发框架。

## 如果您觉得有帮助，请点右上角 "Star" 支持一下谢谢

## **目前已更新至thinkphp官网最新版本 6.0.4  [ 一直迭代更新~ 2020/10/21 ]**
    
[![Fork me on Gitee](https://gitee.com/JefferyCai/kplphp/widgets/widget_3.svg)](https://gitee.com/JefferyCai/kplphp)

基于thinkphp6操作文档
https://www.kancloud.cn/manual/thinkphp6_0/1037479

码云地址    ：   https://gitee.com/JefferyCai/kplphp

Github地址 ：   https://github.com/Jeffery-Cai/kplphp

mysql数据库文件请加以下QQ群获取。

#  http://www.kplphp.com/public/admin.php/login/index

## **目录结构 **
~~~
www  WEB部署目录（或者子目录）
├─app           应用目录 [原本web目录已经改为app了]
│  ├─admin           应用目录 [后台模块]
│  │  ├─common.php      函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─config          配置目录
│  │  ├─route           路由目录
│  │  └─ ...            更多类库目录
│  ├─api           应用目录 [api模块]
│  │  ├─common.php      函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─config          配置目录
│  │  ├─route           路由目录
│  │  └─ ...            更多类库目录
│  │
│  ├─common.php         公共函数文件
│  └─event.php          事件定义文件
│
├─config                全局配置目录
│  ├─addons.php         插件配置
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─console.php        控制台配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─filesystem.php     文件磁盘配置
│  ├─lang.php           多语言配置
│  ├─log.php            日志配置
│  ├─jump.php           跳转配置
│  ├─middleware.php     中间件配置
│  ├─route.php          URL和路由配置
│  ├─session.php        Session配置
│  ├─trace.php          Trace配置
│  └─view.php           视图配置
│
├─public                WEB目录（对外访问目录） == 在这里提醒，建议新增模块的同时，在该目录下复制相同的.php文件同名便可
│  ├─admin.php          入口文件[admin应用入口]
│  ├─api.php            入口文件[api应用入口]
│  ├─index.php          入口文件[index应用入口]
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                Composer类库目录
├─.example.env          环境变量示例文件
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~
## **后台框架测试地址 [重大更换]**

http://www.kplphp.com/public/admin.php/login/index   ceshi  123456789  [360浏览器访问记得使用极速模式]

## [注意:V1.0 跟 V2.0区别重大，建议使用V2.0版本] **
## V2.0版本 [当前码云版本]
* 前后端分离，自写kbuilder操作器【form，see，table快速构建器】。内置增删查改，表单多类型扩展
* 内置一键快速CURD命令
~~~ 
php think make:kplphp_kbuilder Hello Hello --app admin   [ # 快速构建器命令 ]
php think make:kplphp_curd Hello Hello Hello --app admin [ # 普通CURD增删改查+验证器 ]
~~~

* 增加顶部菜单，让菜单更新理性
* 由V1.0版本的table表格datatables插件优化为layui插件
* 强大的插件扩展功能，在线安装卸载升级插件 [可进群下载更多插件]
* 丰富的插件应用市场 [升级中]

## V1.0版本
* 下载kplphpV1.0.0 / V2版本地址：https://gitee.com/JefferyCai/kplphp/releases      【码云】https://github.com/Jeffery-Cai/kplphp/releases  【github】

* 基本的权限管理系统
  支持无限级父子级权限继承，父级的管理员可任意增删改子级管理员及权限设置
  支持单管理员多角色
  支持管理子级数据或个人数据
* 去除顶部菜单,只有左侧菜单

## **安装使用**

https://www.kancloud.cn/jefferycai/kplphp/1805815

## **推荐配置**

LNMP环境：

- PHP7.1+
- Nginx
- CentOS7
- MySQL5.6+

注意:

ThinkPHP6.0基于精简核心和统一用法两大原则在5.1的基础上对底层架构做了进一步的优化改进，并更加规范化。由于引入了一些新特性，ThinkPHP6.0运行环境要求PHP7.1+，不支持5.1的无缝升级（官方给出了升级指导用于项目的升级参考）。


## **界面截图**
![主页](http://www.kplphp.com/public/11.png "主页")

![菜单管理](http://www.kplphp.com/public/22.png "菜单管理")

![模板](http://www.kplphp.com/public/44.png "模板")

![模板](http://www.kplphp.com/public/55.png "模板")

![模板](http://www.kplphp.com/public/33.png "模板")


## **问题反馈**

在使用中有任何问题，请使用以下联系方式联系我们

交流社区: https://ask.kplphp.com  [域名正在建设中]

QQ群1: [972703635](https://jq.qq.com/?_wv=1027&k=57JpRdR)(未满，可加) 
QQ群2: [972703635](https://jq.qq.com/?_wv=1027&k=57JpRdR)(未满，可加) 

Email: (1345199080#qq.com, 把#换成@)

Gitee: https://gitee.com/JefferyCai/kplphp

## **特别鸣谢**

感谢以下的项目,排名不分先后

ThinkPHP：http://www.thinkphp.cn

jQuery：http://jquery.com

layui : https://www.layui.com

等其他.

## **版权信息**

Kplphp遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2019-2020 by Kplphp

All rights reserved。