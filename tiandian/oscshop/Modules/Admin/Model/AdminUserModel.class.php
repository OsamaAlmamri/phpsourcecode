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
namespace Admin\Model;
class AdminUserModel{
	/**
	 *显示分页	 
	 */
	public function show_admin_user_page(){
		
		$sql="select * from ".C('DB_PREFIX')."admin ";	
		
		$count=count(M()->query($sql));
		
		$Page = new \Think\Page($count,C('BACK_PAGE_NUM'));
		$show  = $Page->show();// 分页显示输出	
		
		$sql.=' order by a_id desc LIMIT '.$Page->firstRow.','.$Page->listRows;
		
		$list=M()->query($sql);	
		
		return array(
			'empty'=>'<tr><td colspan="20">~~暂无数据</td></tr>',
			'list'=>$list,
			'page'=>$show
		);	
		
	}
	
	function add_admin_user($data){
			if(empty($data['a_uname'])){
				$error="用户名不能为空！！";				
			}elseif(M('Admin')->getByAUname(trim($data['a_uname']))){
				$error="用户名已经存在！！";				
			}elseif(empty($data['a_passwd'])){
				$error="密码不能为空！！";				
			}
			
			if($error){		
				return array(
					'status'=>'back',
					'message'=>$error				
				);
			}
			
			$data['a_passwd']  =think_ucenter_encrypt($data['a_passwd'],C('PWD_KEY'));
			$data['a_create_time']  =time();
			$data['a_status']  =1;
			
			
			if(M('Admin')->add($data)){
				return array(
				'status'=>'success',
				'message'=>'新增成功',
				'jump'=>U('AdminUser/index')
				);
			}else{
				return array(
				'status'=>'back',
				'message'=>'新增失败'
				
				);
			}
	}
	
	
	function edit_admin_user($d){

		$d['a_passwd']=think_ucenter_encrypt($d['a_passwd'],C('PWD_KEY'));
		
		$r=M('Admin')->where(array('a_id'=>$d['a_id']))->save($d);
		if($r){				
			return array(
				'status'=>'success',
				'message'=>'修改成功',
				'jump'=>U('AdminUser/index')
				);	
		}else{
			return array(
				'status'=>'fail',
				'message'=>'修改失败',
				'jump'=>U('AdminUser/index')
			);
		}
		
	}
	
}
?>