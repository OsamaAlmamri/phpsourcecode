<?php /*a:1:{s:50:"D:\phpstudy_pro\WWW\tp\view\index\login\index.html";i:1601288279;}*/ ?>
<!doctype html>
<html  class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台登录-X-admin2.2</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/login.css">
    <!-- <link rel="stylesheet" href="/static/admin/css/xadmin.css"> -->
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="login-bg" >

<div class="login layui-anim layui-anim-up">
    <div class="message">BOOL酒店管理系统1.0</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form" >
        <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" id="username">
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input" id="password">
        <hr class="hr15">

        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="button" onclick="login()">
        <hr class="hr20" >
    </form>
</div>

<!-- 底部结束 -->

<!--<script src="https://cdn.bootcdn.net/ajax/libs/layer/1.8.5/layer.min.js"></script>-->
<link href="/static/toastr/toastr.css" rel="stylesheet"/>
<script src="/static/toastr/toastr.js"></script>
<script>
    function login() {
        $.ajax({
            type:"post",
            url: "<?php echo url('index/login/index'); ?>",
            data: {
                username:$('#username').val(),
                password:$('#password').val(),
                classes:$('#classes').val(),
            },
            success: function(data){
                console.log(data);
                toastr.error(data.msg);
                if(data.code == '100'){
                    setTimeout(function () {
                        window.location.href="<?php echo url('index/index/index'); ?>";
                    },1000);
                }else if(data.code == '200'){
                    setTimeout(function () {
                        window.location.href="<?php echo url('home/index/index'); ?>";
                    },1000);
                }
            }});
    }
</script>
</body>
</html>