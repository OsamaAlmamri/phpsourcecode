<?php

return [
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@app/runtime',
    'timezone' => 'PRC',
    'language' => 'zh-CN',
    'bootstrap' => [
        'log',
        'common\\components\\LoadModule',
        'common\\components\\LoadPlugin',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@root/cache',
            'dirMode' => 0777 // 防止console生成的目录导致web账户没写权限
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm',
            'timeFormat' => 'HH:mm',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'log' => [
            'targets' => [
                'db' => [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['warning', 'error'],
                    'except' => [
                        'yii\web\HttpException:4*',
                        'yii\i18n\*'
                    ],
                    'prefix' => function () {
                        $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;
                        $route = Yii::$app->requestedRoute;
                        return sprintf('[%s][%s][%s]', Yii::$app->id, $route, $url);
                    },
                    'logVars' => [],
                    'logTable' => '{{%system_log}}'
                ],
            ]
        ],
        'notify' => 'common\components\notify\Handler',
        'moduleManager' => [
            'class' => 'common\\components\\ModuleManager'
        ],
        'pluginManager' => [
            'class' => 'common\components\PluginManager',
        ],
    ],
];
