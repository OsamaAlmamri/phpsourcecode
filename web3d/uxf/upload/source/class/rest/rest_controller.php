<?php

/**
 * Rest风格的控制器
 * 
 * 具体的控制器类名应该是复数形式，一个控制器类代表一个资源
 * 
 * 理论上应该只有一个对外的action，各种动作的相应由内部响应
 */
class Rest_Controller extends Mvc_Controller {

    protected $_method = ''; // 当前请求类型
    protected $_type = ''; // 当前资源类型
    
    // REST允许的请求类型列表
    protected   $allowMethods  = array('get', 'post', 'put', 'delete'); 
    // REST默认请求类型
    protected   $defaultMethod = 'get';
    // REST允许请求的资源类型列表
    protected   $allowTypes  = array('html', 'xml', 'json', 'rss'); 
    // 默认的资源类型
    protected   $defaultType  =  'html';
    // REST允许输出的资源类型列表
    protected   $allowOutputTypes = array(  
                    'xml' => 'application/xml',
                    'json' => 'application/json',
                    'html' => 'text/html',
                );
    
    const REST_METHOD_LIST = 'get, post, put, delete';
    const REST_CONTENT_TYPE_LIST = 'json, xml, php';
    
    const REST_DEFAULT_TYPE = 'json';
    const REST_DEFAULT_METHOD = 'get';
    const REST_OUTPUT_TYPE_JSON = 'json';
    const REST_OUTPUT_TYPE_XML = 'xml';
    
    const TMPL_FILE_NAME = 'rest';
    const DEFAULT_FILTER = '';

    /**
     * 架构函数 取得模板对象实例
     * @access public
     */
    public function __construct($module_id, $id, $act_id) {
        parent::__construct($module_id, $id, $act_id);
        
        if (!defined('__EXT__'))
            define('__EXT__', '');

        // 资源类型检测
        if ('' == __EXT__) { // 自动检测资源类型
            $this->_type = $this->getAcceptType();
        } elseif (false === in_array($this->allowTypes, __EXT__)) {
            // 资源类型非法 则用默认资源类型访问
            $this->_type = $this->defaultType;
        } else {
            // 检测实际资源类型
            if ($this->getAcceptType() == __EXT__) {
                $this->_type = __EXT__;
            } else {
                $this->_type = $this->defaultType;
            }
        }

        // 请求方式检测
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if (false === in_array($this->allowMethods, $method)) {
            // 请求方式非法 则用默认请求方法
            $method = $this->defaultMethod;
        }
        $this->_method = $method;

        //控制器初始化
        if (method_exists($this, '_initialize'))
            $this->_initialize();
    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args) {
        if (0 === strcasecmp($method, $this->_actPrefix . ucfirst($this->_actId))) {
            if (method_exists($this, $method . '_' . $this->_method . '_' . $this->_type)) { // RESTFul方法支持
                $fun = $method . '_' . $this->_method . '_' . $this->_type;
                $this->$fun();
            } elseif ($this->_method == $this->defaultMethod && method_exists($this, $method . '_' . $this->_type)) {
                $fun = $method . '_' . $this->_type;
                $this->$fun();
            } elseif ($this->_type == $this->defaultType && method_exists($this, $method . '_' . $this->_method)) {
                $fun = $method . '_' . $this->_method;
                $this->$fun();
            } elseif (method_exists($this, '_empty')) {
                // 如果定义了_empty操作 则调用
                $this->_empty($method, $args);
            } elseif (file_exists_case(self::TMPL_FILE_NAME)) {
                // 检查是否存在默认模版 如果有直接输出模版
                $this->display();
            } else {
                // 抛出异常
                throw new Exception('_ERROR_ACTION_' . $this->_actId);
            }
        } else {
            switch (strtolower($method)) {
                // 获取变量 支持过滤和默认值 调用方式 $this->_post($key,$filter,$default);
                case '_get':
                    $input = & $_GET;
                    break;
                case '_post':
                    $input = & $_POST;
                    break;
                case '_put':
                case '_delete':
                    parse_str(file_get_contents('php://input'), $input);
                    break;
                case '_request':
                    $input = & $_REQUEST;
                    break;
                case '_session':
                    $input = & $_SESSION;
                    break;
                case '_cookie':
                    $input = & $_COOKIE;
                    break;
                case '_server':
                    $input = & $_SERVER;
                    break;
                default:
                    throw new Exception(get_class($this) . ':' . $method . '_METHOD_NOT_EXIST_');
            }
            if (isset($input[$args[0]])) { // 取值操作
                $data = $input[$args[0]];
                $fun = $args[1] ? $args[1] : self::DEFAULT_FILTER;
                $data = $fun($data); // 参数过滤
            } else { // 变量默认值
                $data = isset($args[2]) ? $args[2] : NULL;
            }
            return $data;
        }
    }

    /**
     * 输出返回数据
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     * @param integer $code HTTP状态
     * @return void
     */
    protected function response($data, $type = '', $code = 200) {
        $this->sendHttpStatus($code);

        echo($this->encodeData($data, strtolower($type)));
        exit;
    }

    // 输出类型    
    /**
     * 设置页面输出的CONTENT_TYPE和编码
     * @access public
     * @param string $type content_type 类型对应的扩展名
     * @param string $charset 页面输出编码
     * @return void
     */
    public function setContentType($type, $charset = '') {
        if (headers_sent())
            return;

        if (empty($charset)) {
            $charset = 'utf-8';
        }

        $type = strtolower($type);
        if (isset($this->allowOutputTypes[$type])) //过滤content_type
            header('Content-Type: ' . $this->allowOutputTypes[$type] . '; charset=' . $charset);
    }

    /**
     * 编码数据
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     * @return string
     */
    protected function encodeData($data, $type = '') {
        if (empty($data))
            return '';
        if ('json' == $type) {
            // 返回JSON数据格式到客户端 包含状态信息
            $data = json_encode($data);
        } elseif ('xml' == $type) {
            // 返回xml格式数据
            $data = xml_encode($data);
        } elseif ('php' == $type) {
            $data = serialize($data);
        }// 默认直接输出
        
        $this->setContentType($type);
        header('Content-Length: ' . strlen($data));
        
        return $data;
    }

    /**
     * 发送Http状态信息
     * 
     * @staticvar array $_status
     * @param int $status 
     */
    protected function sendHttpStatus($status) {
        static $_status = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ', // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if (isset($_status[$code])) {
            header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:' . $code . ' ' . $_status[$code]);
        }
    }

    /**
     * 获取当前请求的Accept头信息
     * @return string
     */
    protected function getAcceptType() {
        $type = array(
            'html' => 'text/html,application/xhtml+xml,*/*',
            'xml' => 'application/xml,text/xml,application/x-xml',
            'json' => 'application/json,text/x-json,application/jsonrequest,text/json',
            'js' => 'text/javascript,application/javascript,application/x-javascript',
            'css' => 'text/css',
            'rss' => 'application/rss+xml',
            'yaml' => 'application/x-yaml,text/yaml',
            'atom' => 'application/atom+xml',
            'pdf' => 'application/pdf',
            'text' => 'text/plain',
            'png' => 'image/png',
            'jpg' => 'image/jpg,image/jpeg,image/pjpeg',
            'gif' => 'image/gif',
            'csv' => 'text/csv'
        );

        foreach ($type as $key => $val) {
            $array = explode(',', $val);
            foreach ($array as $k => $v) {
                if (stristr($_SERVER['HTTP_ACCEPT'], $v)) {
                    return $key;
                }
            }
        }
        return false;
    }

}
