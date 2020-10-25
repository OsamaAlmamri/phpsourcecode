<?php
/**
 * Created by PhpStorm.
 * User: chenbo
 * Date: 18-6-6
 * Time: 上午11:30
 */

namespace SWBT\Process;


use Pimple\Container;

class Master
{
    private static $pid;
    private $logger;
    private $container;
    public function __construct(Container $container)
    {
		(PHP_OS !== 'Darwin') && swoole_set_process_name('SWBT master');
        self::$pid = posix_getpid();
        $this->container = $container;
        $this->logger = $container['logger'];
    }

    public function run(){
        file_put_contents($this->container['swbt_dir'] . $this->container['pid']['file_path'], self::$pid);
        $this->logger->info('SWBT Start', ['pid' => posix_getpid()]);
        $tubeProcess = new Tube($this->container);
        $tubeProcess->start();
        while (true){
            $tubeProcess->rebootWait();
        }
    }

    public static function isExist(){
        return \Swoole\Process::kill(self::$pid, 0);
    }
}