<?php if (!defined('THINK_PATH')) exit();?><!--
Author: hxb0810
Author URL: http://www.boxuejyz.com
-->
<!DOCTYPE html>
<html>
<head>
<title><?php echo (C("app_name")); echo (C("app_copy")); ?></title>
<!-- for-mobile-apps -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="选课系统&copy;hxb0810" />
<!-- //for-mobile-apps -->
<link rel="shortcut icon" href="/web/CourseSEL/favicon.ico" type="image/x-icon" />
<link href="/web/CourseSEL/Public/css/style.css" rel="stylesheet" type="text/css" media="all" />
<script src="/web/CourseSEL/Public/js/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="/web/CourseSEL/Public/layui/css/layui.css" media="all">
<!-- //js -->
<script src="/web/CourseSEL/Public/js/easyResponsiveTabs.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#horizontalTab').easyResponsiveTabs({
				type: 'default', //Types: default, vertical, accordion           
				width: 'auto', //auto or any width like 600px
				fit: true   // 100% fit in a container
			});
		});
	   </script>
</head>
<body>
<div class="content">
	<!-- <h1><?php echo (C("site_name")); ?>选课系统</h1> -->
	<h1><?php echo (C("app_name")); ?>-选课系统</h1>
		<div class="main">
			<div class="profile-left wthree">
				<div class="sap_tabs">
				<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
					<ul class="resp-tabs-list">
						<li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>学生登录</span></li>
						<li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><span>教师登录</span></li>
						<div class="clear"> </div>
					</ul>			
					<div class="resp-tabs-container">
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0" >
							<!-- <div class="got">
								<h6>&nbsp;</h6>
							</div> -->
							<div class="login-top" >
								<div >
										<h6>&nbsp;</h6>
								</div>
								<form class="layui-form"><!-- class="loginform" -->
									<div class="layui-inline">
							            <div class="layui-input-inline">
							              <select name="schoolid" id="schoolid" required>
							                  <option value="">请选择学校</option>
							                  <option value="1">孝义中学</option>
							                  <option value="2">孝义二中</option>
							                  <option value="3">孝义三中</option>
							                  <option value="4">孝义四中</option>
							                  <option value="5">孝义五中</option>
							                  <option value="6">孝义实验中学</option>
							                  <option value="7">孝义华杰中学</option>
							                  <option value="8">孝义艺苑中学</option>
							                </select>
							            </div>
						          	</div>	
									<input type="text" class="email" placeholder="学生学（籍）号" id="stuid" required lay-verify="required|number"/>
									<input type="password" class="password" placeholder="Password" id="spass" lay-verify="required"/>
									
									<!-- <input type="checkbox" id="brand" value="">
									<label for="brand"><span></span> Remember me?</label> -->
								<!-- </form> -->
								<div class="login-bottom">
									<ul>
										<li>
											<form>
												<input type="submit" value="登录" lay-submit lay-filter="form1"/>
											</form>
										</li>
										<li>
											<a href="javascript:void(0);" id="foget">忘记密码？</a>
										</li>
									<ul>
									<div class="clear"></div>
								</div>	
							</div>
						</div>
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
							<!-- <div class="got">
								<h6>&nbsp;</h6>
							</div> -->
							<div class="login-top" >
								<div >
									<h6>&nbsp;</h6>
								</div>
								<form action="">
									<input type="text" placeholder="教师用户名" required id="tename" />
									<input type="password" class="password" placeholder="Password" required id="tepass" />	
									<!-- <input type="checkbox" id="brand" value="">
									<label for="brand"><span></span> Remember me?</label> -->
								<!-- </form> -->
								<div class="login-bottom">
									<ul>
										<li>
											
												<input type="submit" value="登录" lay-submit lay-filter="form2"/>
											</form>
										</li>
										<li>
											<a href="javascript:void(0);" id="foget1">忘记密码？</a>
										</li>
									<ul>
									<div class="clear"></div>
								</div>	
							</div>
						</div>
					</div>	
				</div>
				<div class="clear"> </div>
			</div>
			<!-- <div class="social-icons w3agile">
				<ul> 
				<li class="ggp">Sign in with :</li>
					<li><a href="#"><span class="icons"></span><span class="text">Facebook</span></a></li>
					<li class="twt"><a href="#"><span class="icons"></span><span class="text">Twitter</span></a></li>
					<div class="clear"> </div>
				</ul> 
			</div> -->
			</div>
			<div class="clear"> </div>
	</div>	
	<p class="footer">&copy; 2017 |&nbsp;<img src="/web/CourseSEL/16.png" alt=""> CourseSEL | Design by <a href="javascript:;" id="me"> hxb0810</a> | <a href="http://www.halex.top">www.halex.top</a></p>
