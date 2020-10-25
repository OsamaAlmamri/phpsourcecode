<?php
class FileUpload{
	private $allowType; //�����ϴ����ļ�����
	private $maxSize;	//�����ϴ�������ļ���С
	private $filePath;	//�ϴ���·��
	private $newName;	//�õ������ļ���
	private $subPath;	//�Ƿ�������������������Ŀ¼
	private $extName;	//�ϴ��ļ�����չ��
	
	function __construct($filePath="./upload",$allowType = array('gif','jpg','jpeg','png'),$maxSize = 10001000,$subPath = true){
		$this->filePath = $filepath;
		$this->allowType = $allowType;
		$this->maxSize = $maxSize;
		$this->subPath = $subPath;
	}
	
	//��ʼ�ϴ��ļ�
	public function startUpload($postname){
		//��ȡ�ϴ��ı���
		if($_FILES["$postname"]['error']){
			die("�ļ��ϴ�ʧ��,�������Ϊ��".$_FILES[$postname]['error']);
		}
		
		$this->checkSize();
		$this->checkType();
		$this->checkPath();
		$this->createNewName();
		$this->moveFile();
	}
	
	//��ȡ�ϴ����µ��ļ���
	public function getNewName(){
		return $this->newName;
	}
	
	//��ȡ�ϴ��Ĵ�����Ϣ
	public function getErrorMsg(){
		
	}
	
	//����ϴ��ļ��Ĵ�С
	private function checkSize(){
		if($this->maxSize < $_FILES[$postname]['size']){
			die("�ϴ��ļ���С��������ֵ...");
		}
	}
	
	//����ϴ��ļ�������
	private function checkType(){
		
		if(!in_array(strtolower($this->extName),$this->allowType)){
			die('�ϴ��ļ��ĸ�ʽ��֧��...');
		}
	}
	
	//��ȡ�ϴ��ļ�����չ��
	private function getExtName(){
		$extarr = explode('.',$_FILES[$postname]['name']);
		$this->extName = $extarr[count($extarr)-1];
	}
	
	//����ϴ���Ŀ¼�Ƿ����
	private function checkPath(){
		//����Ƿ�������Ŀ¼
		if($this->subPath){
			$this->filePath = $this->filePath .'/'. date('Ymd').'/';
		}
		//����Ŀ¼
		if(!is_dir($this->filePath) && !is_writable($this->filePath)){
			if(!mkdir($this->filepath,0777,true)){
				die("����Ŀ¼ʧ��...");
			}
		}
	}
	
	//�����µ��ļ���
	private function createNewName(){
		$this->newName = $this->filePath . time().rand(10000,99999).$this->extName;
	}
	
	//��ʼ�ƶ��ϴ��ļ���ָ��Ŀ¼
	private function moveFile(){
		//�ж���ʱ�ļ��Ƿ����
		if(!file_exists($_FILES[$postname]['tmp_name'])){
			die('��ʱ�ļ�������...');
		}
		//��ʼ�ƶ��ļ�
		if(!move_uploaded_file($_FILES[$postname]['tmp_name'],$this->newName)){
			die('��ʱ�ļ��ƶ�ʧ��...');
		}
	}
}