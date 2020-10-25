<?php
/**
 * VgotFaster PHP Framework
 *
 * @package VgotFaster
 * @author pader
 * @copyright Copyright (c) 2009-2015, VGOT.NET
 * @link http://www.vgot.net/ http://vgotfaster.googlecode.com
 * @filesource
 */

namespace VF\Core;

/**
 * VgotFaster Loader
 *
 * @package VgotFaster
 * @subpackage Core
 * @author pader
 */
class Loader {

	protected $models = array();
	protected $_view = array();
	protected $extraNotInclude = array();  //���������õ�ϵͳ����[����]
	protected $activeDBLink = array();

	public function __construct()
	{
		//��ȡ��������Ժͷ����б��ϲ������������õ�ϵͳ�����б���
		$this->extraNotInclude = array_merge($this->extraNotInclude, array_keys(get_object_vars($this)));
	}

	/**
	 * Load and initialize library
	 *
	 * @param string|array $libs Library name or names array
	 * @param ... [optional] Library construct params..
	 * @return Object
	 */
	public function library($libs)
	{
		$args = func_get_args();
		array_splice($args, 1, 0, ''); //insert '' to arg2 $instance

		if (is_array($libs)) {
			foreach($libs as $n => $library) {
				if ($library == 'database') {
					$this->database();
					continue;
				}

				is_numeric($n) && $n = '';  //֧��ʹ�ü���������
				$args[0] = $library;
				$args[1] = $n;

				call_user_func_array(array($this, 'libraryAs'), $args);
			}

		} else {
			if($libs != 'database') {
				return call_user_func_array(array($this, 'libraryAs'), $args);
			} else {
				return $this->database();
			}

		}
	}

	/**
	 * Load a library as another instance name
	 *
	 * @param string $library
	 * @param string $instance Set another instance name
	 * @param ... [optional] Library construct params
	 * @return mixed
	 */
	public function libraryAs($library, $instance='')
	{
		if ($instance == '') {
			$lsp = strpos($library, '/');
			$instance = $lsp === false ? $library : substr($library, $lsp+1);
		}

		$args = func_get_args();
		array_splice($args, 0, 2);

		_systemLog("Try to load library '$library' as '$instance'");

		$VF =& getInstance();

		if ($args || empty($VF->$instance)) {
			_systemLog("Load library '$library' instance '$instance'");

			$filename = $library.'.php';
			$sysLibFile = SYSTEM_PATH.'/libraries/'.ucfirst($filename);
			$appLibFile = APPLICATION_PATH.'/libraries/'.$filename;

			$lsp = strrpos($library, '/');
			$lsp !== FALSE && $library = substr($library, $lsp+1);
			$library = ucfirst($library);

			$className = '';

			if (is_file($sysLibFile)) {
				include_once $sysLibFile;
				$className = '\\VF\\Library\\'.$library;  //Example: \VF\Class
				_systemLog("Load System Library '$library'");
			}

			if (is_file($appLibFile)) {
				include_once $appLibFile;
				$className = '\\'.$library;
				_systemLog("Load Application Library '$library'");
			}

			if ($className == '') {
				showError('No Found Class File: '.$filename);
			}

			if (count($args) == 0) {
				$VF->$instance = new $className;
			} else {
				$class = new \ReflectionClass($className);
				$VF->$instance = $class->newInstanceArgs($args);
			}

			$this->assignToModels($instance);
		}

		return $VF->$instance;
	}

	/**
	 * Load Model(s)
	 *
	 * @param string|array $models Model name(s) (array)
	 * @param string $instance Set another instance name
	 * @return object|void
	 */
	public function model($models, $instance='')
	{
		if (is_array($models)) {
			foreach ($models as $n => $model) {
				if (is_numeric($n)) $n = '';  //֧��ʹ�ü���������
				$this->injectModel($model,$n);
			}
		} else {
			return $this->injectModel($models, $instance);
		}
	}

