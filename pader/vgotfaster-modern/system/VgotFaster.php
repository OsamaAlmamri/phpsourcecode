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
!defined('VGOTFASTER') && exit('Access Deined');

define('VF_VERSION','2.0.0 alpha');
define('VF_VERSION_ID', 20000);

/**
 * VgotFaster Core Process
 *
 * Use single interface file set constant VGOTFASTER,APPLICATION_PATH,SYSTEM_PATH
 * and require this file to finish your VgotFaster Application
 *
 * @created 23:08 2009/8/6
 * @updated 2015/1/16
 */
$started = microtime(true);
/*
	���ڴ洢������ϵͳ�������
	ͨ���˻��������������ݵڶ��ε���ʱ�����ٴ������ļ�
	CONFIG: �������Ӧ�ó�����������
	MODEL:  �������ģ���б�
	LANGUAGE: ���������������
	LOG: ϵͳ���й����� () ��������־��¼��key Ϊ����, value Ϊ����
*/
$CONFIG = $MODEL = $LANGUAGE = $LOG = array();

require SYSTEM_PATH.'/Common.php';
require SYSTEM_PATH.'/Base.php';

$Router =& \VF\loadCore('router');
$URI = $Router->analysis(); //ͨ��·�����ȡ�����õ�URI�ͷ��ʿ������������������
unset($Router);

$URI === false && showError404('controller');  //������������

require SYSTEM_PATH.'/Interface.php';  //��������ģ�ͼ̳нӿ����ļ�

//���غ�����չ
foreach (array('Controller', 'Model') as $ifName) {
	$ifFile = APPLICATION_PATH.'/core/'.$ifName.'.php';
	is_file($ifFile) && include $ifFile;
}

//���ؿ�����
include $URI['file'];

//ʵ������������
$class = '\\Controller\\'.ucfirst(strtolower($URI['controller'])); //For example: [Index] \Controller\Index
if (!class_exists($class)) {
	$class .= 'Controller';
	class_exists($class) || showError404('class');
}

$Controller = new $class;
_systemLog("Initialize Controller '$class'");

//���˷ǹ��������б����ж϶����Ƿ����
$actions = array_diff(get_class_methods($Controller), array('__getInstance', '__construct', '__ControllerAfterRuntime'));
if (!in_array($URI['action'], $actions)) { //Support php keyword as action like "classAction"
	if (in_array($URI['action'].'Action', $actions)) {
		$URI['action'] = $URI['action'].'Action';
	} else {
		array_unshift($URI['params'], $URI['action']);
		$URI['action'] = '_redirect';
	}
}

_systemLog("Call Action '{$URI['action']}'");

//���ж���
//empty($URI['params']) ? $Controller->$URI['action']() : call_user_func_array(array(&$Controller, $URI['action']), $URI['params']);
call_user_func_array(array(&$Controller, $URI['action']), $URI['params']);

//To run the registered after functions
$Controller->__ControllerAfterRuntime();

//VgotFaster Process End