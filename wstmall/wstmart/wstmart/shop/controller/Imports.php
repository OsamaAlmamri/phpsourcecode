<?php
namespace wstmart\shop\controller;
use wstmart\shop\model\Imports as M;
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
 * 默认控制器
 */
class Imports extends Base{
	protected $beforeActionList = ['checkAuth'];
	/**
	 * 数据导入首页
	 */
	public function index(){
		return $this->fetch('import');
	}
	
    /**
     * 上传商品数据
     */
    public function importGoods(){
    	$rs = WSTUploadFile();	
    	if(json_decode($rs)->status==1){
			$m = new M();
    	    $rss = $m->importGoods($rs);
    	    return $rss;
		}
    	return $rs;
    }
}
