
<?php



Const Base_Target = ""
Const ChannelID = 1
ClassID = ""

?>
<?php require_once "AppCode/Conn.php" ?>
<?php require_once "AppCode/fun/function.php" ?>
<?php require_once "AppCode/Pager.php" ?>
<?php require_once "vbs.php" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��Ա��½ - <?php
=Application(SiteID & "_Ok3w_SiteName")
?></title>
<script language="javascript" src="js/js.js"></script>
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="images/DatePicker/WdatePicker.js"></script>	
<link rel="stylesheet" type="text/css" href="images/default/style.css">
</head>

<body>
<?php require_once "head.php" ?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="nav">
  <tr>
    <td><strong>��ǰλ�ã�</strong><a href="./">��վ��ҳ</a> &gt;&gt; ��Ա��½</td>
  </tr>
</table>
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top" style="padding:8px;border:1px solid #CCCCCC;">
		 
		  <div style="border:1px solid #CCCCCC;">
		     <div style="background-color:#F5F5F5; padding:8px;"><strong>��ӭ��½</strong></div>
			 <div style="padding:8px;">
		      <table border="0" cellspacing="8" cellpadding="0">
		        <form  method="post" action="">			  
                
                <tr>
                  <td align="right">�û�����</td>
                  <td><input name="User_Name" type="text" style="width:150px;" id="User_Name" size="20" maxlength="20" />
                    </td>
                  <td><span class="red12">*</span>4-20λ�ַ�������������</td>
                </tr>
                <tr>
                  <td align="right">���룺</td>
                  <td><input name="User_Password" type="password" style="width:150px;" id="User_Password" size="20" maxlength="20" />
                    </td>
                  <td><span class="red12">*</span>6-20λ�ַ�</td>
                </tr>
                <tr>
                  <td align="right">��֤�룺</td>
                  <td><input name="ValidCode" type="text" id="ValidCode" style="width:50px;" size="6" maxlength="4" /></td>
                  <td><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><span class="red12">*</span></td>
	<td>&nbsp;�������&nbsp;</td>
    <td><img src="./c/validcode.php" alt="�����壿�����һ��" name="strValidCode" width="40" height="10" border="0" id="strValidCode" onclick="Get_ValidCode('./');" class="vcode" /></td>
  </tr>
</table></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="bntSubmit" type="button" id="bntSubmit" onClick="Ok3w_UserLogin(this.form,'./')" value="��½" style="padding-top:4px; cursor:pointer;width:52px;" /> </td>
                  <td><span class="red12">*</span><a href="User_Reg.php">��û��ע�᣿</a> <a href="#">�������룿</a></td>
                </tr>
</form>
              </table>
             </div>
		    </div>
    </td>
    <td width="346" align="right" valign="top">
	  <?php require_once "right.php" ?>
    </td>
  </tr>
</table>
<?php require_once "foot.php" ?>
</body>
</html>
