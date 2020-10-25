<?php
/**
 * VgotFaster PHP Framework V2.0
 *
 * VgotFaster ����ļ�
 *
 * @link   http://vgotfaster.googlecode.com
 *         http://www.vgot.net
 * @author yp2008cn@gmail.com
 *         ypnow@163.com
 *         QQ 270075658
 */
define('VGOTFASTER', __FILE__);

/**
 * Ӧ�ÿ�ܻ�������
 * 
 * Ӧ�ó���ɸ��ݴ����ö�ȡ��ͬ�����õ�
 *
 * development ���ؿ�������
 * testing ����ƽ̨
 * production ������ʽ����������������
 */
define('ENVIRONMENT', 'development');

switch (ENVIRONMENT) {
	case 'production':
		error_reporting(0);
		break;
	
	case 'testing':
	case 'development':
		error_reporting(E_ALL);
		break;
}

//Ӧ�ó������������Ŀ¼����
//VgotFaster Constant
define('APPLICATION_PATH', __DIR__.'/app');
define('SYSTEM_PATH', __DIR__.'/system');

//Load VgotFaster Core Running
require SYSTEM_PATH.'/VgotFaster.php';
?>