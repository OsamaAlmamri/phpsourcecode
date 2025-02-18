<?php
/**
 * @package     Help.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月31日
 */

namespace SlimCustom\Libs\Console\Commands;

use SlimCustom\Libs\Console\Command;
use SlimCustom\Libs\App;

/**
 * 帮助命令
 * 
 * @author Jing Tang <tangjing3321@gmail.com>
 */
class Help extends Command
{
    /**
     * Console object
     * 
     * @var \SlimCustom\Libs\Console\Console;
     */
    public $console;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'help';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commands List';
    
    /**
     *  Return Commands List 
     */
    public function handle()
    {
        $tpl = '';
        $tpl = "%rVersion:%r" . PHP_EOL;
        $tpl .= "    %g" . App::NAME . " " . App::VERSION . "%n" . PHP_EOL;
        if (App::NAME != App::$instance->name()) {
            $appVersion = App::$instance->name() . " " . (App::$instance->deploymentStatus() ?  config('version', '1.0') : '%ris not installed%n');
            $tpl .= "    %g" . $appVersion . "%n" . PHP_EOL;
        }
        $tpl .= "%yUsage:%n" . PHP_EOL;
        $tpl .= "    command [options] [arguments]" . PHP_EOL;
        $tpl .= "%yOptions:%n" . PHP_EOL;
        $tpl .= "%g    -h, --help%n                    Display this help message" . PHP_EOL;
        $tpl .= "%g    -q, --quiet%n                   Do not output any message" . PHP_EOL;
        $tpl .= "%g        --ansi%n                    Force ANSI output" . PHP_EOL;
        $tpl .= "%g        --no-ansi%n                 Disable ANSI output" . PHP_EOL;
        $tpl .= "%yAvailable commands:%n" . PHP_EOL;
        $tmp = [];
        foreach ($this->console->getCommands() as $signature => $command) {
            if (! in_array($command['class'], $tmp)) {
                $tmp[] = $command['class'];
                $group = explode('\\', $command['class']);
                $group = end($group);
                $tpl .= "  %y{$group}%n" . PHP_EOL;
            }
            $tplLength = 30;
            $spaceLength = $tplLength - strlen($signature);
            $space = "";
            for ($i = 0; $i < $spaceLength; $i++) {
                $space .= " ";
            }
            $tplitem = "    \033[0;32m%s\x1B[0m{$space}%s" . PHP_EOL;
            $tpl .= sprintf($tplitem, $signature, $command['description']);
        }
        return static::output(trim($tpl, PHP_EOL));
    }
    
}