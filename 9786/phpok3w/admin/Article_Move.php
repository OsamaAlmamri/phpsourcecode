
<?php
Const dbdns="../"
?>
<?php require_once("chk.php");  ?>
<?php require_once("../AppCode/fun/function.php");  ?>
<?php

action = Request.QueryString("action")
strClassID = Request.QueryString("strClassID")
ChannelID = Request.QueryString("ChannelID")
formClassID = Request.Form("formClassID")
toClassID = Request.Form("toClassID")

If action = "move" Then
	SortPath = Conn.Execute("select SortPath from Ok3w_Class where ID=" & toClassID)(0)
	If ChannelID=1 Then
		Table = "Ok3w_Article"
	Else
		Table = "Ok3w_Soft"
	End If
	If strClassID="" Then
		Sql = "update " & Table & " set ClassID=" & Cdbl(toClassID) & ",SortPath='" & SortPath & "' where ClassID=" & Cdbl(formClassID)
		Conn.Execute Sql
		
		Call SaveAdminLog("转移分类：ChannelID=" & ChannelID & ",formClassID=" & formClassID & ",toClassID=" & toClassID)
		Call ActionOk("Article_Move.php?ChannelID=" & ChannelID)
	Else
		strClassID = strClassID & "0"
		
		Sql = "update " & Table & " set ClassID=" & Cdbl(toClassID) & ",SortPath='" & SortPath & "' where ID in(" & strClassID & ")"
		Conn.Execute Sql
		
		Call SaveAdminLog("转移分类：strClassID=" & strClassID)
		Call ActionOk("Article_List.php?ChannelID=" & ChannelID)
	End If
End If

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="images/Style.css">
<script language="javascript" src="../js/class.js"></script>
<script language="javascript" src="images/js.js"></script>
</head>

<body>
<?php require_once "top.php" ?>
<br />
<table cellspacing="0" cellpadding="0" width="98%" align="center" border="0">
  <tbody>
    <tr>
      <td style="PADDING-LEFT: 2px; HEIGHT: 22px" 
    background="images/tab_top_bg.gif"><table cellspacing="0" cellpadding="0" width="477" border="0">
        <tbody>
          <tr>
            <td width="147"><table height="22" cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td width="3"><img id="tabImgLeft__0" height="22" 
                  src="images/tab_active_left.gif" width="3" /></td>
                  <td 
                background="images/tab_active_bg.gif" class="tab"><strong class="mtitle">文章分类转移管理</strong></td>
                  <td width="3"><img id="tabImgRight__0" height="22" 
                  src="images/tab_active_right.gif" 
            width="3" /></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td bgcolor="#ffffff"><table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tbody>
          <tr>
            <td width="1" background="images/tab_bg.gif"><img height="1" 
            src="images/tab_bg.gif" width="1" /></td>
            <td 
          style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; PADDING-TOP: 10px" 
          valign="top"><div id="tabContent__0" style="DISPLAY: block; VISIBILITY: visible">
              <table cellspacing="1" cellpadding="1" width="100%" align="center" 
            bgcolor="#8ccebd" border="0">
                <tbody>
                  <tr>
                    <td 
                style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; PADDING-TOP: 10px" 
                valign="top" bgcolor="#fffcf7"><table border="0" cellpadding="5" cellspacing="1" bgcolor="#EBEBEB">
                      <form id="Form" name="Form" method="post" action="?action=move&ChannelID=<?php
=ChannelID
?>&strClassID=<?php
=strClassID
?>">
                        <tr bgcolor="#FFFFFF">
                          <td><?php
If strClassID<>"" Then
?>文章ID：<br>
                            <textarea name="textarea" rows="20" disabled="disabled"><?php
=strClassID
?></textarea>
							<?php
Else
?>
                            原类别：<br>
                            <select name="formClassID" size="20" id="formClassID"></select>
							 <script language="javascript">
						  InitSelect(document.Form.formClassID,"<?php
=ChannelId
?>","");
						  </script>
							<?php
End If
?></td>
                          <td>转移到：<br>
                            <select name="toClassID" size="20" id="toClassID"></select>
							<script language="javascript">
						  InitSelect(document.Form.toClassID,"<?php
=ChannelId
?>","");
						  </script>
							</td>
                          </tr>
                        
                        <tr bgcolor="#FFFFFF">
                          <td colspan="2"><input type="button" name="bntSubmit" value="确定" onClick="submitform(this.form)"></td>
                          </tr>
                      </form>
                    </table>
<script language="JavaScript" type="text/javascript">
function submitform(frm)
{
<?php
If strClassID="" Then
?>
	if(frm.formClassID.value=="")
	{
		ShowErrMsg("请选择原始分类");
		frm.formClassID.focus();
		return false;
	}
<?php
End If
?>	
	if(frm.toClassID.value=="")
	{
		ShowErrMsg("请选择转移到的分类");
		frm.toClassID.focus();
		return false;
	}
	
	
	frm.bntSubmit.disabled = true;
	frm.bntSubmit.value = "请稍候...";
	
	frm.submit();
}
</script>
</td>
                  </tr>
                </tbody>
              </table>
            </div></td>
            <td width="1" background="images/tab_bg.gif"><img height="1" 
            src="images/tab_bg.gif" width="1" /></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td background="images/tab_bg.gif" bgcolor="#ffffff"><img height="1" 
      src="images/tab_bg.gif" width="1" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php

Call CloseConn()
Set Article = Nothing
Set Admin = Nothing

?>

