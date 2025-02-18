<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * ThinkPHP 引导类
 */
class Think
{

	// 类映射
	private static $_map = array();

	// 实例化对象
	private static $_instance = array();

	/**
	 * 应用程序初始化
	 *
	 * @access public
	 * @return void
	 */
	static public function start()
	{
		// 注册AUTOLOAD方法
		spl_autoload_register('Think::autoload');
		// 设定错误和异常处理
		register_shutdown_function('Think::fatalError');
		set_error_handler('Think::appError');
		set_exception_handler('Think::appException');

		//注册别名便于自动加载
		Think::addMap(array(
			'Storage' => CORE_PATH . 'Storage' . EXT,
			'App' => CORE_PATH . 'App' . EXT,
			'Behavior' => CORE_PATH . 'Behavior' . EXT,
			'Cache' => CORE_PATH . 'Cache' . EXT,
			'Controller' => CORE_PATH . 'Controller' . EXT,
			'Db' => CORE_PATH . 'Db' . EXT,
			'Dispatcher' => CORE_PATH . 'Dispatcher' . EXT,
			'Exception' => CORE_PATH . 'Exception' . EXT,
			'Hook' => CORE_PATH . 'Hook' . EXT,
			'Log' => CORE_PATH . 'Log' . EXT,
			'Model' => CORE_PATH . 'Model' . EXT,
			'Route' => CORE_PATH . 'Route' . EXT,
			'Template' => CORE_PATH . 'Template' . EXT,
			'View' => CORE_PATH . 'View' . EXT,
		));
		// 初始化文件存储方式
		Storage::connect(STORAGE_TYPE);
		$runtimefile = RUNTIME_PATH . APP_MODE . '~runtime.php';
		if (!APP_DEBUG && Storage::has($runtimefile, 'runtime')) {
			Storage::load($runtimefile, null, 'runtime');
		} else {
			if (Storage::has($runtimefile, 'runtime'))
				Storage::unlink($runtimefile, 'runtime');
			$content = '';
			// 读取应用模式
			$mode = include is_file(CONF_PATH . 'core.php') ? CONF_PATH . 'core.php' : MODE_PATH . APP_MODE . '.php';
			// 加载核心函数
			foreach ($mode['core'] as $file) {
				if (is_file($file)) {
					include $file;
				}
			}
			// 加载核心类
			if (!APP_DEBUG) {
				foreach ($mode['build'] as $file) {
					if (is_file($file)) {
						$content .= compile($file);
					}
				}
			}

			// 加载应用模式配置文件
			foreach ($mode['config'] as $key => $file) {
				is_numeric($key) ? C(include $file) : C($key, include $file);
			}

			// 读取当前应用模式对应的配置文件
			if ('common' != APP_MODE && is_file(CONF_PATH . 'config_' . APP_MODE . '.php'))
				C(include CONF_PATH . 'config_' . APP_MODE . '.php');

			// 加载模式别名定义
			if (isset($mode['alias'])) {
				self::addMap(is_array($mode['alias']) ? $mode['alias'] : include $mode['alias']);
			}

			// 加载应用别名定义文件
			if (is_file(CONF_PATH . 'alias.php'))
				self::addMap(include CONF_PATH . 'alias.php');

			// 加载模式行为定义
			if (isset($mode['tags'])) {
				Hook::import(is_array($mode['tags']) ? $mode['tags'] : include $mode['tags']);
			}

			// 加载应用行为定义
			if (is_file(CONF_PATH . 'tags.php'))
				// 允许应用增加开发模式配置定义
				Hook::import(include CONF_PATH . 'tags.php');

			// 加载框架底层语言包
			L(include THINK_PATH . 'Lang/' . strtolower(C('DEFAULT_LANG')) . '.php');

			if (!APP_DEBUG) {
				$content .= "\n Think::addMap(" . var_export(self::$_map, true) . ");";
				$content .= "\nL(" . var_export(L(), true) . ");\nC(" . var_export(C(), true) . ');Hook::import(' . var_export(Hook::get(), true) . ');';
				Storage::put($runtimefile, strip_whitespace('<?php ' . $content), 'runtime');
			} else {
				// 调试模式加载系统默认的配置文件
				C(include THINK_PATH . 'Conf/debug.php');
				// 读取应用调试配置文件
				if (is_file(CONF_PATH . 'debug.php'))
					C(include CONF_PATH . 'debug.php');
			}
		}

		// 读取当前应用状态对应的配置文件
		if (APP_STATUS && is_file(CONF_PATH . APP_STATUS . '.php'))
			C(include CONF_PATH . APP_STATUS . '.php');

		// 设置系统时区
		date_default_timezone_set(C('DEFAULT_TIMEZONE'));

		// 检查应用目录结构 如果不存在则自动创建
		if (C('CHECK_APP_DIR') && !is_dir(LOG_PATH)) {
			// 创建应用目录结构
			require THINK_PATH . 'Common/build.php';
		}

		// 记录加载文件时间
		G('loadTime');
		// 运行应用
		App::run();
	}

