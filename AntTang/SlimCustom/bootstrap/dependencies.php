<?php
/**
 * @package     dependencies.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年4月5日
 */

namespace SlimCustom\bootstrap;

use SlimCustom\Libs\Handlers\PhpError;
use SlimCustom\Libs\Handlers\Error;
use Slim\Handlers\NotFound;
use Slim\Handlers\NotAllowed;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Router;
use Slim\CallableResolver;
use SlimCustom\Libs\Http\Response;
use SlimCustom\Libs\Handlers\FoundHandler as RequestResponse;
use SlimCustom\Libs\App;

// 依赖容器
$container = App::getContainer();

$container['environment'] = function () {
    return new Environment($_SERVER);
};

$container['request'] = function ($container) {
    return Request::createFromEnvironment($container->get('environment'));
};

$container['response'] = function ($container) {
    $headers = new Headers([
        'Content-Type' => 'text/html; charset=UTF-8'
    ]);
    $response = new Response(200, $headers);
    
    return $response->withProtocolVersion($container->get('settings')['httpVersion']);
};

$container['router'] = function ($container) {
    $routerCacheFile = false;
    if (isset($container->get('settings')['routerCacheFile'])) {
        $routerCacheFile = $container->get('settings')['routerCacheFile'];
    }
    
    $router = (new Router())->setCacheFile($routerCacheFile);
    if (method_exists($router, 'setContainer')) {
        $router->setContainer($container);
    }
    
    return $router;
};

$container['foundHandler'] = function () {
    return new RequestResponse();
};

$container['phpErrorHandler'] = function ($container) {
    return new PhpError($container->get('settings')['displayErrorDetails']);
};

$container['errorHandler'] = function ($container) {
    return new Error($container->get('settings')['displayErrorDetails']);
};

$container['notFoundHandler'] = function () {
    return new NotFound();
};

$container['notAllowedHandler'] = function () {
    return new NotAllowed();
};

$container['callableResolver'] = function ($container) {
    return new CallableResolver($container);
};

$container['renderer'] = function ($container) {
    $settings = $container->get('settings')['renderer'];
    return new \Slim\Views\PhpRenderer($settings['template_path']);
};

$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['cache'] = function ($container) {
    return App::single(\SlimCustom\Libs\Cache\Cache::class);
};

$container['database'] = function ($container) {
    $default = strtolower($container['settings']['database']['default']);
    $defaultConfigs = $container['settings']['database']['connections'][$default];
    switch ($default) {
        case 'pdo':
            $dsn = "{$defaultConfigs['driver']}:host={$defaultConfigs['host']};dbname={$defaultConfigs['database']};charset={$defaultConfigs['charset']}";
            $pdo = new \Slim\PDO\Database($dsn, $defaultConfigs['username'], $defaultConfigs['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
            break;
        case 'mongodb':
            $uri = $defaultConfigs['host'];
            $uriOptions = $defaultConfigs['uri_options'];
            $driverOptions = $defaultConfigs['driver_options'];
            $client = new \MongoDB\Client($uri, $uriOptions, $driverOptions);
            $client = $client->selectDatabase($defaultConfigs['database']);
            return $client;
            break;
        case 'eloquent':
            $capsule = new \Illuminate\Database\Capsule\Manager();
            $capsule->addConnection($defaultConfigs);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            return $capsule;
            break;
        default:
            $dsn = "mysql:host={$defaultConfigs['host']};dbname={$defaultConfigs['database']};charset={$defaultConfigs['charset']}";
            $pdo = new \Slim\PDO\Database($dsn, $defaultConfigs['username'], $defaultConfigs['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
            break;
    }
};