</div>

<script src="/web/CourseSEL/Public/layui/layui.js"></script> 
<script type="text/javascript">

layui.use(['form', 'layer'], function(){
	  var layer = layui.layer,form = layui.form;
	  //$ = layui.jquery;
	  form.on('submit(form1)', function(){
	    //layer.msg(JSON.stringify(data.field));
	    var stuid=$('#stuid').val();
		var pass=$('#spass').val();
		var schoolid=$('#schoolid').val();
		//alert (name);
		//alert (pass);
		$.get("<?php echo U('ck_stu');?>",{stuid:stuid,spass:pass,schoolid:schoolid},function(data,status){
			    //alert("Data: " + data + "\nStatus: " + status);
			    if (data===2) {
			    	layer.msg('登录成功！');
			    	location.href="/web/CourseSEL/index.php/Index";
			    } 
			    if(data===1){
			    	layer.msg('该帐号已禁用！');
			    }if (data===0) {
			    	layer.msg('帐号密码错误！');
			    } 
		});
	    return false;
	  });
	  form.on('submit(form2)', function(){
	    //layer.msg(JSON.stringify(data.field));
	    var name=$('#tename').val();
		var pass=$('#tepass').val();
		//alert (name);
		//alert (pass);
		$.get("<?php echo U('ck_te');?>",{tename:name,tpass:pass},function(data,status){
			    //alert("Data: " + data + "\nStatus: " + status);
			    if (data===2) {
			    	layer.msg('登录成功！');
			    	location.href="/web/CourseSEL/index.php/Admin";
			    } 
			    if(data===1){
			    	layer.msg('该帐号已禁用！');
			    }if (data===0) {
			    	layer.msg('您尚未注册或帐号密码错误！');
			    } 
		});
	    return false;
	  });
		

}); 
		$("#foget").on("click",function(){
			//alert('aaa');
		    layer.open({
	    				title:'提示：',
	    				content: '请联系班主任！'
	    				,btn: '我知道了'
	  					})
		  });
		$("#foget1").on('click',function(){ 
		layer.open({
	    				title:'提示：',
	    				content: '请联系学校管理员！'
	    				,btn: '我知道了'
	  					})
		});	
	// function check_te(){
	// 	var name=$('#tename').val();
	// 	var pass=$('#tepass').val();
	// 	//alert (name);
	// 	//alert (pass);
	// 	$.get("<?php echo U('ck_te');?>",{tename:name,tpass:pass},function(data,status){
	// 		    //alert("Data: " + data + "\nStatus: " + status);
	// 		    if (data===2) {
	// 		    	layer.msg('登录成功！');
	// 		    	location.href="/web/CourseSEL/index.php/Admin";
	// 		    } 
	// 		    if(data===1){
	// 		    	layer.msg('该帐号已禁用！');
	// 		    }if (data===0) {
	// 		    	layer.msg('您尚未注册或帐号密码错误！');
	// 		    } 
	// 	});
	// 	return false;
	// };
	// function check_stu(){
	// 	var stuid=$('#stuid').val();
	// 	var pass=$('#spass').val();
	// 	var schoolid=$('#schoolid').val();
	// 	//alert (name);
	// 	//alert (pass);
	// 	$.get("<?php echo U('ck_stu');?>",{stuid:stuid,spass:pass,schoolid:schoolid},function(data,status){
	// 		    //alert("Data: " + data + "\nStatus: " + status);
	// 		    if (data===2) {
	// 		    	layer.msg('登录成功！');
	// 		    	location.href="/web/CourseSEL/index.php/Index";
	// 		    } 
	// 		    if(data===1){
	// 		    	layer.msg('该帐号已禁用！');
	// 		    }if (data===0) {
	// 		    	layer.msg('帐号密码错误！');
	// 		    } 
	// 	});
	// 	return false;
	// };
	$('#me').on('click', function() {
        layer.open({
            title: '朋友，你好！',
            btn:'朕已知道',
            type: 0,
            scrollbar: false,
            content: '<div style="width:300px; height:105px; background-color:#0D4061;color: #fff; margin:-20px;"><div style="padding:20px;text-align:center;">Email:hxb0810@163.com<br>Tel:15534378771</div></div>',
            area: ['300px', '200px'],
            shadeClose: true
        });
    });
	
</script>


</body>
</html>