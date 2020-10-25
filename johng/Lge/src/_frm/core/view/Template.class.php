<?php
/**
 * 模板管理类，实现模板引擎功能。
 *
 * @author John
 */

namespace Lge;

if (!defined('LGE')) {
    exit('Include Permission Denied!');
}
/**
 * 模板管理类
 */
class Template
{
    /**
     * 模板引擎对象
     *
     * @var FastTpl
     */
    private $_tpl;

    // 模板引擎参数
    private $_tplOptions = array(
        'tpl_ext'       => 'tpl.htm',
        'tpl_dir'       => '',
        'cache_dir'     => '',
        'debug'         => true,
        'php_enabled'   => false,
        'totally_php'   => false,
        'check_update'  => true,
    );

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->_tplOptions = Core::$tplOptions;
    }

    /**
     * 设置单项模板配置参数.
     *
     * @param string $optionKey   配置项名称.
     * @param mixed  $optionValue 配置项值.
     *
     * @return void
     */
    public function setOption($optionKey, $optionValue)
    {
        $this->_tplOptions[$optionKey] = $optionValue;
        if (!empty($this->_tpl)) {
            $this->_tpl->setOption($optionKey, $optionValue);
        }
    }

    /**
     * 设置模板引擎参数.
     *
     * @param array $options 参数数组
     *
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->_tplOptions = $options;
        if (!empty($this->_tpl)) {
            $this->_tpl->setOptions($this->_tplOptions);
        }
    }
    
    /**
     * 获得模板引擎参数.
     *
     * @return array
     */
    public function &getOptions()
    {
        return $this->_tplOptions;
    }

    /**
     * 获取模板变量.
     *
     * @return array
     */
    public function &getVars()
    {
        if (empty($this->_tpl)) {
            $this->_init();
        }
        return $this->_tpl->vars;
    }

    /**
     * 根据名称获取赋值的模板变量值.
     *
     * @param string $key 模板变量名称.
     *
     * @return mixed
     */
    public function getVar($key)
    {
        if (empty($this->_tpl)) {
            $this->_init();
        }
        $var = null;
        if (isset($this->_tpl->vars[$key])) {
            $var = $this->_tpl->vars[$key];
        }
        return $var;
    }

    /**
     * 添加插件目录.
     *
     * @param string $dirPath 插件目录绝对路径.
     *
     * @return void
     */
    public function addPluginDir($dirPath)
    {
        $this->_tplOptions['plugin_dirs'][] = $dirPath;
        if (!empty($this->_tpl)) {
            $this->_tpl->setOptions(array('plugin_dirs' => $this->_tplOptions['plugin_dirs']));
        }
    }
    
    /**
     * 设置模板编译文件存放目录。
     *
     * @param string $compileDir 编译后的模板文件存放目录绝对路径
     *
     * @return void
     */
    public function setCompileDir($compileDir)
    {
        $this->_tplOptions['cache_dir'] = $compileDir;
        if (!empty($this->_tpl)) {
            $this->_tpl->setOptions(array('cache_dir' => $compileDir));
        }
    }
    
    /**
     * 设置模板文件存放目录。
     *
     * @param string $tplDir 模板文件存放目录绝对路径
     *
     * @return void
     */
    public function setTplDir($tplDir)
    {
        $this->_tplOptions['tpl_dir'] = $tplDir;
        if (!empty($this->_tpl)) {
            $this->_tpl->setOptions(array('tpl_dir' => $tplDir));
        }
    }
    
    /**
     * 用数组形式为页面赋值，每个变量是数组的键值对。
     *
     * @param array $array 键值对数组
     *
     * @return void
     */
    public function assigns(array $array)
    {
        if (empty($this->_tpl)) {
            $this->_init();
        }
        $this->_tpl->assigns($array);
    }
    
    /**
     * 页面赋值
     *
     * @param string $name  名称
     * @param mixed  $value 赋值
     *
     * @return void
     */
    public function assign($name, $value)
    {
        if (empty($this->_tpl)) {
            $this->_init();
        }
        $this->_tpl->assign($name, $value);
    }
    
    /**
     * 获得模板解析后展示的内容.
     *
     * @param string $file 文件名(不带扩展名)
     *
     * @return string
     */
    public function getDisplayContent($file)
    {
        if (empty($this->_tpl)) {
            $this->_init();
        }
        return $this->_tpl->getDisplayContent($file);
    }
    
    /**
     * 显示模板。
     *
     * @param string $tpl 模板名称(不带扩展名)
     *
     * @return void
     */
    public function display($tpl)
    {
        if (empty($this->_tpl)) {
            $this->_init();
        }
        $this->_tpl->display($tpl);
    }
    
    /**
     * 初始化
     *
     * @return void
     */
    private function _init()
    {
        include(__DIR__."/../component/FastTpl/FastTpl.class.php");
        $this->_tpl = new FastTpl($this->_tplOptions);
    }

}
