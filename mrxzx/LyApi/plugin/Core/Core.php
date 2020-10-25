<?php

namespace Plugin\Core;

use APP\DI;
use APP\program\Ecore;
use LyApi\tools\Config;
use Plugin\Core\tools\Save;
use Unirest\Request;
use Unirest\Exception;

/**
 * Name: Core.Core
 * Author: LyAPI
 * ModifyTime: 2019/07/31
 * Purpose: 提供其他插件引用，使插件开发更简单。
 */

// 可在其他插件中直接引入Core类并使用其中的函数和变量
class Core
{

    protected $Plugin_Name = "Core";                //插件名字信息，必须填写
    protected $Plugin_Version = "V1.0.1";           //插件版本信息，必须填写
    protected $Plugin_Author = "LyApi";             //插件作者信息，建议填写
    protected $Plugin_About = "LyApi Plugin Core";  //插件简单介绍，建议填写
    protected $Plugin_Examine = "";                 //检查插件版本的接口地址（留空则不检查）

    protected $Tmp_Data = array();

    //核心代码构造函数
    public function __construct()
    {
        $this->InitPlugin();
    }

    //检查插件版本，当版本号不同返回False，相同返回True
    protected function CheckVersion()
    {

        if ($this->Plugin_Examine != "") {

            try {
                $data = Request::get($this->Plugin_Examine);
            } catch (Exception $e) {
                return false;
            }

            $ret = $data->body;
            if ($ret->code == 200) {
                if ($ret->data == $this->Plugin_Version) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return false;
        }
    }

    //设置或读取不存在的值时，指向Tmp_Data数组中的数据
    public function __set($name, $value)
    {
        $this->Tmp_Data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Tmp_Data)) {
            return $this->Tmp_Data[$name];
        } else {
            return null;
        }
    }

    //当对象被当作字符串输出时
    public function __toString()
    {
        return array(
            'PluginName' => $this->Plugin_Name,
            'PluginVersion' => $this->Plugin_Version,
            'PluginAuthor' => $this->Plugin_Author,
            'PluginAbout' => $this->Plugin_About
        );
    }

    //被当作函数调用时，自动调用某个函数
    public function __invoke($function, ...$args)
    {
        $ret = null;

        eval('$ret = $this->$function(' . implode(",", $args) . ');');
        return $ret;
    }

    // 插件初始化程序 ( 插件重写构造函数后依然可使用本函数 )
    protected function InitPlugin(){
        $Using_ECore = Config::getConfig('func', '')['USING_ECORE'];
        if ($Using_ECore) {

            // 调用初始化插件函数
            $Ecore = new Ecore();
            $Result = $Ecore->InitPlugin($this->Plugin_Name,$this->Plugin_Version);

            if (is_array($Result)) {
                array_merge($this->Tmp_Data, $Result);
            } else {
                $this->Tmp_Data['init'] = $Result;
            }
        }
    }

    //插件缓存设置
    protected function PluginCache()
    {
        return DI::FileCache('Plugin_' . $this->Plugin_Name);
    }

    //插件数据IO
    protected function PluginSave()
    {
        return new Save($this->Plugin_Name);
    }

    //插件信息获取
    public function GetPluginName()
    {
        return $this->Plugin_Name;
    }
    public function GetPluginVersion()
    {
        return $this->Plugin_Version;
    }
    public function GetPluginAuthor()
    {
        return $this->Plugin_Author;
    }
    public function GetPluginAbout()
    {
        return $this->Plugin_About;
    }
}
