<?php
/**
 * @package     Test.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月2日
 */
namespace Demo\middlewares\Admin;

/**
 * 中间件示例
 * 
 * @author Jing <tangjing3321@gmail.com>
 */
class Index
{
    /**
     * 中间件index方法
     * 
     * @param \Slim\Http\Request $request
     * @param \SlimCustom\Libs\Http\Response $response
     * @param \Closure $next
     * @return \SlimCustom\Libs\Http\Response
     */
    public function index(\Slim\Http\Request $request, \SlimCustom\Libs\Http\Response $response, \Closure $next)
    {
        $response->getBody()->write('Hello Boy,Girl . ');
        $response = $next($request, $response);
        $response->getBody()->write(' . Come Play!');
        return $response;
    }
}