<?php
/**
 * 这是框架的默认加载类，一般什么都不做，用于被包含时不做任何业务处理。
 *
 * @author john
 */

namespace Lge;

if (!defined('LGE')) {
    exit('Include Permission Denied!');
}

/**
 * Class Controller_Default
 */
class Controller_Default extends BaseController
{

    /**
     * 默认控制器入口函数。
     *
     * @return void
     */
    public function index()
    {
        /*
         * CLI模式下的value及option检查
         */
        if (php_sapi_name() == 'cli') {
            Module_Command::instance()->run();
        }
    }

}
