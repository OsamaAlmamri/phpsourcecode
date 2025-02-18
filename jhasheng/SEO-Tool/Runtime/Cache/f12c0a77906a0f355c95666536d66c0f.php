<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($lang_metinfo); ?></title>
<link href="__PUBLIC__/css/metinfo.css" rel="stylesheet" />

<script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.0.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/cookie.js"></script>
<script type="text/javascript">
$(function(){
	$('#topnav li').click(function(){
		var topid = $(this).index();
		var index = parseInt(topid) + 1;
		var leftid = 0;
		var link = $('#ul_'+index).find('li').eq(leftid).children('a').attr('href');
		
		$.cookie('leftid',leftid);		
		$.cookie('topid',topid);
		$.cookie('link',link);

		$('#main').attr("src",link);
		$(this).children('a').addClass('onnav').end().siblings().children('a').removeClass('onnav');
		$('#ul_'+index).show().siblings().hide().find('a').removeClass('on');
		$('#ul_'+index).find('li').eq(leftid).children('a').addClass('on');
		//document.title = topid+":"+link;
	});
	$('#leftnav li').click(function(){
		$.cookie('leftid',$(this).index());
		$.cookie('link',$(this).children('a').attr('href'));
		$(this).children('a').addClass('on');
		$(this).siblings('li').children('a').removeClass('on');
	});
});
window.onload = function(){
	var topid = $.cookie('topid')?$.cookie('topid'):0;
	var leftid = $.cookie('leftid')?$.cookie('leftid'):0;
	var index = parseInt(topid)+1;
	var link = $.cookie('link')?$.cookie('link'):$('#ul_'+index+' li').eq(leftid).children('a').attr('href');
	//document.title = topid+":"+leftid+'::'+typeof($.cookie('link'));
	$('#main').attr("src",link);
	$('#topnav').children('li').eq(topid).children('a').addClass('onnav');
	$('#ul_'+index).show().siblings().hide();
	$('#ul_'+index+' li').eq(leftid).children('a').addClass('on');
}
</script>

<style type="text/css">
#top .floatr li a span{ behavior: url(__PUBLIC__/images/iepngfix.htc); }
a{text-decoration: none;}
</style>
</head>
<body>
<div class="main">
<div id="top"> 
    <div class="floatr">
		<div class="top-r-box">
		<div class="top-right-boxr">
			<div class="top-r-t"><a href="<?php echo U('Index/logout');?>" id="mydata" class='tui' style="text-decoration:underline;">退出登陆</a><span>-</span><a target="_top" id="outhome" class='tui'><?php echo (session('aliasname')); ?></a><span>|</span><a title="">qietext</a>
			</div>
		    <div class="langs">
        <div class="langtxt">
			<div class="langkkkbox">
				<div id="langcig">
					<span id="langcion" title="<?php echo ($lang); ?>"><?php echo ($langname); ?></span>
					<span class="langqie"><?php echo ($lang_indexlang); ?></span>
				</div>
				<div class="langlist shadow" style="display:none;"></div>
			</div>
			<div class="webyy"><?php echo ($lang_langweb); ?>：</div>
		</div>
			</div>
		</div>
		<div class="nav">
            <ul id="topnav">
				<li class="list" id="metnav_1">
					<a id="nav_1" ><span class="c4"></span>网站管理</a>
				</li>
                <li class="list" id="metnav_2">
					<a id="nav_2" ><span class="c3"></span>关键词</a>
				</li>
                <li class="list" id="metnav_3">
					<a id="nav_3" ><span class="c5"></span>消费管理</a>
				</li>
                <li class="list" id="metnav_4">
					<a id="nav_4" ><span class="c7"></span>用户管理</a>
				</li>
				<li class="list" id="metnav_5">
					<a id="nav_5" ><span class="c1"></span>系统设置</a>
				</li>
            </ul>
		</div>
		</div>
    </div>
    <div class="floatl">
	    <a  id="met_logo"><img src="<?php echo ($met_agents_logo_index); ?>" /></a>
	</div>
</div>
<div id="content">
    <div class="floatl" id="metleft">
	    <div class="nav_list" id="leftnav">
			<ul id="ul_1">
				<li><a href="<?php echo U('Index/main');?>" target='main' id='nav_4_29' >后台首页</a></li>
				<li><a href="<?php echo U('Site/lists');?>" target='main' id='nav_4_29' >网站管理</a></li>
				<li><a href="<?php echo U('Site/add');?>" target='main' id='nav_4_31' >添加网站</a></li>
				<li><a href="<?php echo U('Site/recycle');?>" target='main' id='nav_4_31' >网站回收站</a></li>
			</ul>
			<ul style="display:none;" id="ul_2">
				<li><a href="<?php echo U('Keyword/lists');?>" target='main' id='nav_3_25' >管理关键词</a></li>
				<li><a href="<?php echo U('Keyword/add');?>" target='main' id='nav_3_26' >添加关键词</a></li>
			</ul>
			<ul style="display:none;" id="ul_3">
				<li><a target='main' id='nav_5_34' target='main' >今日消费</a></li>
				<li><a target='main' id='nav_5_35' target='main' >历史消费</a></li>
			</ul>
			<ul style="display:none;" id="ul_4">
				<li><a href="<?php echo U('User/listuser');?>" target='main' id='nav_7_45' >会员管理</a></li>
				<li><a href="<?php echo U('User/adduser');?>" target='main' id='nav_7_49' >添加会员</a></li>
				<li><a href="<?php echo U('User/rolelist');?>" target='main' id='nav_7_49' >会员组管理</a></li>
				<li><a href="<?php echo U('User/addrole');?>" target='main' id='nav_7_49' >添加会员组</a></li>
				<li><a href="<?php echo U('User/listnode');?>" target='main' id='nav_7_46' >会员权限管理</a></li>
				<li><a href="<?php echo U('User/addnode');?>" target='main' id='nav_7_46' >添加会员权限</a></li>
			</ul>
			<ul style="display:none;" id="ul_5">
				<li><a target='main' id='nav_1_8' href="<?php echo U('Setting/main');?>">系统信息</a></li>
				<li><a target='main' id='nav_1_9' href="<?php echo U('Setting/base');?>">基本设置</a></li>
				<li><a target='main' id='nav_1_10' href="<?php echo U('Setting/rbac');?>">RBAC设置</a></li>
				<!--li><a target='main' id='nav_1_11' >图片设置</a></li-->
				<!--li><a target='main' id='nav_1_12' >安全与效率</a></li-->
				<li><a target='main' id='nav_1_13' >数据与备份</a></li>
				<li><a target='main' id='nav_1_14' >上传文件管理</a></li>
			</ul>
	    </div>
		<div class="claer"></div>
		<div class="left_footer"><a>Krasen</a><span class="none">Jia Junjun</span></div>
	</div>
    <div class="floatr" id="metright">
        <div class="iframe">
		    <div class="min"><iframe frameborder="0" id="main" name="main" src="<?php echo ($morenurl); ?>" scrolling="auto"></iframe></div>
		</div>
    </div>
	<div class="clear"></div>
	</div>
</div>
</body>
</html>