	/**
	 * �� model() �������ã�����ģ��
	 *
	 * @param mixed $name
	 * @param string $instance Set another instance name
	 * @return Object
	 */
	protected function injectModel($name, $instance='')
	{
		$file = APPLICATION_PATH.'/models/'.$name.'.php';

		$lsp = strrpos($name, '/'); //Last Slash Point
		$lsp !== false && $name = substr($name, $lsp+1);

		$instance = $instance == '' ? $name : $instance;
		_systemLog("Try to load model '$name' as '$instance'");

		$VF =& getInstance();
		$baseProps = get_object_vars($VF);

		if (!isset($baseProps[$instance]) || empty($VF->$instance)) {
			$lsp = strrpos($file, '/');
			is_file($file) ? include_once $file : showError('No Found Model File: '.substr($file, $lsp+1));

			_systemLog("Load model '$name' instance '$instance'");

			$class = '\\Model\\'.ucfirst($name);
			$VF->$instance = new $class;
			$VF->$instance->_injectBroadcast($instance);

			//��ʵ����ģ��֮����� _assignLibraries() ��Ϊ����ģ�͵Ĺ��캯���м�����ģ�ͻ������ʱ���ڽ������ķ���������ʹ��
			//$VF->$instance->_assignLibraries();

			$this->models[] = $instance;  //��¼���������ģ���б���
		}

		return $VF->$instance;
	}

	/**
	 * Assign Libraries To Models
	 *
	 * @param string $instance
	 * @return void
	 */
	protected function assignToModels($instance)
	{
		$VF =& getInstance();
		foreach($this->models as $model) {
			$VF->$model->_assignLibraries($instance);
		}
	}

	/**
	 * Load Helper Function(s)
	 *
	 * @param string|array $params Helper names
	 * @return void
	 */
	public function helper($params)
	{
		$helpers = is_array($params) ? $params : array($params);

		foreach ($helpers as $name) {
			$sysHelperFile = SYSTEM_PATH.'/helpers/'.$name.'.php';
			$appHelperFile = APPLICATION_PATH.'/helpers/'.$name.'.php';

			$loaded = false;

			if (is_file($sysHelperFile)) {
				include_once $sysHelperFile;
				$loaded = true;
				_systemLog("Load system helper '$name'");
			}
			
			if (is_file($appHelperFile)) {
				include_once $appHelperFile;
				$loaded = true;
				_systemLog("Load application helper '$name'");
			}
			
			//���һ���ļ���������,�Ǿ���û�ҵ��
			if (!$loaded) {
				showError('No Found Helper File: '.$name.'.php');
			}
		}
	}

	/**
	 * Load View
	 *
	 * @param string $name ��ͼ���·��,�޺�׺
	 * @param array|object $vars Ҫ��ѹ����ͼ�еı���
	 * @param bool $return
	 * @update 16:20 2009/12/19
	 * @return string
	 */
	public function view($name,$vars=NULL,$return=FALSE)
	{
		$viewFile = APPLICATION_PATH.'/views/'.$name.'.'.getInstance()->config->get('config','view_file_extension');
		return $this->parseView($viewFile,$name,$vars,$return);
	}

	/**
	 * Load HTML Template
	 *
	 * @param string $file
	 * @param array|object $vars
	 * @param bool $return
	 * @return string
	 * @update 21:40 2009/12/20
	 */
	public function template($file, $vars=NULL, $return=FALSE)
	{
		$tplFile = APPLICATION_PATH.'/views/'.$file.'.'.getInstance()->config->get('config','template_file_extension');
		$objFile = APPLICATION_PATH.'/data/template_cache/'.str_replace('/',' ',$file).'.tpl.php';

		//���ģ���ļ��ȱ����ļ���,�����ģ�������±���
		if (@filemtime($tplFile) > @filemtime($objFile)) {
			$T = loadClass('template');
			$T->complie($tplFile, $objFile);
			_systemLog("Complite Template File: \"$tplFile\" -> \"$objFile\"");
		}

		return $this->parseView($objFile, $file, $vars, $return);
	}

	/**
	 * Load View Vars
	 *
	 * @param array|object|string $vars
	 * @param mixed $value
	 * @return void
	 */
	public function vars($vars, $value=NULL)
	{
		isset($this->_view['vars']) OR $this->_view['vars'] = array();
		if (empty($vars)) return;

		if ((is_array($vars) OR is_object($vars))) {
			$this->_view['vars'] = array_merge($this->_view['vars'], (array)$vars);
		} else {
			$this->_view['vars'][$vars] = $value;
		}
	}

