<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="<?php echo base_url()?>Public/admin_style/css/admin.css" type="text/css"/>
<title>无标题文档</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.STYLE1 {
	font-size: 12px;
	color: #000000;
}
.STYLE5 {font-size: 12}
.STYLE7 {font-size: 12px; color: #FFFFFF; }
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="57" background="<?php echo base_url()?>Public/admin_style/images/main_03.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="378" height="57" background="<?php echo base_url()?>Public/admin_style/images/mylogo.png">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="281" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="33" height="27"><img src="<?php echo base_url()?>Public/admin_style/images/main_05.gif" width="33" height="27" /></td>
            <td width="248" background="<?php echo base_url()?>Public/admin_style/images/main_06.gif"><table width="225" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td><div align="right"><a href="<?php echo site_url('admin/logout')?>" onclick="return confirm('确定要退出登陆吗？')" target="_parent"><img border="0" src="<?php echo base_url()?>Public/admin_style/images/quit.gif" width="69" height="17" /></a></div></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="40" background="<?php echo base_url()?>Public/admin_style/images/main_10.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="194" height="40" background="<?php echo base_url()?>Public/admin_style/images/main_07.gif">&nbsp;</td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="21"><img src="<?php echo base_url()?>Public/admin_style/images/main_13.gif" width="19" height="14" /></td>
            <td width="35" class="STYLE7"><div align="center"><a href="<?php echo site_url('home/index')?>" target="_blank">首页</a></div></td>
            <td width="21" class="STYLE7"><img src="<?php echo base_url()?>Public/admin_style/images/main_15.gif" width="19" height="14" /></td>
            <td width="35" class="STYLE7"><div align="center"><a href="javascript:void(0)" onclick="history.go(-1)">后退</a></div></td>
            <td width="21" class="STYLE7"><img src="<?php echo base_url()?>Public/admin_style/images/main_17.gif" width="19" height="14" /></td>
            <td width="35" class="STYLE7"><div align="center"><a href="javascript:void(0)" onclick="history.go(1)">前进</a></div></td>
            <td width="21" class="STYLE7"><img src="<?php echo base_url()?>Public/admin_style/images/main_19.gif" width="19" height="14" /></td>
            <td width="35" class="STYLE7"><div align="center"><a href="javascript:void(0)" onclick="top.location.reload()">刷新</a></div></td>
            <td width="21" class="STYLE7"><img src="<?php echo base_url()?>Public/admin_style/images/main_21.gif" width="19" height="14" /></td>
            <td width="35" class="STYLE7"><a href="http://www.baidu.com" target="_blank"><div align="center">帮助</a></div></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        <td width="248" background="<?php echo base_url()?>Public/admin_style/images/main_11.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="16%"><span class="STYLE5"></span></td>
            <td width="75%"><div id="time" align="center" style="color:#ffffff"><span class="STYLE7"><span>
            <script language="javascript" type="text/javascript">
                 window.onload=function (){
                 setInterval("document.getElementById('time').innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);
 }
</script>
            </span></div></td>
            <td width="9%">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="30" background="<?php echo base_url()?>Public/admin_style/images/main_31.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="8" height="30"><img src="<?php echo base_url()?>Public/admin_style/images/main_28.gif" width="8" height="30" /></td>
        <td width="147" background="<?php echo base_url()?>Public/admin_style/images/main_29.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="24%">&nbsp;</td>
            <td width="43%" height="20" valign="bottom" class="STYLE1">管理菜单</td>
            <td width="33%">&nbsp;</td>
          </tr>
        </table></td>
        <td width="39"><img src="<?php echo base_url()?>Public/admin_style/images/main_30.gif" width="39" height="30" /></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
              <td height="20" valign="bottom"><span class="STYLE1">当前登录用户：<?php if(isset($_SESSION['user']))echo $_SESSION['user'];else echo "未登录"?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;权限：超级管理员 </span></td>
              <td valign="bottom" class="STYLE1"><div align="right"><img src="<?php echo base_url()?>Public/admin_style/images/sj.gif" width="6" height="7" /> 当前登陆&nbsp;IP：<?php echo $_SERVER['REMOTE_ADDR'];?>     &nbsp; &nbsp;&nbsp;<img src="<?php echo base_url()?>Public/admin_style/images/sj.gif" width="6" height="7" /> &nbsp;提供商：<a href="" target="_blank">PJY</a> &nbsp; &nbsp;</div></td>
          </tr>
        </table></td>
        <td width="17"><img src="<?php echo base_url()?>Public/admin_style/images/main_32.gif" width="17" height="30" /></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
