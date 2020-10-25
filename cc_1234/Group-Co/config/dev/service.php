<?php
return [
    //加密token，16位。可修改
    'encipher' => 'uoI49l^^M!a5&bZt',

    //注册中心，如果不为空的话，在server启动时会起一个子进程订阅依赖的服务列表。
    'registryAddress' => [
        'scheme' => 'redis',
        'host' => '192.168.1.103',//change it
        'prefix'   => 'co:',
        'port' => 6379,
        'auth' => '',
    ],
    // 'registry_address' => [
    //     'scheme' => 'zookeeper',
    //     'host' => '127.0.0.1',
    //     'port' => 2181,
    //     //集群模式
    //     //'url' => '127.0.0.1:2181,127.0.0.1:2182'
    // ],

    //配置service
    'server' => [
        'monitor' => [
            //本机当前内网ip||如果不填默认取当前运行容器内网IP
            //'ip' => '192.168.1.103',//change it
            'serv' => '0.0.0.0',
            'port' => 9517,
            'config' => [
                'daemonize' => true,        
                'log_file' => 'runtime/service/monitor.log',
            ],
            'public' => '',
            'process' => [
                //你可以使用框架封装的心跳检测进程
                'Group\Process\HeartbeatProcess',
            ],
        ],
        //可以配置多个server，注意请监听不同的端口。
        //serverName
        'test' => [
            //本机当前内网ip||如果不填默认取当前运行容器内网IP
            //'ip' => '192.168.1.103',//change it
            'serv' => '0.0.0.0',
            'port' => 9511,
            //server配置，请根据实际情况调整参数
            'config' => [
                //'daemonize' => true,
                //worker进程数量         
                'worker_num' => 2,
                //最大请求数，超过后讲重启worker进程
                'max_request' => 50000,
                //task进程数量
                'task_worker_num' => 5,
                //task进程最大处理请求上限，超过后讲重启task进程
                'task_max_request' => 50000,
                //日志
                'log_file' => 'runtime/service/user.log',
                //其他配置详见swoole官方配置参数列表
            ],
            
            //公开哪些服务，如果不设置此参数默认公开所有服务
            //'public' => 'User',
            'rely' => ''
        ],
    ],
];
