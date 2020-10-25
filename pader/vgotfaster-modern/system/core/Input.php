<?php
/**
 * VgotFaster PHP Framework
 *
 * @package VgotFaster
 * @author pader
 * @copyright Copyright (c) 2009-2010, VGOT.NET
 * @link http://www.vgot.net/ http://vgotfaster.googlecode.com
 * @filesource
 */

namespace VF\Core;

/**
 * VgotFaster ����ӿ���
 *
 * �û��������ݵĽ��պ͹���
 *
 * @package VgotFaster
 * @subpackage Library
 * @author pader
 */
class Input {

	public $ip = '';

	public function __construct()
	{
		$phpMQG = get_magic_quotes_gpc();
		define('MAGIC_QUOTES_GPC',$GLOBALS['CONFIG']['config']['open_magic_quotes_gpc']);

		//���ӿ������
		if (MAGIC_QUOTES_GPC and !$phpMQG) {
			$magic = 'deepAddslashes';
		} elseif (!MAGIC_QUOTES_GPC and $phpMQG) {
			$magic = 'deepStripslashes';
		}

		if (isset($magic)) {
			foreach (array('_GET','_POST','_REQUEST','_SERVER','_COOKIE') as $var) {
				isset($GLOBALS[$var]) AND $GLOBALS[$var] = $this->$magic($GLOBALS[$var]);
			}
		}
	}

	/**
	 * Get Input Data
	 *
	 * Get request data and you can use function to filter data
	 *
	 * @param string $name
	 * @param string $functions Use function to filter request, example: 'trim|html2text|stripslashes'
	 * @return mixed Filtered request content
	 */
	public function get($name, $functions=NULL) { return $this->fetchGPC($_GET, $name, $functions); }
	public function post($name, $functions=NULL) { return $this->fetchGPC($_POST, $name, $functions); }
	public function cookie($name, $functions=NULL) { return $this->fetchGPC($_COOKIE, $name, $functions); }
	public function server($name, $functions=NULL) { return $this->fetchGPC($_SERVER, $name, $functions); }
	public function request($name, $functions=NULL) { return $this->fetchGPC($_REQUEST, $name, $functions); }

	/**
	 * Get Input Data From GET or Post
	 *
	 * It will fetch GET first, if none set int GET, then fetch POST
	 *
	 * @param string $name
	 * @param null $functions
	 * @return mixed
	 */
	public function gp($name,$functions=NULL) {
		return isset($_GET[$name]) ? $this->fetchGPC($_GET, $name, $functions) : $this->fetchGPC($_POST, $name, $functions);
	}

	/**
	 * ��ȡURI��
	 *
	 * @param int $number ��ȡ�ڼ���
	 * @return URI segment
	 */
	public function segment($number)
	{
		$number--;
		$uri = $this->uri('uri');
		return isset($uri[$number]) ? $uri[$number] : NULL;
	}

	/**
	 * ��ȡ�����б�
	 *
	 * �������� list() �Ѳ������������
	 * ����list($id,$page,$style) = $this->input->params(3);
	 * ʹ�� function action($id='',$page='',$style='') ��ȱ����������趨ÿ��������Ĭ��ֵ���ȽϷ���
	 * ���û���趨Ĭ��ֵ���� PHP ���ڲ���������ʱ����
	 *
	 * @param int|bool $length ���ز�������ĳ��ȣ����������鳤�Ȳ���ʱ�����Զ�ʹ�� NULL ��䵽�˳�����ȷ�� list() ����������
	 * @return array Params
	 */
	public function params($length=TRUE)
	{
		if ($length === TRUE or isset($GLOBALS['URI']['params'][$length-1])) {
			return $GLOBALS['URI']['params'];
		} else {
			$params = $this->uri('params');
			return array_pad($params, $length, NULL);
		}
	}

