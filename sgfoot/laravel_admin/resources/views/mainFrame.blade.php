<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>首页 · 后台模板 HTML</title>
    <link rel="stylesheet" href="{{asset('frame/layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{asset('frame/static/css/style.css')}}">
    <link rel="icon" href="{{asset('frame/static/image/code.png')}}">
</head>
<body>
<!-- layout admin -->
<div class="layui-layout layui-layout-admin"> <!-- 添加skin-1类可手动修改主题为纯白，添加skin-2类可手动修改主题为蓝白 -->
    <!-- header -->
    <div class="layui-header my-header">
        <a href="{{route('home')}}">
            <!--<img class="my-header-logo" src="" alt="logo">-->
            <div class="my-header-logo">后台模板by Laravel5.3</div>
        </a>
        <div class="my-header-btn">
            <button class="layui-btn layui-btn-small btn-nav"><i class="layui-icon">&#xe65f;</i></button>
        </div>

        <!-- 顶部左侧添加选项卡监听 -->
        <ul class="layui-nav" lay-filter="side-top-left">
        </ul>
        <!-- 顶部右侧添加选项卡监听 -->
        <ul class="layui-nav my-header-user-nav" lay-filter="side-top-right">
            <li class="layui-nav-item"><a href="javascript:;" class="refresh" href-url=""><i
                            class="layui-icon">&#x1002;</i>刷新</a></li>
            <li class="layui-nav-item">
                <a class="name" href="javascript:;"><i class="layui-icon">&#xe629;</i>主题</a>
                <dl class="layui-nav-child">
                    <dd><a data-skin="0" href="javascript:;">默认</a></dd>
                    <dd><a data-skin="1" href="javascript:;">纯白</a></dd>
                    <dd><a data-skin="2" href="javascript:;">蓝白</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a class="name" href="javascript:;"><img src="./frame/static/image/code.png" alt="logo"> Admin </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" href-url="demo/set.html"><i class="layui-icon">&#xe614;</i>个人设置</a></dd>
                    <dd><a href="{{route('logout')}}"><i class="layui-icon">&#x1006;</i>退出</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <!-- side -->
    <div class="layui-side my-side">
        <div class="layui-side-scroll">
            <!-- 左侧主菜单添加选项卡监听 -->
            <ul class="layui-nav layui-nav-tree" lay-filter="side-main">

            </ul>
        </div>
    </div>
    <!-- body -->
    <div class="layui-body my-body">
        <div class="layui-tab layui-tab-card my-tab" lay-filter="card" lay-allowClose="true">
            <ul class="layui-tab-title">
                <li class="layui-this" lay-id="1"><span><i class="layui-icon">&#xe638;</i>欢迎页</span></li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe id="iframe" src="demo/welcome.html" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="layui-footer my-footer">
        <p><a href="http://vip-admin.com" target="_blank">vip-admin 后台模板v1.8.0</a>&nbsp;&nbsp;&&nbsp;&nbsp;<a
                    href="http://admin-laravel.sgfoot.com" target="_blank">admin-laravel管理系统v1.0.0</a></p>
        <p>2017-{{date('Y')}} © vip-admin & admin-laravel</p>
    </div>
</div>


<!-- 右键菜单 -->
<div class="my-dblclick-box none">
    <table class="layui-tab dblclick-tab">
        <tr class="card-refresh">
            <td><i class="layui-icon">&#x1002;</i>刷新当前标签</td>
        </tr>
        <tr class="card-close">
            <td><i class="layui-icon">&#x1006;</i>关闭当前标签</td>
        </tr>
        <tr class="card-close-all">
            <td><i class="layui-icon">&#x1006;</i>关闭所有标签</td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="{{asset('frame/layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset('frame/static/js/vip_comm.js')}}?v={{time()}}"></script>
<script type="text/javascript">
    layui.use(['layer', 'vip_nav'], function () {
        // 操作对象
        var layer = layui.layer
            , vipNav = layui.vip_nav
            , $ = layui.jquery;

        // 顶部左侧菜单生成 [请求地址,过滤ID,是否展开,携带参数]
        vipNav.top_left('{{route('config', ['flag'=>'top'])}}', 'side-top-left', false);
        // 主体菜单生成 [请求地址,过滤ID,是否展开,携带参数]
        vipNav.main('{{route('config', ['flag'=>'left'])}}', 'side-main', false);
        // you code ...

    });
</script>
</body>
</html>