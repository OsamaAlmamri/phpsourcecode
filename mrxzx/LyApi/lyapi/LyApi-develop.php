<?php

namespace LyApi;

use APP\program\Ecore;
use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;
use LyApi\core\error\OtherException;
use LyApi\core\request\Request;
use LyApi\tools\Config;

class LyApi
{

    // LyAPI信息：
    public static $version = "1.7.0";


    //普通对象函数
    private $appConfig = [];
    private $Response_Code = 400;

    public function __construct($Config = [])
    {
        // 对配置进行处理
        if (!array_key_exists("Http_Status_Set", $Config)) {
            $Config['Http_Status_Set'] = true;
        }

        $Config["apiConfig"] = Config::getConfig("api","");

        $this->appConfig = $Config;
    }

    // 运行接口程序
    public function Run()
    {
        $Config = $this->appConfig;
        $RespCode = self::output($Config['Http_Status_Set']);
        $this->Response_Code = $RespCode;
        return $RespCode;
    }


    public function output($http_status_set = true, $env_focus = "API")
    {

        // 系统配置文件读取
        $apiConfig = Config::getConfig("api", "");
        $funConfig = Config::getConfig("func", "");

        // 启动 ECore 拓展程序
        $usiEcore = null;
        if ($funConfig['USING_ECORE']) {
            $usiEcore = new Ecore();
        }

        $resopnse = $apiConfig['DEFAULT_RESPONSE'];
        $methods = $apiConfig['ACCESS_METHODS'];
        $service = $apiConfig['GET_METHOD_SETTING']['DEFAULT_SERVICE'];

        if ($methods == "URL" || Request::Request($service) != "") {
        } else {
        }
    }

    public function createStructure(){
        $apiConfig = $this->appConfig['apiConfig'];
        $resopnse = $apiConfig['DEFAULT_RESPONSE'];
    }


    public function showError($focus = "API", $data = array())
    {
        if($focus != "API"){
            $DirPath = LyApi . '/app/view/error/';
            if (is_file($DirPath . $data . '.html')) {
                return file_get_contents($DirPath . $data . '.html');
            } else {
                return file_get_contents($DirPath . 'default.html');
            }
        }else{
            // return ;
        }
    }
}
