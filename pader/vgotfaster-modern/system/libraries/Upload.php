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

namespace VF\Library;

/**
 * VgotFaster Upload Library
 *
 * @package VgotFaster
 * @author Pader
 */
class Upload {

	var $set = array();
	var $uploadInfo = array();
	var $errorCode;

	function __construct($set=array())
	{
		$this->set = array(
			/*
				�ϴ��ļ��ı���������
				��Ҳ������ִ�� doUpload() ����ʱ���ݵ�һ���ַ�������ʱ���Ĵ�ֵ���� doUpload() �и��Ĳ������´���Ч
			*/
			'fileVarName' => 'filedata',
			'uploadTargetPath' => '.',  //�ϴ�����Ŀ���ļ���
			/*
				�����ϴ����ļ���ʽ
				��*�� ���������ȫ����ʽ�������ʽʹ�� ��|���ָ�
				�磺 jpg|gif|doc|txt
			*/
			'allowExtensions' => '*',
			/*
				�����ϴ����ļ���С���� KB Ϊ��λ
				��Ϊ 0 ������
			*/
			'filesizeLimit' => 0,
			/*
				���������ļ�ʱ�Ƿ񸲸�
				Ϊ FALSE ʱ VF ���Զ����ļ�������� ��_1�������ֲ���������
			*/
			'overwrite' => FALSE,
			/*
				�ϴ�����������������
				ѡ�����ļ�����������Ϊ�����õ����ƣ�Ϊ��ʱ��������������ֻ���� encryptName Ϊ FALSE ʱ����Ч
			*/
			'rename' => '',
			/*
				������ʱ�Ƿ������������ļ���
				Ϊ FALSE ������ԭ��׺��rename��������ֵʱ�������Ч
			*/
			'overwriteExtension' => TRUE,
			/*
				�Ƿ��Զ��������ļ�
				Ϊ TRUE ʱ��VF�������ļ�������Ϊһ��������ַ���������ԭ��ʽ���� overwrite Ϊ FALSE ʱ����Ч
			*/
			'encryptName' => FALSE,
			'removeSpace' => FALSE  //�Ƿ�ʹ���»����滻�ļ����еĿո�
		);

		if ($config = getConfig('upload', true)) {
			$this->initialize($config);
		}

		if(count($set) > 0) {
			$this->initialize($set);
		}
	}

	/**
	 * ��ʼ���ϴ�����
	 *
	 * Ϊ�޸�ϵͳĬ������Ϊ�ϴ���׼��
	 *
	 * @param array $set ��������
	 * @return void
	 */
	function initialize($set)
	{
		foreach($set as $key => $val) {
			if(isset($this->set[$key])) {
				$this->set[$key] = $val;
			}
		}
		$this->set['uploadTargetPath'] = str_replace('\\','/',$this->set['uploadTargetPath']);
		$this->set['uploadTargetPath'] = rtrim($this->set['uploadTargetPath'],'/');
	}

	/**
	 * ִ�е��ļ��ϴ�����
	 *
	 * @param string|array $fileVarNameOrSet �ϴ������ƻ���������
	 * @param array $set ��������
	 * @return array �ϴ���Ϣ
	 */
	function doUpload($fileVarNameOrSet='',$set=array())
	{
		//�õ��ϴ��ļ��������� $fileVarName
		$fileVarName = $this->set['fileVarName'];
		if($fileVarNameOrSet) {
			if(is_array($fileVarNameOrSet)) {
				$this->initialize($fileVarNameOrSet);
				$fileVarName = $this->set['fileVarName'];
			} else {
				$fileVarName = $fileVarNameOrSet;
			}
		}

		if(count($set) > 0) {
			$this->initialize($set);
		}

		if(!isset($_FILES[$fileVarName])) {
			$this->errorCode = 'err_no_file';
			return FALSE;
		}

		$this->uploadInfo = $this->progressUpload($_FILES[$fileVarName]);

		return $this->uploadInfo;
	}

