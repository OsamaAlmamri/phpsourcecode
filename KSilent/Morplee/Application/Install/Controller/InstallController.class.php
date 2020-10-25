<?PHP
namespace Install\Controller;
use Think\Controller;
use Think\Db;
use Think\Storage;

class InstallController extends Controller{

    protected function _initialize(){
        if(Storage::has('./Data/install.lock')){
            $this->error('�Ѿ��ɹ���װ��OneThink���벻Ҫ�ظ���װ!');
        }
    }

    public function step1(){
    	session('error', false);
    	
        //�������
        $env = check_env();

        //Ŀ¼�ļ���д���
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //�������
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);
        $this->display();
    }

    public function step2($db = null, $admin = null,$tb=null){
        if(IS_POST){

            //������Ա��Ϣ
            if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
                $this->error('����д��������Ա��Ϣ');
            } else if($admin[1] != $admin[2]){
                $this->error('ȷ����������벻һ��');
            } else {
                $info = array();
                list($info['username'], $info['password'], $info['repassword'], $info['email'])
                = $admin;
                //�������Ա��Ϣ
                session('admin_info', $info);
            }

            //������ݿ�����
            if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
                $this->error('����д���������ݿ�����');
            } else {
                $DB = array();
                list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'],
                     $DB['DB_PORT']) = $db;
                //�������ݿ�����
                session('db_config', $DB);

                //�������ݿ�
                $dbname = $DB['DB_NAME'];
                unset($DB['DB_NAME']);
                $db  = Db::getInstance($DB);
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
                $db->execute($sql) || $this->error($db->getError());
            }


            //��ת�����ݿⰲװҳ��
            $this->redirect('step3');
        } else {
            
            session('error') && $this->error('�������û��ͨ������������������ԣ�');

            $step = session('step');
            if($step != 1 && $step != 2){
                $this->redirect('step1');
            }

            session('step', 2);
            $this->display();
        }
    }

    
    //��װ����������װ���ݱ����������ļ�
    public function step3(){
        if(session('step') != 2){
            $this->redirect('step2');
        }

        $this->display();

        //�������ݿ�
        $dbconfig = session('db_config');
        $db = Db::getInstance($dbconfig);
        //�������ݱ�
        create_tables($db, $dbconfig['DB_PREFIX']);
        //ע�ᴴʼ���ʺ�
        $auth  = build_auth_key();
        $admin = session('admin_info');
        register_administrator($db, $dbconfig['DB_PREFIX'], $admin, $auth);

        //���������ļ�
        $conf   =   write_config($dbconfig, $auth);
        session('config_file',$conf);

        if(session('error')){
            //show_msg();
        } else {
            session('step', 3);
            $this->redirect('Index/complete');
        }
    }
}