<?php 
/**
* POPFrame
*
* ���ݿ�ܣ�murray.cn��
* @author Murray Wang <wjn_84@163.com>
* @version 1.0
* @package ��ɫ������
*/

defined('INPOP') or exit('Access Denied');

class aclrole extends Model{

	//��ʼ��
    public function __construct(){
		parent::__construct("aclroles", "rid");
    }
	
}

?>