# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

---


## 安装步骤
* sudo npm install -g gulp      #全局安装npm
* sudo npm install -g bower     #全局安装bower
* cd register
* sudo npm install gulp   #在项目本地安装gulp
* sudo npm install bower  #在项目本地安装bower
* sudp npm install        #安装项目Node依赖、Laravel Elixir

## 在项目根目录新增文件.bowerrc

```json
{
    "directory": "vendor/bower_dl"
}
```
执行命令 bower init创建文件bower.json


## 如果是linux 后面加上 --allow-root  windows不需要
*  sudo bower install admin-lte --allow-root
*  sudo bower install fontawesome --allow-root
*  sudo bower install ionicons --allow-root
*  sudo bower install https://github.com/smalot/bootstrap-datetimepicker.git --allow-root
*  sudo bower install DataTables --allow-root
*  sudo bower install sweetalert --allow-root
*  sudo bower install dropzone --allow-root
*  sudo bower install toastr --allow-root
*  sudo bower install fileupload --allow-root
*  sudo bower install fancybox --allow-root
*  npm install --save-dev gulp-import-css
*  npm install gulp-cssimport
*  npm install gulp-rename
*  gulp copyfiles
*  gulp

*  php artisan key:generate
* entrust缓存不支持file和database,所以需要在.env 中把CACHE_DRIVER=file 改为 CACHE_DRIVER=array 。然后增加DB_PREFIX=jiyi_ ，把resource/sql 目录下 sql导入即可。
* 火狐中datetmepick的插件会报错，但是其他的插件没有时间选项，解决方法把js文件中this.defaultTimeZone=(new Date()).toString().split("(")[1].slice(0,-1);改为this.defaultTimeZone='GMT '+(new Date()).getTimezoneOffset()/60


