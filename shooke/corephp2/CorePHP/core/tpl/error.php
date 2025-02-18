<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>错误提示!</title>
<STYLE>
body{ COLOR: #333; background:#f8f8f8; font-size: 12px;padding:0px;margin:0px;}
a{text-decoration:none;color:#3071BF}
.wrap{ margin:20px auto; padding:10px 10px 0; max-width:800px;background:#fff;border:1px solid #DDD;border-radius:10px;overflow:hidden;}
h1{text-align:center;}
.message{font-size:1.2em; line-height:22px; padding:15px 15px;background:#f2dede;color:#a94442;border:solid 1px #ebccd1;border-radius:5px;}
.foot{height:50px;border-top:1px solid #DDD;margin:15px -15px 0px;}
.foot a{display:block;width:33.33333333%;float:left;text-align:center;height:50px;line-height:50px;background:#fafafa;border-left:1px solid #DDD;box-sizing:border-box;}
.foot a:hover,.foot a:focus{background:#eee;}
@media only screen and (max-width: 767px) {
	.wrap{margin:20px;}
}
</STYLE>
</head>
<body>
	<div class="wrap">
	<h1>错误提示</h1>
	<div class="message">出错信息：<?php echo $message; ?></div>	
	<?php if( $errorCode && Config::get('DEBUG') ) { ?>
	 	<div class="message">出错文件：<?php echo $errorFile; ?></div>
		<div class="message">错误行：<?php echo $errorLine; ?></div>
		<div class="message">错误级别：<?php echo $errorLevel; ?></div>
		<div class="message">Trace信息：<br><?php echo $trace; ?></div>
	<?php } ?>
<div class="foot">
<a href="javascript:history.back()" title="返回">返回</a>
<a href="<?php echo $url; ?>" title="转到指定页面">跳转</a>
<a href="<?php echo __APP__; ?>" title="回到首页">回到首页</a> 
</div>
</div>
</body>
</html>