	/**
	 * ��������ѹ����ͼ��ģ��
	 *
	 * @param string $file
	 * @param string $viewName
	 * @param array|object $vars
	 * @param bool $_VF_returnBuffer
	 * @return string
	 */
	protected function parseView($file, $viewName, $vars=NULL, $_VF_returnBuffer=FALSE)
	{
		//�������ŵ����������Բ�����ѹ�ı�������
		$this->_view = array_merge($this->_view, array('file'=>$file, 'name'=>$viewName));
		$this->vars($vars);
		unset($file, $viewName, $vars);

		if (!file_exists($this->_view['file'])) {
			$tmp = explode('/', $this->_view['file']);
			showError("No Found View File '".end($tmp)."'");
		}

		//ȥ��������Ŀ�кͱ��༰ϵͳ����ͬ��������
		$VF =& getInstance();
		$VFObjects = array_diff(array_keys(get_object_vars($VF)), $this->extraNotInclude);
		foreach ($VFObjects as $key) {
			if (!isset($this->$key)) {
				$this->$key =& $VF->$key;
			}
		}
		unset($key, $VFObjects);

		//��ѹ����
		extract($this->_view['vars'], EXTR_OVERWRITE);

		//Buffer to faster and get return
		ob_start();

		//���������ò�֧�ֶ̱�ǩʱ�Ĵ���
		if((bool)@ini_get('short_open_tag') === FALSE && PHP_VERSION_ID < 50400 && $VF->config->get('config','open_short_tag') == TRUE) {
			eval('?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($this->_view['file']))));
			_systemLog("Include View File [Eval Short Open Tag] '{$this->_view['file']}'");
		} else {
			include $this->_view['file'];
			_systemLog("Include View File '{$this->_view['file']}'");
		}

		if($_VF_returnBuffer) {
			$buffer = ob_get_contents(); ob_end_clean();
			_systemLog("Return Buffer From View '{$this->_view['name']}'");
			return $buffer;
		} else ob_end_flush();
	}

	/**
	 * Load MySQL Database Operation Library
	 *
	 * @param string|array $config ConfigName|ConfigArray
	 * @param string $instance
	 * @return Object
	 */
	public function &database($config=NULL, $instance='db')
	{
		$VF =& getInstance();
		$configure = $VF->config->get('database');

		if (!is_array($config)) {
			if ($config === null) {
				$configName = $configure['default_config'];
			} else {
				$configName = $config;
			}
			if (!isset($configure[$configName])) {
				showError("No found database config '$configName'");
			}
			$config = $configure[$configName];
		} else {
			$configName = md5(http_build_query($config));
		}

		//�µ�����
		if (!isset($this->activeDBLink[$configName])) {
			$class = $configure['use_pdo_driver'] ? 'database' : 'mysql';
			$class = ucfirst($class);

			require_once SYSTEM_PATH.'/database/'.$class.'.php';
			
			$class = '\\VF\\Database\\'.$class;
			$VF->$instance = new $class();
			$VF->$instance->connect($config);
			
			_systemLog("Load database '$configName' instance as '$instance'");

		//ͬ�������ã��µĹ�����
		} elseif ($this->activeDBLink[$configName] != $instance) {
			$VF->$instance =& $VF->{$this->activeDBLink[$configName]}; //ֱ�ӿ���
			
			_systemLog("Copy database instance '$configName' as '$instance'");
			
		} elseif (!$VF->$instance->ping()) {
			$VF->$instance->connect($config);
		}

		$this->activeDBLink[$configName] = $instance;
		$this->assignToModels($instance);

		return $VF->$instance;
	}

	/**
	 * AutoLoad
	 *
	 * ���� autoload.php ���ã��Զ�������ص���⣬ģ�ͺͺ���
	 *
	 * @return void
	 */
	public function autoload()
	{
		$VF =& getInstance();
		$autoload = $VF->config->get('autoload');
		$autoload['helpers'] AND $this->helper($autoload['helpers']);
		$autoload['libraries'] AND $this->library($autoload['libraries']);
		$autoload['models'] AND $this->model($autoload['models']);
	}

}
