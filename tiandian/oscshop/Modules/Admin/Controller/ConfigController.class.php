<?php
/**
 * oscshop 电子商务系统
 *
 * ==========================================================================
 * @link      http://www.oscshop.cn/
 * @copyright Copyright (c) 2015 oscshop.cn. 
 * @license   http://www.oscshop.cn/license.html License
 * ==========================================================================
 *
 * @author    李梓钿
 *
 */
namespace Admin\Controller;
use Admin\Model\ConfigModel;

class ConfigController extends CommonController{
	
	protected function _initialize(){
		parent::_initialize();
			$this->breadcrumb1='系统';
			$this->breadcrumb2='配置管理';
	}
	
     public function index(){
     	
		$model=new ConfigModel();   
		
		$filter=I('get.');
		
		$search=array();
		
		if(isset($filter['name'])){
			$search['name']=$filter['name'];		
		}
		if(isset($filter['config_group'])){
			$search['config_group']=$filter['config_group'];
			$this->get_group=$filter['config_group'];			
		}
		
		
		$data=$model->show_config_page($search);	
		
		$this->assign('empty',$data['empty']);// 赋值数据集
		$this->assign('list',$data['list']);// 赋值数据集
		$this->assign('page',$data['page']);// 赋值分页输出	
		
    	$this->display();
	 }
	
	function add(){
		
		if(IS_POST){
			
			$model=new ConfigModel();  
			$data=I('post.');
			$return=$model->add_config($data);			
			$this->osc_alert($return);
		}
		
		$this->crumbs='新增';		
		$this->action=U('Config/add');
		$this->display('edit');
	}

	function edit(){
		if(IS_POST){
			$model=new ConfigModel();  
			$data=I('post.');
			$return=$model->edit_config($data);		
		
			$this->osc_alert($return);
		}
		$this->crumbs='编辑';		
		$this->action=U('Config/edit');
		$this->c=M('Config')->where(array('id'=>I('id')))->find();
	
		$this->display();		
	}
	public function del(){
		$r=M('Config')->delete(I('id'));
		if($r){
			$this->redirect('Config/index');
		}
	}

	 
}
?>