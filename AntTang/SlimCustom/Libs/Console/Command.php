<?php
/**
 * @package     Command.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月31日
 */

namespace SlimCustom\Libs\Console;

/**
 * Command
 *
 * @author Jing <tangjing3321@gmail.com>
 */
class Command extends \Clio\Console
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * Init Command
     */
    public function __construct()
    {}

    /**
     * Return Command Signature
     *
     * @return string
     */
    public function signature()
    {
        return $this->signature;
    }

    /**
     * Return Command Description
     *
     * @return string
     */
    public function description()
    {
        return $this->description;
    }
}