	/**
	 * ���ļ��ϴ�
	 *
	 * @param string|array $fileVarNameOrSet �ϴ�������(������"[]"), ����������
	 * @param array $set ��������
	 * @return array �ϴ���Ϣ��ά����
	 */
	function doMultiUpload($fileVarNameOrSet='',$set=array())
	{
		//�õ��ϴ��ļ��������� $fileVarName
		$fileVarName = $this->set['fileVarName'];
		if($fileVarNameOrSet) {
			if(is_array($fileVarNameOrSet)) {
				$this->initialize($fileVarNameOrSet);
				$fileVarName = $this->set['fileVarName'];
			} else {
				$fileVarName = $fileVarNameOrSet;
			}
		}

		if(count($set) > 0) {
			$this->initialize($set);
		}

		if(!isset($_FILES[$fileVarName])) {
			$this->errorCode = 'err_no_file';
			return FALSE;
		}
		$uploads = $_FILES[$fileVarName];
		$upsInfo = array();
		$isNoFalse = FALSE;
		//ѭ�������ϴ�����
		foreach($uploads['name'] as $key => $name) {
			$upload = array();
			foreach(array('name','type','tmp_name','error','size') as $r) {
				$upload[$r] = $uploads[$r][$key];
			}
			$upsInfo[$key] = $this->progressUpload($upload,TRUE);
			if($upsInfo[$key] !== FALSE) $isNoFalse = TRUE;
		}

		if($isNoFalse) {
			$this->uploadInfo = $upsInfo;
			return $this->uploadInfo;
		} return FALSE;
	}

