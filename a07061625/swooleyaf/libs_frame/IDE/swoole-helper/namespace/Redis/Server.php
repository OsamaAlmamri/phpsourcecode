<?php
namespace Swoole\Redis;

/**
 * @since 4.5.4
 */
class Server extends \Swoole\Server
{
    const NIL = 1;
    const ERROR = 0;
    const STATUS = 2;
    const INT = 3;
    const STRING = 4;
    const SET = 5;
    const MAP = 6;

    /**
     * @param $host[required]
     * @param $port[optional]
     * @param $mode[optional]
     * @param $sock_type[optional]
     *
     * @return mixed
     */
    public function __construct($host, $port = null, $mode = null, $sock_type = null)
    {
    }

    /**
     * @return mixed
     */
    public function __destruct()
    {
    }

    /**
     * @return mixed
     */
    public function start()
    {
    }

    /**
     * @param $command[required]
     * @param $callback[required]
     *
     * @return mixed
     */
    public function setHandler($command, $callback)
    {
    }

    /**
     * @param $command[required]
     *
     * @return mixed
     */
    public function getHandler($command)
    {
    }

    /**
     * @param $type[required]
     * @param $value[optional]
     *
     * @return mixed
     */
    public static function format($type, $value = null)
    {
    }

    /**
     * @param $host[required]
     * @param $port[required]
     * @param $sock_type[required]
     *
     * @return mixed
     */
    public function listen($host, $port, $sock_type)
    {
    }

    /**
     * @param $host[required]
     * @param $port[required]
     * @param $sock_type[required]
     *
     * @return mixed
     */
    public function addlistener($host, $port, $sock_type)
    {
    }

    /**
     * @param $event_name[required]
     * @param $callback[required]
     *
     * @return mixed
     */
    public function on($event_name, $callback)
    {
    }

    /**
     * @param $event_name[required]
     *
     * @return mixed
     */
    public function getCallback($event_name)
    {
    }

    /**
     * @param $settings[required]
     *
     * @return mixed
     */
    public function set($settings)
    {
    }

    /**
     * @param $fd[required]
     * @param $send_data[required]
     * @param $server_socket[optional]
     *
     * @return mixed
     */
    public function send($fd, $send_data, $server_socket = null)
    {
    }

    /**
     * @param $ip[required]
     * @param $port[required]
     * @param $send_data[required]
     * @param $server_socket[optional]
     *
     * @return mixed
     */
    public function sendto($ip, $port, $send_data, $server_socket = null)
    {
    }

    /**
     * @param $conn_fd[required]
     * @param $send_data[required]
     *
     * @return mixed
     */
    public function sendwait($conn_fd, $send_data)
    {
    }

    /**
     * @param $fd[required]
     *
     * @return mixed
     */
    public function exists($fd)
    {
    }

    /**
     * @param $fd[required]
     *
     * @return mixed
     */
    public function exist($fd)
    {
    }

    /**
     * @param $fd[required]
     * @param $is_protected[optional]
     *
     * @return mixed
     */
    public function protect($fd, $is_protected = null)
    {
    }

    /**
     * @param $conn_fd[required]
     * @param $filename[required]
     * @param $offset[optional]
     * @param $length[optional]
     *
     * @return mixed
     */
    public function sendfile($conn_fd, $filename, $offset = null, $length = null)
    {
    }

    /**
     * @param $fd[required]
     * @param $reset[optional]
     *
     * @return mixed
     */
    public function close($fd, $reset = null)
    {
    }

    /**
     * @param $fd[required]
     *
     * @return mixed
     */
    public function confirm($fd)
    {
    }

    /**
     * @param $fd[required]
     *
     * @return mixed
     */
    public function pause($fd)
    {
    }

    /**
     * @param $fd[required]
     *
     * @return mixed
     */
    public function resume($fd)
    {
    }

    /**
     * @param $data[required]
     * @param $worker_id[optional]
     * @param $finish_callback[optional]
     *
     * @return mixed
     */
    public function task($data, $worker_id = null, $finish_callback = null)
    {
    }

    /**
     * @param $data[required]
     * @param $timeout[optional]
     * @param $worker_id[optional]
     *
     * @return mixed
     */
    public function taskwait($data, $timeout = null, $worker_id = null)
    {
    }

    /**
     * @param $tasks[required]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public function taskWaitMulti($tasks, $timeout = null)
    {
    }

    /**
     * @param $tasks[required]
     * @param $timeout[optional]
     *
     * @return mixed
     */
    public function taskCo($tasks, $timeout = null)
    {
    }

    /**
     * @param $data[required]
     *
     * @return mixed
     */
    public function finish($data)
    {
    }

    /**
     * @return mixed
     */
    public function reload()
    {
    }

    /**
     * @return mixed
     */
    public function shutdown()
    {
    }

    /**
     * @param $worker_id[optional]
     *
     * @return mixed
     */
    public function stop($worker_id = null)
    {
    }

    /**
     * @return mixed
     */
    public function getLastError()
    {
    }

    /**
     * @param $reactor_id[required]
     *
     * @return mixed
     */
    public function heartbeat($reactor_id)
    {
    }

    /**
     * @param $fd[required]
     * @param $reactor_id[optional]
     *
     * @return mixed
     */
    public function getClientInfo($fd, $reactor_id = null)
    {
    }

    /**
     * @param $start_fd[required]
     * @param $find_count[optional]
     *
     * @return mixed
     */
    public function getClientList($start_fd, $find_count = null)
    {
    }

    /**
     * @return mixed
     */
    public function getWorkerId()
    {
    }

    /**
     * @return mixed
     */
    public function getWorkerPid()
    {
    }

    /**
     * @param $worker_id[optional]
     *
     * @return mixed
     */
    public function getWorkerStatus($worker_id = null)
    {
    }

    /**
     * @return mixed
     */
    public function getManagerPid()
    {
    }

    /**
     * @return mixed
     */
    public function getMasterPid()
    {
    }

    /**
     * @param $fd[required]
     * @param $reactor_id[optional]
     *
     * @return mixed
     */
    public function connection_info($fd, $reactor_id = null)
    {
    }

    /**
     * @param $start_fd[required]
     * @param $find_count[optional]
     *
     * @return mixed
     */
    public function connection_list($start_fd, $find_count = null)
    {
    }

    /**
     * @param $message[required]
     * @param $dst_worker_id[required]
     *
     * @return mixed
     */
    public function sendMessage($message, $dst_worker_id)
    {
    }

    /**
     * @param $process[required]
     *
     * @return mixed
     */
    public function addProcess($process)
    {
    }

    /**
     * @return mixed
     */
    public function stats()
    {
    }

    /**
     * @param $port[optional]
     *
     * @return mixed
     */
    public function getSocket($port = null)
    {
    }

    /**
     * @param $fd[required]
     * @param $uid[required]
     *
     * @return mixed
     */
    public function bind($fd, $uid)
    {
    }

    /**
     * @param $ms[required]
     * @param $callback[required]
     *
     * @return mixed
     */
    public function after($ms, $callback)
    {
    }

    /**
     * @param $ms[required]
     * @param $callback[required]
     *
     * @return mixed
     */
    public function tick($ms, $callback)
    {
    }

    /**
     * @param $timer_id[required]
     *
     * @return mixed
     */
    public function clearTimer($timer_id)
    {
    }

    /**
     * @param $callback[required]
     *
     * @return mixed
     */
    public function defer($callback)
    {
    }
}
