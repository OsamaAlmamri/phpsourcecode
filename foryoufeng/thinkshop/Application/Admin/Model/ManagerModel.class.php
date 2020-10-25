<?php
/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 2015/11/24
 * Time: 22:15
 */
namespace Admin\Model;
use Common\Model\UserModel;
class ManagerModel extends UserModel{
    /**
     * ��̨����Ա��¼
     * @param $username �û���
     * @param $password ����
     * @param $map   ��������
     * @return bool
     */
    public function login($username, $password, $map){
        //ȥ��ǰ��ո�
        $username = trim($username);
        //ƥ���¼��ʽ
        $map['name'] = array('eq', $username); //�û�����½
        $map['is_lock'] = array('neq', 1);
        $user = $this->where($map)->find(); //�����û�
        if(!$user){
            $this->error = '1';//�û������ڻ򱻽���
        }else{
            if(user_md5($password) !== $user['password']){
                $this->error = '2';//�������
            }else{
                //���µ�¼��Ϣ
                $data = array(
                    'id'              => $user['id'],
                    'last_login' => NOW_TIME,
                    'last_ip'   => get_client_ip(),
                );
                $this->save($data);
                $this->autoLogin($user);
                return $user['id'];
            }
        }
        return false;
    }
}