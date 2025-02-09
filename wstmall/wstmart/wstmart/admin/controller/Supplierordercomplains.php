<?php
namespace wstmart\admin\controller;
use wstmart\admin\model\SupplierOrderComplains as M;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 订单投诉控制器
 */
class Supplierordercomplains extends Base{
	
    public function index(){
        $areaList = model('areas')->listQuery(0); 
        $this->assign("areaList",$areaList);
        $this->assign("p",(int)input("p"));
    	return $this->fetch("list");
    }
    /**
    * 查看投诉信息
    */
    public function view(){
        $m = new M();
        if((int)Input('cid')>0){
            $data = $m->getDetail();
            $this->assign('order',$data);
            $rs = model('SupplierOrders')->getByView($data['orderId']);
            $this->assign('object',$rs);
            $this->assign("from",input("from/d",0));
        }
        $this->assign("p",(int)input("p"));
        return $this->fetch('view');
    }
    /**
     * 跳去处理页面
     */
    public function toHandle(){
        $m = new M();
        if(Input('cid')>0){
            $data = $m->getDetail();
            $this->assign('order',$data);
            $rs = model('SupplierOrders')->getByView($data['orderId']);
            $this->assign('object',$rs);
        }
        $this->assign("p",(int)input("p"));
        return $this->fetch("handle");
    }
    /**
     * 转交给应诉人回应
     */
    public function deliverRespond(){
        $m = new M();
        return $m->deliverRespond();
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }
    /**
     * 仲裁投诉记录
     */
    public function finalHandle(){
        $m = new M();
        return $m->finalHandle();
    }






























    /*
    * 获取数据
    */
    public function get(){
        $m = new M();
        return $m->getById(Input("id/d",0));
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    /**
    * 修改
    */
    public function edit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }

    
}
