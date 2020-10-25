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
 * VgotFaster ���û�ȡ��
 *
 * ��ȡ�����ü�����ϵͳ���Զ������úͻ�ȡ���Ե�
 *
 * @package VgotFaster
 * @author pader
 */
class Config {

	/**
	 * ��ȡһ������
	 *
	 * @param string �����ļ���
	 * @param string �����ļ��е�ĳһ������
	 * @return mixed
	 */
	public function load($name, $key=null) {
		$config = getConfig($name);
		return is_null($key) ? $config : $config[$key];
	}

	/**
	 * Get A Config Value
	 *
	 * @param string $name
	 * @param string $key
	 * @return mixed
	 */
	public function get($name, $key=null)
	{
		return $this->load($name, $key);
	}

	/**
	 * Set A Config Value
	 *
	 * Just set in dynamic process, not into file
	 *
	 * @param string $name
	 * @param string|array $key
	 * @param mixed $val
	 */
	public function set($name, $key, $val=null) {
		global $CONFIG;

		if (is_array($key)) {
			if (!isset($CONFIG[$name])) {
				$CONFIG[$name] = $key;
			} else {
				foreach ($key as $k => $v) {
					$CONFIG[$name][$k] = $v;
				}
			}
		} else {
			$CONFIG[$name][$key] = $val;
		}
	}

	/**
	 * ��ȡ����
	 *
	 * �������������е���������
	 *
	 * @param mixed $name �����ļ�����
	 * @param string $language ָ��ĳ������
	 * @return Lang Data
	 */
	public function lang($name, $language='')
	{
		global $LANGUAGE;

		if ($language == '') {
			$language = $this->get('config', 'default_language');
		}

		if (isset($LANGUAGE[$language][$name])) {
			return $LANGUAGE[$language][$name];
		}

		$appLang = APPLICATION_PATH.'/language/'.$language;
		$sysLang = SYSTEM_PATH.'/language/'.$language;

		if (is_dir($appLang) || is_dir($sysLang)) {
			$appLangFile = $appLang.'/lang_'.$name.'.php';
			$sysLangFile = $sysLang.'/lang_'.$name.'.php';

			$lang = array();

			if (is_file($appLangFile)) {
				include_once $appLangFile;
			} elseif (is_file($sysLangFile)) {
				include_once $sysLangFile;
			} else {
				showError("No found file of language $name");
			}

			$LANGUAGE[$language][$name] = $lang;
			
			return $lang;

		} else {
			showError("Language $language does not exists!");
		}
	}

}