	/**
	 * 自动加载的映射
	 *
	 * @param $class
	 * @param string $map
	 */
	static public function addMap($class, $map = '')
	{
		if (is_array($class)) {
			self::$_map = array_merge(self::$_map, $class);
		} else {
			self::$_map[$class] = $map;
		}
	}

	/**
	 * 类库自动加载
	 *
	 * @param string $class 对象类名
	 * @return void
	 */
	public static function autoload($class)
	{
		// 检查是否存在映射
		if (isset(self::$_map[$class])) {
			include self::$_map[$class];
		} else {
			$name = substr($class, 0, strpos($class, '_'));
			$filename='';
			if ($name && (in_array($name, array('Think', 'Org', 'Behavior', 'Com', 'Vendor')) || is_dir(LIB_PATH . $name))) {
				// Library目录下面的命名空间自动定位
				$path = LIB_PATH;
				$class = str_replace('_', '/', $class);
				$filename = $path . $class . EXT;
				if (!is_file($filename)) return; //文件不存在 返回
			} else {
				$tmp = explode('_', parse_name($class));
				if (count($tmp) > 1) {
					$layer = ucfirst(array_pop($tmp));
					// 自动加载，先判断是否
					if (is_file(LIB_PATH . '/' . $layer . '/' . $class . EXT)) {
						$filename = LIB_PATH . '/' . $layer . '/' . $class . EXT;
					} elseif (is_file(APP_PATH . MODULE_NAME . '/' . $layer . '/' . $class . EXT)) {
						$filename = APP_PATH . MODULE_NAME . '/' . $layer . '/' . $class . EXT;
					}
				}
			}
			// Win环境下面严格区分大小写
			if ($filename && IS_WIN && false === strpos(str_replace('\\', '/', realpath($filename)), basename($class . EXT))) {
				return;
			}
			include $filename;
		}
	}

	/**
	 * 取得对象实例 支持调用类的静态方法
	 *
	 * @param string $class  对象类名
	 * @param string $method 类的静态方法名
	 * @return object
	 */
	static public function instance($class, $method = '')
	{
		$identify = $class . $method;
		if (!isset(self::$_instance[$identify])) {
			if (class_exists($class)) {
				$o = new $class();
				if (!empty($method) && method_exists($o, $method))
					self::$_instance[$identify] = call_user_func(array(&$o, $method));
				else
					self::$_instance[$identify] = $o;
			} else
				self::halt(L('_CLASS_NOT_EXIST_') . ':' . $class);
		}
		return self::$_instance[$identify];
	}

	/**
	 * 自定义异常处理
	 *
	 * @access public
	 * @param mixed $e 异常对象
	 */
	static public function appException($e)
	{
		$error = array();
		$error['message'] = $e->getMessage();
		$trace = $e->getTrace();
		if ('E' == $trace[0]['function']) {
			$error['file'] = $trace[0]['file'];
			$error['line'] = $trace[0]['line'];
		} else {
			$error['file'] = $e->getFile();
			$error['line'] = $e->getLine();
		}
		$error['trace'] = $e->getTraceAsString();
		Log::record($error['message'], Log::ERR);
		// 发送404信息
		header('HTTP/1.1 404 Not Found');
		header('Status:404 Not Found');
		self::halt($error);
	}

