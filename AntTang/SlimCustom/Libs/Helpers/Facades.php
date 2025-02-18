<?php
/**
 * @package     Facades.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月22日
 */

use \SlimCustom\Libs\App;

if (! function_exists('app')) {
    /**
     * Return App
     * 
     * @return \SlimCustom\Libs\App
     */
    function app()
    {
        return App::$instance;
    }
}

if (! function_exists('model')) {
    /**
     * Return Model
     *
     * @param $table
     * @return \SlimCustom\Libs\Model\Model|\Slim\PDO\Statement\SelectStatement|\Slim\PDO\Statement\InsertStatement|\Slim\PDO\Statement\UpdateStatement|\Slim\PDO\Statement\DeleteStatement|\MongoDB\Collection
     */
    function model($table)
    {
        $class = '\\' . App::$instance->name() . '\\Models\\' . $table;
        return class_exists($class) ? App::di($class) : App::di(\SlimCustom\Libs\Model\Model::class)->table($table);
    }
}

if (! function_exists('request')) {
    /**
     * Return Request
     *
     * @return \Slim\Http\Request
     */
    function request()
    {
        return App::$instance->getContainer()->get('request');
    }
}

if (! function_exists('response')) {
    /**
     * Return Response
     *
     * @return \SlimCustom\Libs\Http\Response
     */
    function response()
    {
        return App::single('response');
    }
}

if (! function_exists('session')) {
    /**
     * Return Session
     *
     * @return \SlimCustom\Libs\Session\Session
     */
    function session()
    {
        return App::single(\SlimCustom\Libs\Session\Session::class);
    }
}

if (! function_exists('cache')) {
    /**
     * Return Cache
     *
     * @return \SlimCustom\Libs\Cache\Cache
     */
    function cache()
    {
        return App::single(\SlimCustom\Libs\Cache\Cache::class);
    }
}

if (! function_exists('filesystem')) {
    /**
     * Return Filesystem
     * 
     * @param $class
     * @return \SlimCustom\Libs\Filesystem\Filesystem
     */
    function filesystem()
    {
        return App::single(\SlimCustom\Libs\Filesystem\Filesystem::class);
    }
}

if (! function_exists('logger')) {
    /**
     * Return Logger
     *
     * @return \Monolog\Logger
     */
    function logger()
    {
        return App::$instance->getContainer()->get('logger');
    }
}

if (! function_exists('renderer')) {
    /**
     * Return Renderer
     *
     * @return \Slim\Views\PhpRenderer
     */
    function renderer()
    {
        return App::$instance->getContainer()->get('renderer');
    }
}

if (! function_exists('database')) {
    /**
     * Return Database
     *
     * @return \Slim\PDO\Database|\MongoDB\Database
     */
    function database()
    {
        return App::$instance->getContainer()->get('database');
    }
}

if (! function_exists('router')) {
    /**
     * Return Router
     *
     * @return \Slim\Router
     */
    function router()
    {
        return App::$instance->getContainer()->get('router');
    }
}

if (! function_exists('environment')) {
    /**
     * Return Environment
     *
     * @return \Slim\Http\Environment
     */
    function environment()
    {
        return App::$instance->getContainer()->get('environment');
    }
}

if (! function_exists('curl')) {
    /**
     * Return Environment
     *
     * @return \SlimCustom\Libs\Curl\Curl
     */
    function curl()
    {
        return App::single(\SlimCustom\Libs\Curl\Curl::class);
    }
}

if (! function_exists('guzzle')) {
    /**
     * Return Guzzle Client
     *
     * @return \GuzzleHttp\Client
     */
    function guzzle()
    {
        return App::single(\GuzzleHttp\Client::class);
    }
}

if (! function_exists('validator')) {
    /**
     * Return Validator
     * 
     * @param array $data
     * @param array $rules
     * @param array $message
     * @return \SlimCustom\Libs\Validation\Validator
     */
    function validator($data, $rules, $message = [])
    {
        return \SlimCustom\Libs\Validation\Validator::make($data, $rules, $message);
    }
}

if (! function_exists('paginator')) {
    /**
     * Return Paginator
     * 
     * @param array $items
     * @param integer $perPage
     * @param integer $currentPage
     * @param array $options
     * @return \SlimCustom\Libs\Paginator\Paginator
     */
    function paginator($items, $perPage, $currentPage = null, array $options = [])
    {
        return \SlimCustom\Libs\Paginator\Paginator::make($items, $perPage, $currentPage, $options);
    }
}

if (! function_exists('daemon')) {
    /**
     * Return Daemon
     * 
     * @return \SlimCustom\Libs\Console\Daemon
     */
    function daemon()
    {
        return App::single(\SlimCustom\Libs\Console\Daemon::class);
    }
}