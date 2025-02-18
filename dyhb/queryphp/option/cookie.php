<?php

declare(strict_types=1);

/*
 * This file is part of the your app package.
 *
 * The PHP Application For Code Poem For You.
 * (c) 2018-2099 http://yourdomian.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
     * ---------------------------------------------------------------
     * cookie 域名
     * ---------------------------------------------------------------
     *
     * Cookie 的有效域名/子域名。 设置成子域名（例如 'www.example.com'），会使 Cookie
     * 对这个子域名和它的三级域名有效（例如 w2.www.example.com）。 要让 Cookie 对整个域名
     * 有效（包括它的全部子域名），只要设置成域名就可以了（这个例子里是 'example.com'）
     * 相关技术文档：http://php.net/manual/zh/function.setcookie.php
     */
    'domain' => '',

    /*
     * ---------------------------------------------------------------
     * cookie 路径
     * ---------------------------------------------------------------
     *
     * Cookie 有效的服务器路径。 设置成 '/' 时，Cookie 对整个域名 domain 有效。 如果设置成 '/foo/'，
     * Cookie 仅仅对 domain 中 /foo/ 目录及其子目录有效（比如 /foo/bar/）。 默认值是设置 Cookie 时的当前目录
     * 相关技术文档：http://php.net/manual/zh/function.setcookie.php
     */
    'path' => '/',

    /*
     * ---------------------------------------------------------------
     * cookie 默认过期时间
     * ---------------------------------------------------------------
     *
     * Cookie 的过期时间。 这是个 Unix 时间戳，即 Unix 纪元以来（格林威治时间 1970 年 1 月 1 日 00:00:00）
     * 的秒数。 也就是说，基本可以用 time() 函数的结果加上希望过期的秒数。 或者也可以用 mktime()。 time()+60*60*24*30
     * 就是设置 Cookie 30 天后过期。 如果设置成零，或者忽略参数， Cookie 会在会话结束时过期（也就是关掉浏览器时）
     * 这里的过期时间为我们在当前时间上加上了过期的秒数量即为过期时间
     * 相关技术文档：http://php.net/manual/zh/function.setcookie.php
     */
    'expire' => 86400,

    /*
     * ---------------------------------------------------------------
     * cookie 安全 HTTPS 连接
     * ---------------------------------------------------------------
     *
     * 设置成 TRUE，表示只应通过客户端的安全HTTPS连接传输 cookie
     * 相关技术文档：http://php.net/manual/zh/function.setcookie.php
     */
    'secure' => false,

    /*
     * ---------------------------------------------------------------
     * cookie 仅 HTTP 协议访问
     * ---------------------------------------------------------------
     *
     * 设置成 TRUE，Cookie 仅可通过 HTTP 协议访问。 这意思就是 Cookie 无法通过类似 JavaScript 这样的脚本语言访问。
     * 要有效减少 XSS 攻击时的身份窃取行为，可建议用此设置（虽然不是所有浏览器都支持），不过这个说法经常有争议。 PHP 5.2.0 中添加。
     * TRUE 或 FALSE
     * 相关技术文档：http://php.net/manual/zh/function.setcookie.php
     */
    'httponly' => false,

    /*
     * ---------------------------------------------------------------
     * cookie 跨域发送设置
     * ---------------------------------------------------------------
     *
     * 允许服务器设定一则 cookie 不随着跨域请求一起发送，这样可以在一定程度上防范跨站请求伪造攻击（CSRF）。
     * Strict 或 Lax
     * 相关技术文档：https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Set-Cookie
     * 例外 php 7.3 支持 https://www.php.net/manual/en/function.setcookie.php
     */
    'samesite' => null,
];
