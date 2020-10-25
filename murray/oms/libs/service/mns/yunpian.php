<?php 
/**
* POPFrame
*
* ���ݿ�ܣ�murray.cn��
* @author Murray Wang <wjn_84@163.com>
* @version 1.0
* @package ��Ƭ������
*/

defined('INPOP') or exit('Access Denied');

class yunpian{

	public $apikey; //��Կ

	//��ʼ��
    public function __construct($apikey = ""){
		$this->apikey = $apikey;
    }

	//����˻�
	function get_user($ch,$apikey){
		curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v1/user/get.json');
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $this->apikey)));
		return curl_exec($ch);
	}

	//���ŷ���
	function send($ch,$data){
		curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v1/sms/send.json');
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		return curl_exec($ch);
	}

	//��������
	function voice_send($ch,$data){
		curl_setopt ($ch, CURLOPT_URL, 'http://voice.yunpian.com/v1/voice/send.json');
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		return curl_exec($ch);
	}

	//ִ�з��Ͷ���
	function doSend($sendData = "", $mobile = ""){
		if(!$sendData) return false;
		if(!$mobile) return false;
		$ch = curl_init();
		//������֤��ʽ
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
		//���÷��ؽ��Ϊ��
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//���ó�ʱʱ��
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		//����ͨ�ŷ�ʽ
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = array('text'=>$sendData, 'apikey'=>$this->apikey, 'mobile'=>$mobile);
		$json_data = $this->send($ch,$data);
		$return_data = json_decode($json_data, true);
		if($return_data['code'] == 0){
			$return = "success";
		}else{
			$return = "failure".$json_data;
		}
		curl_close($ch);
		return $return;
	}

}
?>