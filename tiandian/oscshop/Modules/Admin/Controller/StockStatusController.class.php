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
use Admin\Model\StockStatusModel;
class StockStatusController extends CommonController{
	
	protected function _initialize(){
		parent::_initialize();
			$this->breadcrumb1='商品';
			$this->breadcrumb2='库存状态';
	}
	
     public function index(){
     	
		$model=new StockStatusModel();   
		
		$data=$model->show_stock_status_page();		
		
		$this->assign('empty',$data['empty']);// 赋值数据集
		$this->assign('list',$data['list']);// 赋值数据集
		$this->assign('page',$data['page']);// 赋值分页输出	
		
    	$this->display();
	 }

	function add(){
		
		if(IS_POST){
			
			$model=new StockStatusModel();  
			$data=I('post.');
			$return=$model->add_stock_status($data);			
			$this->osc_alert($return);
		}
		
		$this->crumbs='新增';		
		$this->action=U('StockStatus/add');
		$this->display('edit');
	}

	function edit(){
		if(IS_POST){
			$model=new StockStatusModel();  
			$data=I('post.');
			$return=$model->edit_stock_status($data);			
			$this->osc_alert($return);
		}
		$this->crumbs='编辑';		
		$this->action=U('StockStatus/edit');
		$this->d=M('StockStatus')->find(I('id'));
		$this->display('edit');		
	}

	 public function del(){
		$model=new StockStatusModel();  
		
		$return=$model->del_stock_status();			
		$this->osc_alert($return); 	
	 }
	 
}
?>