<?php

/* Index */

define('_ROOT_', preg_replace('/[\/\\\\]{1,}/', '/', __DIR__ . '/'));

define('_DATA_', _ROOT_ . 'data/');
define('_LOGS_', _DATA_ . 'logs/');
define('_TEMP_', _DATA_ . 'temp/');

define('APP_PATH', _ROOT_ . 'application/');

require _ROOT_ . 'thinkphp/start.php';

