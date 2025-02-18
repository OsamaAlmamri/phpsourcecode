<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="Keywords" content="代码工厂">
        <meta name="Description" content="每天自学一点点，每天进步一点点">
        <meta name="author" content="Clmao">
        <meta http-equiv="Cache-Control" content="no-transform" />
        <meta http-equiv="Cache-Control" content="no-siteapp"/>
        <title>ClmaoBlog V1.0 安装</title>
        <link rel="apple-touch-icon" href="/Public/appicon.png">
        <link rel="shortcut icon" href="/Public/appicon.png">
        <link href="/Public/zui/zui.min.css" rel="stylesheet">
        <script src="/Public/js/jquery.min.js"></script>
        <script src="/Public/zui/zui.min.js"></script>
        <style>
            .panel{margin: 0 auto;max-width: 80%;}
            .btn{margin-right: 20px;}
            .input-group{margin-bottom: 10px;max-width: 80%;}
        </style>
    </head>
    
   
<body>
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav nav-justified">
            <li class="cat-item nav-system-home active"><a href="/">安装协议</a></li>
            <li class="cat-item active"><a href="javascript:;">环境检测</a></li>
            <li class="cat-item active"><a href="javascript:;">创建数据库</a></li>
            <li class="cat-item active"><a href="javascript:;">安装</a></li>
            <li class="cat-item"><a href="javascript:;">完成</a></li>
        </ul>
    </nav>
    <div class="panel panel-success">
        <div class="panel-heading">ClmaoBlog安装</div>
        <div class="panel-body">
            
                <h1>安装</h1>
                <div id="show-list" class="install-database">
                </div>
                <script type="text/javascript">
                    var list = document.getElementById('show-list');
                    function showmsg(msg, classname) {
                        var li = document.createElement('p');
                        li.innerHTML = msg;
                        classname && li.setAttribute('class', classname);
                        list.appendChild(li);
                        document.scrollTop += 30;
                    }
                </script>
            
            <p><a href="" class="btn btn-lg btn-default disabled"><i class="icon-coffee"></i> 安装中，请稍后</a></p>
        </div>
    </div>


</body>
</html>