<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/11/25
 * Time: 15:45
 */

namespace Admin\Common;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;
use Common\Controller\CommonController;
use Common\Model\CommonModel;
use Common\Util\Tree;

class AdminController extends  CommonController
{
    protected $model;
    /**
     * ��ҳ�����ʼ�����漰�����û���¼��Ȩ����
     */
    protected function _initialize(){
        //��¼���
        if(!admin_login()){ //��û��¼��ת����վҳ��
            $this->redirect('/');
        }
        $user=session('user_auth');
        if('administrator'!=$user['roles']){// ����Ȩ�޿���

        }
        if(!D('ManagerGroup')->checkMenuAuth()){
            $this->error('Ȩ�޲��㣡');
        }
        $map['status'] = array('eq', 1);
        if(!C('DEVELOP_MODE')){ //�Ƿ���������ģʽ
            $map['dev'] = array('neq', 1);
        }
        $tree=new Tree();
        $all_admin_menu_list = $tree->list_to_tree(D('SystemMenu')->where($map)->select()); //����ϵͳ�˵�
        //��������keyΪ�˵�ID
        foreach($all_admin_menu_list as $key => $val){
            $all_menu_list[$val['id']] = $val;
        }
        $current_menu = D('SystemMenu')->getCurrentMenu(); //��ǰ�˵�
        if($current_menu){
            $parent_menu = D('SystemMenu')->getParentMenu($current_menu); //��ȡ���м����
            foreach($parent_menu as $key => $val){
                $parent_menu_id[] = $val['id'];
            }
            $side_menu_list = $all_menu_list[$parent_menu[0]['id']]['_child']; //���˵�
        }
        $this->assign('current_active',dirname($current_menu['url']));//��ǰ�������
        $this->assign('__ALL_MENU_LIST__', $all_menu_list); //���в˵�
        $this->assign('__SIDE_MENU_LIST__', $side_menu_list); //���˵�
        $this->assign('__PARENT_MENU__', $parent_menu); //��ǰ�˵������и����˵�
        $this->assign('__PARENT_MENU_ID__', $parent_menu_id); //��ǰ�˵������и����˵���ID
        $this->assign('__CURRENT_ROOTMENU__', $parent_menu[0]['id']); //��ǰ���˵�
        $this->assign('__USER__', session('user_auth')); //�û���¼��Ϣ
        $this->assign('__CONTROLLER_NAME__', strtolower(CONTROLLER_NAME)); //��ǰ����������
        $this->assign('__ACTION_NAME__', strtolower(ACTION_NAME)); //��ǰ��������
    }

    /**
     * ������ʾ������
     * @param array $data �����ݿ���ѡ����������
     * @param array $binddata  ��Ҫ�󶨵�����
     * @param string $search   ��ʾ��������ʾ
     * @param bool|false $status  �Ƿ��н���  Ĭ��û��
     */
    public function listBuilder($data=array(),$binddata=array(),$search="",$status=false){
        $builder = new ListBuilder();
        $builder->setMetaTitle('thinkshop')->addTopButton('addnew');  //���������ť
        if($status){
           $builder->addTopButton('resume');
        }
             //������ð�ť
         $builder->addTopButton('delete')//���ɾ����ť
            ->setSearch($search, U('index')); //���������Ϣ
        if($binddata){//����Ŀ����
            foreach($binddata as $k=>$v){
                $builder->addTableColumn($k, $v);
            }
        }
        //����Ŀ��ʾ������
            $builder->addTableColumn('right_button', 'operate', 'btn')
                ->setTableDataList($data) //�����б�;
                ->addRightButton('edit')   //��ӱ༭��ť
                ->addRightButton('delete') //���ɾ����ť
                ->display();
    }

    /**
     * @param $form��Ҫ��ӵı�����
     */
    protected function addBuilder($form){
        $builder = new FormBuilder();
        $builder->setMetaTitle('add')->setPostUrl(U('add'));
        foreach($form as $v){
            if(count($v)==5){
                $builder->addFormItem($v[0],$v[1],$v[2],$v[3],$v[4]);
            }else{
                $builder->addFormItem($v[0],$v[1],$v[2],$v[3]);
            }
        }
        $builder->display();
    }

