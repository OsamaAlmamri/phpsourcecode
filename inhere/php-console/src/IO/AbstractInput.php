<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-03
 * Time: 10:24
 */

namespace Inhere\Console\IO;

use Inhere\Console\Concern\InputArgumentsTrait;
use Inhere\Console\Concern\InputOptionsTrait;
use function getcwd;
use function is_int;
use function trim;

/**
 * Class AbstractInput
 *
 * @package Inhere\Console\IO
 */
abstract class AbstractInput implements \Inhere\Console\Contract\InputInterface
{
    use InputArgumentsTrait, InputOptionsTrait;

    /**
     * @var string
     */
    protected $pwd;

    /**
     * The script path
     * e.g `./bin/app` OR `bin/cli.php`
     *
     * @var string
     */
    protected $script;

    /**
     * The script name
     * e.g `app` OR `cli.php`
     *
     * @var string
     */
    protected $scriptName;

    /**
     * the command name(Is first argument)
     * e.g `start` OR `start`
     *
     * @var string
     */
    protected $command = '';

    /**
     * eg `./examples/app home:useArg status=2 name=john arg0 -s=test --page=23`
     *
     * @var string
     */
    protected $fullScript;

    /**
     * Raw argv data.
     *
     * @var array
     */
    protected $tokens;

    /**
     * Same the $tokens but no $script
     *
     * @var array
     */
    protected $flags = [];

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    abstract public function toString(): string;

    /**
     * find command name. it is first argument.
     */
    protected function findCommand(): void
    {
        if (!isset($this->args[0])) {
            return;
        }

        $newArgs = [];

        foreach ($this->args as $key => $value) {
            if ($key === 0) {
                $this->command = trim($value);
            } elseif (is_int($key)) {
                $newArgs[] = $value;
            } else {
                $newArgs[$key] = $value;
            }
        }

        $this->args = $newArgs;
    }

    /***********************************************************************************
     * getter/setter
     ***********************************************************************************/

    /**
     * @return string
     */
    public function getPwd(): string
    {
        if (!$this->pwd) {
            $this->pwd = (string)getcwd();
        }

        return $this->pwd;
    }

    /**
     * @return string
     */
    public function getWorkDir(): string
    {
        return $this->getPwd();
    }

    /**
     * @param string $pwd
     */
    public function setPwd(string $pwd): void
    {
        $this->pwd = $pwd;
    }

    /**
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }

    /**
     * @param string $script
     */
    public function setScript(string $script): void
    {
        $this->script = $script;
    }

    /**
     * @return string
     */
    public function getScriptName(): string
    {
        return $this->scriptName;
    }

    /**
     * @return string
     */
    public function getBinName(): string
    {
        return $this->scriptName;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getFullScript(): string
    {
        return $this->fullScript;
    }

    /**
     * @param string $fullScript
     */
    public function setFullScript(string $fullScript): void
    {
        $this->fullScript = $fullScript;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param array $tokens
     */
    public function setTokens(array $tokens): void
    {
        $this->tokens = $tokens;
    }
}
