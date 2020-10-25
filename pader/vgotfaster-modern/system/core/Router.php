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
 * VgotFaster ϵͳ·����
 *
 * ���� URI�������÷ַ�·�ɷ������Ӧ�Ŀ������������������ URI �еĲ���
 * ע: ·������ʵ����ʱ�Ϳ�ʼ����!
 *
 * @package VgotFaster
 * @author pader
 */
class Router {

	protected $config;
	protected $URIString;
	protected $trueURIString = '';
	protected $URI;
	protected $controllerFile;
	protected $controllerName;
	protected $controllerAction;
	protected $visitParams = array();

	/**
	 * SYS_Router::__construct()
	 *
	 * ·��������ʱ����������û��ʵ���������Բ�����Ҳ��ʹ�� getInstance() ��ȡ����
	 * �����������ֱ��ʹ�� loadClass() ����
	 */
	public function __construct()
	{
		$config = getConfig('config');
		$routes = getConfig('routes');

		$this->config = array(
			'defaultController' => $config['default_controller'],
			'method'            => strtoupper($config['router_method']),
			'params'            => $config['router_get_params'],
			'separator'         => $config['uri_separator'],
			'suffix'            => $config['url_suffix'],
			'allowedRegular'    => $config['uri_allowed_regular'],
			'routes'            => isset($routes) ? $routes : NULL
		);
	}

	/**
	 * analysis
	 *
	 * @return array
	 */
	public function analysis()
	{
		//֧��ʹ������ļ��Զ��������Ŀ¼·��
		$controllerDir = defined('CONTROLLER_PATH') ? CONTROLLER_PATH : APPLICATION_PATH.'/controllers';

		$this->URIExport();  //��ѹ URI ������ $this->URI

		// ---  ��ʼ�ļ�������·�� ---
		$path = join('/', $this->URI);

		//ֱ�ӷ���Ŀ¼,����Ŀ¼��Ĭ�Ͽ�����
		if (is_dir($dir = $controllerDir.'/'.$path)) {
			$controllerName = $this->config['defaultController'];  //ControllerName
			$controllerFile = "{$dir}/{$controllerName}.php";

		//����ָ���������ļ�
		} elseif (is_file($file = "{$controllerDir}/{$path}.php")) {
			$controllerName = strtolower(end($this->URI));
			$controllerFile = $file;

		//���ʿ������ļ����Ҵ��в���
		} else {
			$lpath = $controllerDir;
			$export = $this->URI;

			//��������Ѱ�ҿ������ļ�
			foreach ($export as $i => $seg) {
				$lpath .= '/'.$seg;  //�²�ļ����ִ�
				$controllerFile = $lpath.'.php';  //�²���ļ�
				unset($export[$i]);
				if (is_file($controllerFile)) {
					$controllerName = $seg;
					$this->visitParams = array_values($export);  //��ȡ�����Ԥ����Ĳ���
					break;
				}
			}
		}

		$ControllerFile = str_replace('//', '/', $controllerFile);  //�����������ļ�·���п��ܳ��ֵ�����б�ܵĴ���
		if (!is_file($ControllerFile)) {  //�������ļ��������򷵻� FALSE
			return FALSE;
		}

		//��ز������뵽��������
		$this->controllerFile = $controllerFile;
		$this->controllerName = $controllerName;
		$this->controllerAction = $this->visitParams ? array_shift($this->visitParams) : 'index';

		//����·�ɷ����������
		return array(
			'file' => $this->controllerFile,
			'controller' => $this->controllerName,
			'action' => $this->controllerAction,
			'params' => $this->visitParams,
			'string' => $this->URIString,
			'uri' => $this->URI,
			'route' => $this->trueURIString
		);
	}

	/**
	 * URI ��ѹ����
	 *
	 * ����������ָ���ķ�ʽ��ȡ�������ʽ����URI��������
	 *
	 * @return void
	 */
	private function URIExport()
	{
		$split = $this->config['separator'];

		switch($this->config['method'])
		{
			case 'PATH_INFO': $uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : ''; break;
			case 'QUERY_STRING': $uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''; break;
			case 'GET':
				$p = $this->config['params'];
				$controller = isset($_GET[$p['controller']]) ? $_GET[$p['controller']] : $this->config['defaultController'];
				$action = isset($_GET[$p['action']]) ? $_GET[$p['action']] : '';
				$uri = empty($action) ? $controller : $controller.$split.$action;
			break;
			default: showError('Unsupport Router Method');
		}

		$this->URIString = trim($uri, $split.'/');

		if ($this->URIString) {
			//���URI�Ϸ���
			if ($this->URIString && !preg_match($this->config['allowedRegular'], $this->URIString)) {
				showError('URI Not Allow!');
			}
			$this->trueURIString = $this->removeSuffix($this->URIString);  //��׺����
			$this->trueURIString = $this->translateRoute($this->trueURIString);  //·��ת��
			$this->URI = explode($split, $this->trueURIString);
		}

		if (!is_array($this->URI) and count($this->URI) == 0) {
			$this->URI = array($this->config['defaultController']);
		}
	}

	/**
	 * ת��·��
	 *
	 * ��������ƥ�䲢ת����ʵ��·��
	 *
	 * @param mixed $uri
	 * @return string Orig uri
	 */
	private function translateRoute($uri)
	{
		if (is_array($this->config['routes']) && count($this->config['routes']) > 0) {
			foreach ($this->config['routes'] as $exp => $route) {
				$exp = '#^'.$exp.'$#';
				if (preg_match($exp, $uri)) {
					return preg_replace($exp, $route, $uri);
				} elseif($uri == $exp) {
					return $route;
				} else continue;
			}
		}

		return $uri;
	}

	/**
	 * URI ��׺����
	 *
	 * @param mixed $uri
	 * @return string
	 */
	private function removeSuffix($uri)
	{
		if (!$this->config['suffix']) {
			return $uri;
		}
		if (!strpos($uri, $this->config['suffix'])) {
			return $uri;
		} else {
			$suffix = preg_quote($this->config['suffix']);
			return preg_replace("/$suffix$/",'',$uri);
		}
	}

}
