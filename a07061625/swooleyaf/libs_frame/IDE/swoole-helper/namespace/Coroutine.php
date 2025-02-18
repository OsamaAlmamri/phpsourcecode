<?php
namespace Swoole;

/**
 * @since 4.5.4
 */
class Coroutine
{
    /**
     * @param $func[required]
     * @param $params[optional]
     *
     * @return mixed
     */
    public static function create($func, $params = null)
    {
    }

    /**
     * @param $callback[required]
     *
     * @return mixed
     */
    public static function defer($callback)
    {
    }

    /**
     * @param $options[required]
     *
     * @return mixed
     */
    public static function set($options)
    {
    }

    /**
     * @param $cid[required]
     *
     * @return mixed
     */
    public static function exists($cid)
    {
    }

    /**
     * @return mixed
     */
    public static function yield()
    {
    }

    /**
     * @return mixed
     */
    public static function suspend()
    {
    }

    /**
     * @param $cid[required]
     *
     * @return mixed
     */
    public static function resume($cid)
    {
    }

    /**
     * @return mixed
     */
    public static function stats()
    {
    }

    /**
     * @return mixed
     */
    public static function getCid()
    {
    }

    /**
     * @return mixed
     */
    public static function getuid()
    {
    }

    /**
     * @param $cid[optional]
     *
     * @return mixed
     */
    public static function getPcid($cid = null)
    {
    }

    /**
     * @param $cid[optional]
     *
     * @return mixed
     */
    public static function getContext($cid = null)
    {
    }

    /**
     * @param $cid[optional]
     * @param $options[optional]
     * @param $limit[optional]
     *
     * @return mixed
     */
    public static function getBackTrace($cid = null, $options = null, $limit = null)
    {
    }

    /**
     * @param $cid[optional]
     *
     * @return mixed
     */
    public static function getElapsed($cid = null)
    {
    }

    /**
     * @return mixed
     */
    public static function list()
    {
    }

    /**
     * @return mixed
     */
    public static function listCoroutines()
    {
    }

    /**
     * @return mixed
     */
    public static function enableScheduler()
    {
    }

    /**
     * @return mixed
     */
    public static function disableScheduler()
    {
    }

    /**
     * @param $domain_name[required]
     * @param $family[optional]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function gethostbyname($domain_name, $family = null, $timeout = null)
    {
    }

    /**
     * @param $domain_name[required]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function dnsLookup($domain_name, $timeout = null)
    {
    }

    /**
     * @param $command[required]
     * @param $get_error_stream[optional]
     *
     * @return mixed
     */
    public static function exec($command, $get_error_stream = null)
    {
    }

    /**
     * @param $seconds[required]
     *
     * @return mixed
     */
    public static function sleep($seconds)
    {
    }

    /**
     * @param $hostname[required]
     * @param $family[optional]
     * @param $socktype[optional]
     * @param $protocol[optional]
     * @param $service[optional]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function getaddrinfo($hostname, $family = null, $socktype = null, $protocol = null, $service = null, $timeout = null)
    {
    }

    /**
     * @param $path[required]
     *
     * @return mixed
     */
    public static function statvfs($path)
    {
    }

    /**
     * @param $filename[required]
     *
     * @return mixed
     */
    public static function readFile($filename)
    {
    }

    /**
     * @param $filename[required]
     * @param $data[required]
     * @param $flags[optional]
     *
     * @return mixed
     */
    public static function writeFile($filename, $data, $flags = null)
    {
    }

    /**
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function wait($timeout = null)
    {
    }

    /**
     * @param $pid[required]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function waitPid($pid, $timeout = null)
    {
    }

    /**
     * @param $signo[required]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function waitSignal($signo, $timeout = null)
    {
    }

    /**
     * @param $fd[required]
     * @param $events[optional]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public static function waitEvent($fd, $events = null, $timeout = null)
    {
    }

    /**
     * @param $handle[required]
     * @param $length[optional]
     *
     * @return mixed
     */
    public static function fread($handle, $length = null)
    {
    }

    /**
     * @param $handle[required]
     *
     * @return mixed
     */
    public static function fgets($handle)
    {
    }

    /**
     * @param $handle[required]
     * @param $string[required]
     * @param $length[optional]
     *
     * @return mixed
     */
    public static function fwrite($handle, $string, $length = null)
    {
    }
}