	/**
	 * ������ name/value/name/value ����ʽ������鷵��
	 *
	 * @param bool $pos ��Ϊ����ʱΪ�� URI �ڼ�������,Ĭ�ϴӶ���֮��
	 * @return array URI Assoc Data
	 */
	public function assoc($pos=TRUE)
	{
		if ($pos === TRUE) {
			$params = $this->uri('params');
		} else {
			$params = $pos > 1 ? array_slice($this->uri('uri'),$pos-1) : $this->uri('uri');
		}

		$assoc = array();
		foreach (array_chunk($params,2) as $row) {
			$assoc[$row[0]] = isset($row[1]) ? $row[1] : NULL;
		}

		return $assoc;
	}

	/**
	 * Get URI Parameter
	 *
	 * file|controller|action|params|string|uri|route
	 *
	 * @param string $key
	 * @return string|array
	 */
	public function uri($key=NULL)
	{
		if (is_null($key)) {
			return $GLOBALS['URI'];
		} else {
			return $GLOBALS['URI'][$key];
		}
	}

	/**
	 * ��ȡ������IP��ַ
	 *
	 * @return IP Address
	 */
	public function ipAddress()
	{
		if($this->ip != '') return $this->ip;

		if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $this->server('HTTP_CLIENT_IP');
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $this->server('HTTP_X_FORWARDED_FOR');
		elseif(!empty($_SERVER['REMOTE_ADDR'])) $ip = $this->server('REMOTE_ADDR');
		else $ip = '';

		preg_match('/[\d\.]{7,15}/', $ip, $ips);
		$this->ip = !empty($ips[0]) ? $ips[0] : 'unknown';
		unset($ips);

		return $this->ip;
	}

	/**
	 * ��ȡHTML�е��ı�����,���HTML����
	 *
	 * @param string $str HTML code
	 * @return string
	 */
	public function html2text($str)
	{
		$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU",'',$str);
		$str = str_replace(array('<br />','<br>','<br/>'), "\n", $str);
		$str = strip_tags($str);
		return $str;
	}

	/**
	 * ����Ƿ���ȷ�ύ�˱� //debug �˺��������ڵ��Խ׶�
	 *
	 * @param string $var ��Ҫ���ı���
	 * @param bool $allowget �Ƿ�����GET��ʽ
	 * @param bool $seccodecheck ��֤�����Ƿ���
	 * @return bool
	 */
	public function isSubmit($var, $allowget=false, $seccodecheck=false)
	{
		if(empty($GLOBALS['_REQUEST'][$var])) {
			return FALSE;
		} else {
			global $_SERVER;
			if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) ||
				preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
				return TRUE;
			} else {
				showError('submit_invalid');//debug �˴���ȱ��
			}
		}
	}

	/**
	 * Array Deep Add Slashes
	 *
	 * @param array|string $var
	 * @return array|string
	 */
	public function deepAddslashes($var)
	{
		if (is_array($var)) {
			foreach($var as $key => $val) {
				$var[$key] = $this->deepAddslashes($val);
			}
			return $var;
		} else return addslashes($var);
	}

	/**
	 * Array Deep Strip Slashes
	 *
	 * @param array|string $var
	 * @return
	 */
	public function deepStripslashes($var)
	{
		if(is_array($var)) {
			foreach($var as $key => $val) {
				$var[$key] = $this->deepStripslashes($val);
			}
			return $var;
		} else return stripslashes($var);
	}



	/**
	 * ����ָ��ȫ�������ֵ
	 *
	 * @param array $GPC
	 * @param string $key
	 * @param bool $functions
	 * @return mixed Var
	 */
	private function fetchGPC(&$GPC, $key, $functions=NULL)
	{
		if (!isset($GPC[$key])) return null;

		$var = $GPC[$key];

		if (!$functions) {
			return $var;
		} else {
			if (!is_array($functions)) {
				$functions = explode('|',$functions);
			}
			foreach ($functions as $f) {
				if (function_exists($f)) {
					$var = $f($var);
				} elseif (method_exists($this,$f)) {
					$var = $this->$f($var);
				} else {
					showError('Unabled to call function '.$f.'()', true, 2);
				}
			}
			return $var;
		}
	}

}
