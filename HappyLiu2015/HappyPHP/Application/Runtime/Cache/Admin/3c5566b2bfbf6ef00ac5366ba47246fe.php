<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>欢迎您登录后台管理</title>
        <!-- 新 Bootstrap 核心 CSS 文件 -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/test/Public/Admin/css/admin.css" media="all">
    </head>
    <body id="login-page">
        <div id="main-content">
            <!-- 主体 -->
            <form class="form-signin" method="post" action="/test/index.php/Admin/Public/login.html">
                <h2 class="form-signin-heading">登录后台管理系统</h2>
                <input type="text" name="username" class="input-block-level" placeholder="账号" />
                <input type="password" name="password" class="input-block-level" placeholder="密码" />
                <input type="text" name="verify" class="input-medium" placeholder="验证码" />
                <a class="reloadverify" title="换一张" href="javascript:void(0)">换一张？</a>
                <p><img class="verifyimg reloadverify" alt="点击切换" src="<?php echo U('Public/verify');?>"></p>
                <p><button class="btn btn-large btn-primary" id="loginsub" type="submit">登录系统</button> &nbsp;&nbsp; <a class="btn btn-large btn-info" href="/test/">返回前台</a></p>
            </form>
            <!--[if lt IE 9]>
            <script type="text/javascript" src="/test/Public/static/jquery-1.10.2.min.js"></script>
            <![endif]-->
            <!--[if gte IE 9]><!-->
            <script type="text/javascript" src="/test/Public/static/jquery-2.0.3.min.js"></script>
            <!--<![endif]-->
            <script type="text/javascript">
                $(function(){
                    //初始化选中用户名输入框
                    $(".form-signin").find("input[name=username]").focus();
                    //刷新验证码
                    var verifyimg = $(".verifyimg").attr("src");
                    $(".reloadverify").click(function(){
                        if( verifyimg.indexOf('?')>0){
                            $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
                        }else{
                            $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
                        }
                    });

                    //验证账号密码
                    $("#loginsub").click(function(){
                        var username = $("input[name=username]").val();
                        var passwd = $("input[name=password]").val();
                        if(username == '' || passwd == '') {
                            alert('登录账号和密码不能为空!');
                            $(".form-signin").find("input[name=username]").focus();
                            return false;
                        }
                    });

                });
            </script>
        </div>
    </body>
</html>