<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo (session('_systemname')); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/uap/Public/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/uap/Public/adminlte/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/uap/Public/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/uap/Public/adminlte/dist/css/skins/_all-skins.min.css">

    <script src="/uap/Public/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/uap/Public/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/uap/Public/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/uap/Public/adminlte/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/uap/Public/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="/uap/Public/adminlte/plugins/fastclick/fastclick.min.js"></script>
    <script src="/uap/Public/adminlte/dist/js/app.min.js"></script>

    <script src="/uap/Public/underscore-min.js"></script>
    <link rel="stylesheet" href="/uap/Public/zTree/css/zTreeStyle/metro.css"/>
    <script src="/uap/Public/zTree/js/jquery.ztree.all-3.5.min.js"></script>
    <script src="/uap/Public/bootstrap-typeahead.js"></script>
</head>
<style>
    th {
        vertical-align: middle;
        text-align: center;
    }

    td {
        vertical-align: middle;
        text-align: center;
    }
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a href="/uap/Public/adminlte/index2.html" class="logo">
            <span class="logo-lg"><?php echo (session('_systemname')); ?></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="/uap/home/login">
                            <span class="hidden-xs" >注销</span>
                        </a>

                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">

                <?php $menus = $_SESSION['menus']; ?>

                <?php $smid = $_SESSION['mid']; ?>
                <?php $scmid = $_SESSION['cmid']; ?>

                <?php if(is_array($menus)): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["parentid"] == 0): $vid = $vo["id"]; ?>

                        <?php if($smid == $vid): ?><li class="treeview active">
                                <?php else: ?>
                            <li class="treeview"><?php endif; ?>
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <?php echo ($vo["name"]); ?><span></span> <i
                                class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu menu-open">
                            <?php if(is_array($menus)): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$so): $mod = ($i % 2 );++$i; $cid = $so["parentid"]; ?>
                                <?php $coid = $so["id"]; ?>
                                <?php if($cid == $vid): if($scmid == $coid): ?><li class="active">
                                            <?php else: ?>
                                        <li><?php endif; ?>
                                    <a
                                            onclick="loadAndSetMenuSession('<?php echo ($so["parentid"]); ?>',
                                                '<?php echo ($so["id"]); ?>','/uap/<?php echo ($so["url"]); ?>')"
                                            href="javascript:void(0)"><i
                                            class="fa fa-circle-o"></i>
                                        <?php echo ($so["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                            <!--<li class="active"><a href="/uap/home/config"><i class="fa fa-circle-o"></i>-->
                            <!--系统参数设置</a>-->
                            <!--</li>-->
                            <!--<li class="active"><a href="/uap/home/menu"><i class="fa fa-circle-o"></i> 菜单管理</a>-->
                            <!--</li>-->
                            <!--<li class="active"><a href="/uap/home/role"><i class="fa fa-circle-o"></i> 角色管理</a>-->
                            <!--</li>-->
                            <!--<li class="active"><a href="/uap/home/user"><i class="fa fa-circle-o"></i> 用户管理</a>-->
                            <!--</li>-->
                        </ul>
                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        
<section class="content-header">
    <h1>
        设置角色
    </h1>
</section>

<div class="content">
    <div class="box box-info">
        <div class="box-header with-border">
        </div>
        <form id="form" action="" class="form-horizontal" method="post">
            <input style="display: none" value="<?php echo ($id); ?>" name="id" id="id">
            <input style="display: none" value="<?php echo ($roleids); ?>" name="roleids" id="roleids">
            <div class="box-body table-responsive no-padding">
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 1%;text-align: center"><input name="pmenu" type="checkbox"
                                                                        onclick="checktb(this,'roleids')"
                                /></th>
                        <th style="width: 45%;text-align: center">角色名称</th>
                        <th style="width: 45%;text-align: center">备注</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if(is_array($roles)): $i = 0; $__LIST__ = $roles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td style="text-align: center;vertical-align: middle"><input id="<?php echo ($vo["id"]); ?>"
                                                                                         onclick="checktb(this,'roleids')"
                                                                                         type="checkbox"
                                    />
                            </td>
                            <td style="text-align: center;vertical-align: middle"><?php echo ($vo["name"]); ?></td>
                            <td style="text-align: center;vertical-align: middle"><?php echo ($vo["notes"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <a href="javascript:void(0)" onclick="commonSubmit('form','/uap/home/user/configRoleEntity','')"
                   class="btn btn-primary pull-right" style="margin-left: 5px">提交</a>
                <a href="/uap/home/user" class="btn btn-default  pull-right">返回</a>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        var roleids = '<?php echo ($roleids); ?>';
        var ids = roleids.split(',');
        $(ids).each(function () {
            $('#' + this).prop('checked', true);
        })
    });
</script>
        <!-- Content Header (Page header) -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Speed-CMDB</b> 0.0.1
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="#">Speed Studio</a>.</strong> All rights reserved.
    </footer>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- AdminLTE for demo purposes -->
<script>
    function loadAndSetMenuSession(fMenu, sMenu, url) {
        $.post('/uap/home/common/setMenuSession',
                {'id': fMenu, 'subid': sMenu},
                function (rs) {
                    debugger
                    window.location.href = url;
                }
        );
    }

    function validate() {
        var obj = $("div[required]");
        var flag = true;
        for (var i = 0; i < obj.length; i++) {
            if (obj[i].children[1].children[0].value == "") {
                alert("请填写[" + obj[i].children[0].textContent + "]");
                flag = false;
                break;
            }
        }
        return flag;
    }
    function commonSubmit(formName, actionName, reload) {
        var data = $('#' + formName).serialize();
        if (validate()) {
            $.post(actionName, data, function (rs) {
                debugger;
                if (rs == "") {
                    if (reload == true) {
                        window.location.reload();
                    } else {
                        location.href = document.referrer;
                    }
                } else {
                    alert(rs);
                }
            }).error(function () {
                alert('操作异常，请联系管理员');
            });
        }
    }

    function deleteEntity(url, id) {
        //$('#loadingMsg').addClass('active');
        $.post(url, {"id": id}, function (rs) {
            //$('#loadingMsg').removeClass('active');
            if (rs == "") {
                window.location.reload();
            } else {
                alert(rs);
            }
        })
    }

    function checktb(obj, id) {
        if (obj.name == 'pmenu' && obj.checked == true) {
            $($("input[type='checkbox']")).each(function () {
                $(this).prop('checked', true);
            });
        }
        if (obj.name == 'pmenu' && obj.checked == false) {
            $($("input[type='checkbox']")).each(function () {
                $(this).removeAttr("checked");
            });
        }

        var uids = "";
        $($("input[type='checkbox']")).each(function () {
            if (this.checked == true && this.name != 'pmenu') {
                uids += this.id + ",";
            }
        });
        $("#" + id).val(uids);
    }
</script>
</body>
</html>