	/**
	 * 自定义错误处理
	 *
	 * @access public
	 * @param int $errno      错误类型
	 * @param string $errstr  错误信息
	 * @param string $errfile 错误文件
	 * @param int $errline    错误行数
	 * @return void
	 */
	static public function appError($errno, $errstr, $errfile, $errline)
	{
		switch ($errno) {
			case E_ERROR:
			case E_PARSE:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				ob_end_clean();
				$errorStr = "$errstr " . $errfile . " 第 $errline 行.";
				if (C('LOG_RECORD')) Log::write("[$errno] " . $errorStr, Log::ERR);
				self::halt($errorStr);
				break;
			case E_STRICT:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			default:
				$errorStr = "[$errno] $errstr " . $errfile . " 第 $errline 行.";
				self::trace($errorStr, '', 'NOTIC');
				break;
		}
	}

	// 致命错误捕获
	static public function fatalError()
	{
		Log::save();
		if ($e = error_get_last()) {
			switch ($e['type']) {
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
					ob_end_clean();
					self::halt($e);
					break;
			}
		}
	}

	/**
	 * 错误输出
	 *
	 * @param mixed $error 错误
	 * @return void
	 */
	static public function halt($error)
	{
		$e = array();

		if (APP_DEBUG || IS_CLI) {
			//调试模式下输出错误信息
			if (!is_array($error)) {
				$trace = debug_backtrace();
				$e['message'] = $error;
				$e['file'] = $trace[0]['file'];
				$e['line'] = $trace[0]['line'];
				ob_start();
				debug_print_backtrace();
				$e['trace'] = ob_get_clean();
			} else {
				$e = $error;
			}
			if (IS_CLI) {
				exit($e['message'] . PHP_EOL . 'FILE: ' . $e['file'] . '(' . $e['line'] . ')' . PHP_EOL . $e['trace']);
			}
		} else {
			//否则定向到错误页面
			$error_page = C('ERROR_PAGE');
			if (!empty($error_page)) {
				redirect($error_page);
			} else {
				if (C('SHOW_ERROR_MSG'))
					$e['message'] = is_array($error) ? $error['message'] : $error;
				else
					$e['message'] = C('ERROR_MESSAGE');
			}
		}
		// 包含异常页面模板
		$TMPL_EXCEPTION_FILE = C('TMPL_EXCEPTION_FILE');
		if (!$TMPL_EXCEPTION_FILE) {
			//显示在加载配置文件之前的程序错误
			exit('<b>Error:</b>' . $e['message'] . ' in <b> ' . $e['file'] . ' </b> on line <b>' . $e['line'] . '</b>');
		}
		include $TMPL_EXCEPTION_FILE;
		exit;
	}

	/**
	 * 添加和获取页面Trace记录
	 *
	 * @param string $value   变量
	 * @param string $label   标签
	 * @param string $level   日志级别(或者页面Trace的选项卡)
	 * @param boolean $record 是否记录日志
	 * @return void
	 */
	static public function trace($value = '[think]', $label = '', $level = 'DEBUG', $record = false)
	{
		static $_trace = array();
		if ('[think]' === $value) { // 获取trace信息
			return $_trace;
		} else {
			$info = ($label ? $label . ':' : '') . print_r($value, true);
			if ('ERR' == $level && C('TRACE_EXCEPTION')) { // 抛出异常
				E($info);
			}
			$level = strtoupper($level);
			if (!isset($_trace[$level]) || count($_trace[$level]) > C('TRACE_MAX_RECORD')) {
				$_trace[$level] = array();
			}
			$_trace[$level][] = $info;
			if ((defined('IS_AJAX') && IS_AJAX) || !C('SHOW_PAGE_TRACE') || $record) {
				Log::record($info, $level, $record);
			}
		}
	}
}
