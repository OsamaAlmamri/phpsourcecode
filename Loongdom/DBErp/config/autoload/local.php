<?php
/**
 * DBERP 进销存系统
 *
 * ==========================================================================
 * @link      http://www.dberp.net/
 * @copyright 北京珑大钜商科技有限公司，并保留所有权利。
 * @license   http://www.dberp.net/license.html License
 * ==========================================================================
 *
 * @author    静静的风 <baron@loongdom.cn>
 *
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'translator'    => [
        'locale' => 'zh_CN',
        'translation_file_patterns' => [
            [
                'type' => 'phpArray',
                'base_dir' => \Admin\Validator\Resources::getBasePath(),
                'pattern'  => \Admin\Validator\Resources::getPatternForValidator()
            ],
            [
                'type' => 'phpArray',
                'base_dir' => \Admin\Validator\Resources::getBasePath(),
                'pattern'  => \Admin\Validator\Resources::getPatternForCaptcha()
            ],
        ]
    ],

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                //'driverClass' => Doctrine\DBAL\Driver\PDOPgSql\Driver::class,
                'params' => [
                    'host'     => 'localhost',
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'freedberp',
                    'port'     => '3306',
                    'charset'  => 'utf8mb4'
                ]
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'proxy_dir' => 'data/cache/Doctrine/DoctrineORMModule/Proxy'
            ]
        ]
    ],
];