    /**
     * @param $form��Ҫ�༭�ı�����
     */
    protected function editBuilder($form){
        $id=I('get.id',0);
        $builder = new FormBuilder();
        $builder->setMetaTitle('edit')->setPostUrl(U('edit'));
        foreach($form as $v){
            if(count($v)==5){
                $builder->addFormItem($v[0],$v[1],$v[2],$v[3],$v[4]);
            }else{
                $builder->addFormItem($v[0],$v[1],$v[2],$v[3]);
            }
        }
        $builder->setFormData($this->model->find($id))->display();
    }
    /**
     * ��ȡ��ȡ���ķ�ҳ���ݲ����䵽ģ������У�������ɾ��
     * @param $data ��ȡ��������
     * @return mixed
     */
    protected function pagers($data){
        $pager=$data['pages'];
        $this->assign("pager",$pager);
        //����������ҳ
        for($i=1;$i<=$pager['pagers'];$i++){
            $spage[$i]=$i;
        }
        $this->assign("spage",$spage);//��ʾ����ҳ
        unset($data['pages']);//ɾ����
        return $data;
    }
    /**
     * ����һ�����߶������ݵ�״̬
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME){
        $ids    = I('request.ids');
        $status = I('request.status');
        if(empty($ids)){
            $this->error('��ѡ��Ҫ����������');
        }
        //�����������
        switch($model){
            case 'User':
                if(in_array(1, $ids, true) || 1 == $ids)
                    $this->error('��������ĳ�������Ա״̬');
                break;
            case 'UserGroup':
                if(in_array(1, $ids, true) || 1 == $ids)
                    $this->error('��������ĳ�������Ա��״̬');
                break;
        }
        $model_primary_key = D($model)->getPk();
        $map[$model_primary_key] = array('in',$ids);
        switch($status){
            case 'forbid' : //������Ŀ
                $data = array('status' => 0);
                $this->editRow($model, $data, $map, array('success'=>'���óɹ�','error'=>'����ʧ��'));
                break;
            case 'resume' : //������Ŀ
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 0), $map);
                $this->editRow($model, $data, $map, array('success'=>'���óɹ�','error'=>'����ʧ��'));
                break;
            case 'hide' : //������Ŀ
                $data = array('status' => 2);
                $map  = array_merge(array('status' => 1), $map);
                $this->editRow($model, $data, $map, array('success'=>'���سɹ�','error'=>'����ʧ��'));
                break;
            case 'show' : //��ʾ��Ŀ
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 2), $map);
                $this->editRow($model, $data, $map, array('success'=>'��ʾ�ɹ�','error'=>'��ʾʧ��'));
                break;
            case 'recycle' : //�ƶ�������վ
                $data['status'] = -1;
                $this->editRow($model, $data, $map, array('success'=>'�ɹ���������վ','error'=>'ɾ��ʧ��'));
                break;
            case 'restore' : //�ӻ���վ��ԭ
                $data = array('status' => 1);
                $map  = array_merge(array('status' => -1), $map);
                $this->editRow($model, $data, $map, array('success'=>'�ָ��ɹ�','error'=>'�ָ�ʧ��'));
                break;
            case 'delete'  : //ɾ����Ŀ
                $result = D($model)->where($map)->delete();
                if($result){
                   return true;
                }else{
                    $this->error('ɾ��ʧ��');
                }
                break;
            default :
                $this->error('��������');
                break;
        }
    }

    /**
     * �����ݱ��еĵ��л���м�¼ִ���޸� GET����idΪ���ֻ򶺺ŷָ�������
     * @param string $model ģ������,��M����ʹ�õĲ���
     * @param array  $data  �޸ĵ�����
     * @param array  $map   ��ѯʱ��where()�����Ĳ���
     * @param array  $msg   ִ����ȷ�ʹ������Ϣ array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                      urlΪ��תҳ��,ajax�Ƿ�ajax��ʽ(������Ϊ������ʱ����)
     * @author jry <598821125@qq.com>
     */
    final protected function editRow($model, $data, $map, $msg){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        //�����id�ֶΣ�����������
        $fields = M($model)->getDbFields();
        if(in_array('id',$fields) && !empty($id)){
            $where = array_merge(array('id' => array('in', $id )) ,(array)$where);
        }
        $msg = array_merge(array('success'=>'�����ɹ���', 'error'=>'����ʧ�ܣ�', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg);
        if(M($model)->where($map)->save($data) !== false){
            $this->success($msg['success'], $msg['url'], $msg['ajax']);
        }else{
            $this->error($msg['error'], $msg['url'], $msg['ajax']);
        }
    }
    protected function addData(){
        $data=$this->model->addData();
        if($data){
            if($data==CommonModel::MFAIL){
                $this->error('����ʧ��');
            }else{
                $this->error($data);
            }
        }else{
           return true;
        }
    }
    protected function editData(){
        $data=$this->model->editData();
        if($data){
            if($data==CommonModel::MFAIL){
                $this->error('����ʧ��');
            }else{
                $this->error($data);
            }
        }else{
            return true;
        }
    }
}