<?php if(!defined('HDPHP_PATH'))exit;C('SHOW_NOTICE',FALSE);?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢迎使用HDPHP框架</title>
    <style type="text/css">
        body{
            background: #F4655F;
        }
        div#main {
            padding: 30px 50px;
            font-family: "Microsoft Yahei", Helvetica, arial, sans-serif;
            color:#333;
            margin: 20px;
            background: #F3726D;
        }

        div#main h1 {
            font-size: 180px;
            font-weight: bold;
            margin: 0px;
            padding-bottom: 30px;
            color:#ffffff;
            text-align: center;
        }

        div#main div.hdphp {
            font-size: 40px;
            color:#ffffff;
            text-align: center;
        }

        div#main div.path {
            font-size: 22px;
            margin-top: 30px;
            background: #D35B56;
            color:#fff;
            padding: 6px 10px;
            text-align: center;
        }

    </style>
</head>
<body>
<div id="main">
    <h1> √  </h1>
    <div class="hdphp">欢迎使用 <b>HDPHP </b>!为PHP爱好者创造的框架</div>
    <div class="path">
        [ 您现在访问的是<?php echo $hd['const']['MODULE'];?>模块的<?php echo $hd['const']['CONTROLLER'];?>控制器<?php echo $hd['const']['ACTION'];?>动作 ]
        <a href="<?php echo U('Admin/Index/index');?>">登录后台</a>
    </div>
</div>
</body>
</html>