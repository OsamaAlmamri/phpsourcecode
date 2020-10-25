<?php
return [
/****************FRAMEWORK CONFIG*********************/
    //debug开启后service会打印接受到的数据包
    'debug' => true,

    //zh|en|fr...
    'locale' => 'zh',

    //时区
    'timezone' => 'Asia/Shanghai',

    //类的映射
    'aliases' => [
        //like  'demo'       => 'src\Service\demo',
    ],

    'onWorkStartServices' => [
        'Group\Async\Pool\MysqlPoolServiceProvider',
        'Group\Async\Pool\RedisPoolServiceProvider',
        //'Group\Async\Pool\WebSocketPoolServiceProvider',
    ],

    'onRequestServices' => [
        //如果做api服务,可以不加载twig
        'Group\Controller\TwigServiceProvider',
    ],

    //需要实例化的单例
    'singles' => [
        //like  'demo'       => 'src\demo\demo',
    ],

    //扩展console命令行控制台
    'consoleCommands' => [
        'log.clear' => [
            'command' => 'src\Web\Command\LogClearCommand', //执行的类
            'help' => '清除日志', //提示
        ],
    ],
//**修改以下配置后需要restart server。reload不生效！
/****************SERVER CONFIG*********************/
    //本机当前内网ip||如果不填默认取当前运行容器内网IP
    //'ip' => '192.168.1.103',//change it

    'host' => '0.0.0.0',
    'port' => 9777,

    'setting' => [
        //日志
        //'daemonize' => true,
        'log_file' => 'runtime/error.log',
        'worker_num' => 2,    //worker process num
        'backlog' => 256,   //listen backlog
        'heartbeat_idle_time' => 30,
        'heartbeat_check_interval' => 10,
        'dispatch_mode' => 1, 
        'max_request' => 10000,
        'reload_async' => true,
    ],

    //在启动时可以添加用户自定义的工作进程,必须是swoole_process,请继承Group\Process抽象类
    'process' => [
    ],

    //依赖的服务模块 
    'services' => ["User", "Order", "Monitor"],
    //服务调用失败次数，超出后进行故障切换
    'retries' => 3,
    //异步rpc方法调用超时时间
    'timeout' => 5,

    //此参数可不填。通信协议 eof：结束符, buf：包头+包体。也可以填自定义的customProtocols
    'protocol' => 'buf',
    //服务调用失败次数，超出后进行故障切换
    'retries' => 3,
    //异步rpc方法调用超时时间
    'timeout' => 5,

    //连接池大小
    'ws.maxPool' => 100,
];