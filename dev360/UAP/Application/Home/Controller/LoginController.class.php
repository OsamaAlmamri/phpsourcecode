<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function index(){
        $this->display();
    }

    public function checkLogin(){
        $rs=M('sys_user')->where(array_filter($_POST))->find();
        if(empty($rs)){
            echo "error";
        }else{
            $_SESSION['_user']=$rs;

            //����������cookies��������ϵͳ����ʹ��
            setcookie("_username",$rs['name'],time()+3600*12,'/',C('domainName'));
            setcookie("_userid",$rs['id'],time()+3600*12,'/',C('domainName'));
            echo "";
        }
    }
}