	/**
	 * �����ϴ����ļ�
	 *
	 * �����ϴ����ļ����ɵ��ļ��ϴ� doUpload() �Ͷ��ļ��ϴ� doMultiUpload() ����
	 * Ҳ�����ڿ������е��õȣ��������Ƽ�
	 *
	 * @param array $upload �ϴ�����
	 * @param bool $multiple Ԥ��
	 * @return array �ϴ���Ϣ
	 */
	function progressUpload($upload,$multiple=FALSE)
	{
		$upInfo = array();

		$uploadSource = str_replace('\\\\','\\',$upload['tmp_name']);

		$this->errorCode = $upload['error'];
		if($upload['error'] != 0) {
			return FALSE;
		}

		if(!is_uploaded_file($uploadSource)) {
			$this->errorCode = 'err_no_file';
			return FALSE;
		}

		//�ļ���С���
		if($this->set['filesizeLimit'] > 0) {
			if($upload['size'] / 1024 > $this->set['filesizeLimit']) {
				$this->errorCode = 'err_allow_size';
				return FALSE;
			}
		}

		$upInfo['fileType'] = $upload['type'];
		$upInfo['origName'] = $upload['name'];

		$extension = $this->pathinfo($upload['name'],'extension');

		//allowExtensions ѡ��
		if(!$this->checkExtension($extension)) {
			$this->errorCode = 'err_allow_ext';  //�ļ���ʽ������
			return FALSE;
		}

		//rename ѡ��,ֻ�Ե��ļ��ϴ���Ч
		if(!empty($this->set['rename']) and $this->set['encryptName'] == FALSE) {
			if($this->set['overwriteExtension']) {  //overwriteExtension
				$upload['name'] = $this->set['rename'];
			} else {
				$upload['name'] = $this->set['rename'].(empty($extension) ? '' : '.'.$extension);
			}
		}

		//overwrite ѡ��
		$fileUpTarget = $this->set['uploadTargetPath'].'/'.$upload['name'];
		if($this->set['overwrite'] == FALSE) {
			$fileUpTarget = $this->getOverwritePath($fileUpTarget);
		}

		//removeSpace ѡ��
		if($this->set['removeSpace']) {
			$fileUpTarget = str_replace(' ','_',$fileUpTarget);
		}

		if(!@move_uploaded_file($uploadSource,$fileUpTarget)) {
			if($this->errorCode == 0) {
				$this->errorCode = 'err_cant_write';
			}
			return FALSE;
		}

		$this->errorCode = 'upload_successfuly';
		$pathinfo = $this->pathinfo($fileUpTarget);

		$upInfo['fullPath'] = realpath($fileUpTarget);
		$upInfo['fileName'] = $pathinfo['basename'];
		$upInfo['fileExt'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		$upInfo['fileSize'] = $upload['size'];

		return $upInfo;
	}

	/**
	 * ����ļ���ʽ�Ƿ������ϴ�
	 *
	 * @param string $extension
	 * @return bool
	 */
	private function checkExtension($extension)
	{
		if($this->set['allowExtensions'] != '*' and !empty($extension)) {
			$allowExtensions = explode('|',$this->set['allowExtensions']);
			$allowExtensions = array_map('trim',$allowExtensions);
			$extension = strtolower($extension);
			return in_array($extension,$allowExtensions);
		}
		return TRUE;
	}

	/**
	 * ��ȡһ�����������ϴ�·��
	 *
	 * �� overwrite Ϊ FALSE �����ļ�������ʱ����ȡ����ʹ�õ��ļ���
	 * �Զ��ں��������
	 *
	 * @param string $fileUpTarget
	 * @return string �µ��ϴ�·��
	 */
	private function getOverwritePath($fileUpTarget)
	{
		if(!file_exists($fileUpTarget) and !$this->set['encryptName']) {
			return $fileUpTarget;
		}
		$pathinfo = $this->pathinfo($fileUpTarget);
		$ext = isset($pathinfo['extension']) ? '.'.$pathinfo['extension'] : '';
		$fileHash = uniqid();

		for($i=0;;$i++) {
			//encryptName ѡ��
			$fileName = $this->set['encryptName'] == TRUE ? str_shuffle($fileHash) : $pathinfo['filename']."_$i";
			$fileUpTarget = $pathinfo['dirname'].'/'.$fileName.$ext;
			if(!file_exists($fileUpTarget)) {
				return $fileUpTarget;
			}
		}
	}

	/**
	 * ��ʾ����
	 *
	 * @return
	 */
	function error()
	{
		/*
			UPLOAD_ERR_OK = 0��û�д��������ļ��ϴ��ɹ���
			UPLOAD_ERR_INI_SIZE = 1���ϴ����ļ������� php.ini �� upload_max_filesize ѡ�����Ƶ�ֵ��
			UPLOAD_ERR_FORM_SIZE = 2���ϴ��ļ��Ĵ�С������ HTML ���� MAX_FILE_SIZE ѡ��ָ����ֵ��
			UPLOAD_ERR_PARTIAL = 3���ļ�ֻ�в��ֱ��ϴ���
			UPLOAD_ERR_NO_FILE = 4��û���ļ����ϴ���
			UPLOAD_ERR_NO_TMP_DIR = 6���Ҳ�����ʱ�ļ��С�PHP 4.3.10 �� PHP 5.0.3 ������
			UPLOAD_ERR_CANT_WRITE = 7���ļ�д��ʧ�ܡ�PHP 5.1.0 ������
		*/
		$VF =& getInstance();
		$lang = $VF->config->lang('upload');

		if(is_numeric($this->errorCode)) {
			$errorCodes = array(
				0 => 'upload_successfuly',
				1 => 'err_ini_size',
				2 => 'err_form_size',
				3 => 'err_partial',
				4 => 'err_no_file',
				6 => 'err_no_tmp_dir',
				7 => 'err_cant_write',
				8 => 'err_allow_ext'  //custom
			);
			if(isset($errorCodes[$this->errorCode])) {
				$this->errorCode = $errorCodes[$this->errorCode];
			}
		}

		return isset($lang[$this->errorCode]) ? $lang[$this->errorCode] : $lang['err_unknow'];
	}

	/**
	 * �˺�������ȡ�� php �Դ��� pathinfo() ����
	 *
	 * �Դ��� pathinfo() �����ڷ� windows ����ϵͳ�б��ֺܲ�����
	 *
	 * @param string $path �ļ�·��
	 * @param string $get �Ƿ�ֻ��ȡ����ֵ�е�һ��Ԫ��[��д����]
	 * @return array|string
	 */
	function pathinfo($path,$get='')
	{
		$path = str_replace('\\','/',$path); //ֻʹ�� / б��
		$info = array();

		$pathExport = explode('/',$path);
		$count = count($pathExport);
		$baseName = end($pathExport);

		$lastPoint = strrpos($baseName,'.');
		unset($pathExport[$count - 1]);

		$info['dirname'] = join('/',$pathExport);
		$info['basename'] = $baseName;  //baseName
		$info['extension'] = $lastPoint !== FALSE ? substr($baseName,$lastPoint + 1) : '';  //extension
		$info['filename'] = $lastPoint !== FALSE ? substr($baseName,0,$lastPoint) : $info['basename'];  //fileName
		return empty($get) ? $info : $info[$get];
	}

}
