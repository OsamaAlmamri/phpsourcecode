
<?php
Const dbdns="../"
?>
<?php require_once("chk.php");  ?>
<?php require_once "../AppCode/Class/Ok3w_Vote.php" ?>
<?php require_once("../AppCode/fun/function.php");  ?>
<?php require_once "../AppCode/fun/CreateHtml.php" ?>
<?php

Call CheckAdminFlag(1)
pID = Trim(Request.QueryString("pID"))

Set Vote = New Ok3w_Vote
Select Case Trim(Request.Form("action"))
	Case "add"
		Call Vote.Add()
		Call SaveAdminLog("添加投票：" & Vote.Title)
		Call CloseConn()
		Call ActionOk("Sys_Vote.php?pID=" & pID)
	Case "edit"
		Call Vote.Edit()
		Call SaveAdminLog("修改投票(ID=" & Vote.ID & ")为：" & Vote.Title)
		Call CloseConn()
		Call ActionOk("Sys_Vote.php?pID=" & pID)
	Case "del"
		Call Vote.Del()
		Call SaveAdminLog("删除投票(ID=" & Vote.ID & ")")
		Call CloseConn()
		Call ActionOk("Sys_Vote.php?pID=" & pID)
End Select

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="images/Style.css">
<style type="text/css">
.noB{
	border:0px;
	background-color:#FFFFFF;
	cursor:pointer;
}
.STYLE1 {color: #0000FF}
</style>
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
                  <td class="mtitle" 
                background="images/tab_active_bg.gif">投票管理</td>
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
                valign="top" bgcolor="#fffcf7">
<?php

If pID="" Then
?>			
				      <table border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
				        <form name="form1" method="post" action="">
                        <tr>
                          <td bgcolor="#FFFFFF">标题/有效期</td>
                          <td bgcolor="#FFFFFF">说明</td>
                          <td bgcolor="#FFFFFF">类型</td>
                          <td bgcolor="#FFFFFF">排序</td>
                          <td bgcolor="#FFFFFF">添加</td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">
                            <input name="Title" type="text" id="Title" size="25" maxlength="225"><br>开始：<input name="bTime" type="text" id="bTime" value="<?php
=Now()
?>" size="20" /><br>结束：<input name="eTime" type="text" id="eTime" value="<?php
=Now()+365
?>" size="20" /></td>
                          <td bgcolor="#FFFFFF"><textarea name="Content" cols="50" rows="5" id="Content"></textarea></td>
                          <td bgcolor="#FFFFFF"><input name="Value" type="radio" value="0" checked>
                            单选<br>
                              <input type="radio" name="Value" value="1">
                              多选</td>
                          <td bgcolor="#FFFFFF"><input name="Xu" type="text" id="Xu" value="0" size="4"></td>
                          <td bgcolor="#FFFFFF"><input name="action" type="hidden" id="action" />
                            <input type="button" name="Submit3" value="添 加" onClick="javascript:formsubmit(this.form,'add');"></td>
                        </tr>  </form>
                      </table>
				      <br>
				      <table border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
                        <tr  bgcolor="#EBEBEB">
                          <td align="center">编号</td>
                          <td align="center">标题/有效期</td>
                          <td align="center">说明</td>
                          <td align="center">类型</td>
                          <td align="center">排序</td>
                          <td align="center">选项</td>
                          <td align="center">操作</td>
                        </tr>
											<form name="form2" method="post" action="">
                         </form>
<?php

Sql = "select * from Ok3w_Vote where pID=0  order by Xu"
Rs.Open Sql,Conn,1,1
Do While Not Rs.Eof

?>						<form name="form2" method="post" action="">
                        <tr bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                          <td><input name="ID" type="text" id="ID" value="<?php
=Rs("ID")
?>" size="4" readonly="readonly" /></td>
                          <td><input name="Title" type="text" id="Title" value="<?php
=Rs("Title")
?>" size="25" maxlength="225"><br>开始：<input name="bTime" type="text" id="bTime" value="<?php
=Rs("bTime")
?>" size="20" /><br>结束：<input name="eTime" type="text" id="eTime" value="<?php
=Rs("eTime")
?>" size="20" /></td>
                          <td><textarea name="Content" cols="45" rows="5" id="Content"><?php
=Rs("Content")
?></textarea></td>
                          <td><input name="Value" type="radio" value="0" <?php
If Rs("Value")=0 Then
?>checked<?php
End If
?>>
单选<br>
<input type="radio" name="Value" value="1" <?php
If Rs("Value")=1 Then
?>checked<?php
End If
?>>
多选</td>
                          <td><input name="Xu" type="text" id="Xu" value="<?php
=Rs("Xu")
?>" size="4"></td>
                          <td><a href="?pID=<?php
=Rs("ID")
?>">&gt;&gt;&gt;</a></td>
                          <td><input name="action" type="hidden" id="action" />
                            <input name="Submit2" type="button" onClick="javascript:formsubmit(this.form,'edit');" value="修 改" />
                            <input name="Submit22" type="button" onClick="javascript:if(!confirm('真的要删除吗？')) return false;formsubmit(this.form,'del');" value="删 除" /></td>
                        </tr> </form>
<?php

	Rs.MoveNext
Loop
Rs.Close

?>						
                      </table>
<?php
Else
?>

<strong>投票：<?php
=Conn.Execute("select Title from Ok3w_Vote where ID=" & pID)(0)
?>
</strong>
<br />
<table border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
				        <form name="form1" method="post" action="?pID=<?php
=pID
?>">
                        <tr>
                          <td bgcolor="#FFFFFF">标题</td>
                          <td bgcolor="#FFFFFF">得票数</td>
                          <td bgcolor="#FFFFFF">排序</td>
                          <td bgcolor="#FFFFFF">添加</td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">
                            <input name="Title" type="text" id="Title" size="50" maxlength="225">                          </td>
                          <td bgcolor="#FFFFFF"><input name="Value" type="text" id="Value" value="0" size="4"></td>
                          <td bgcolor="#FFFFFF"><input name="Xu" type="text" id="Xu" value="0" size="4"></td>
                          <td bgcolor="#FFFFFF"><input name="action" type="hidden" id="action" />
                            <input name="Content" type="hidden" id="Content" value="#">
                            <input name="pID" type="hidden" id="pID" value="<?php
=pID
?>">
                            <input type="button" name="Submit3" value="添 加" onClick="javascript:formsubmit(this.form,'add');"></td>
                        </tr>  </form>
                      </table>
				      <br>
				      <table border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
                        <tr  bgcolor="#EBEBEB">
                          <td align="center">标题</td>
                          <td align="center">得票数</td>
                          <td align="center">排序</td>
                          <td align="center">操作</td>
                        </tr>
											<form name="form2" method="post" action="">
                         </form>
<?php

Sql = "select * from Ok3w_Vote where pID=" & pID & " order by Xu"
Rs.Open Sql,Conn,1,1
Do While Not Rs.Eof

?>						<form name="form2" method="post" action="?pID=<?php
=pID
?>">
                        <tr bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                          <td><input name="Title" type="text" id="Title" value="<?php
=Rs("Title")
?>" size="50" maxlength="225"></td>
                          <td><input name="Value" type="text" id="Value" value="<?php
=Rs("Value")
?>" size="4"></td>
                          <td><input name="Xu" type="text" id="Xu" value="<?php
=Rs("Xu")
?>" size="4"></td>
                          <td><input name="action" type="hidden" id="action" />
                            <input name="Content" type="hidden" id="Content" value="#">
                            <input name="pID" type="hidden" id="pID" value="<?php
=Rs("pID")
?>">
							<input name="ID" type="hidden" id="ID" value="<?php
=Rs("ID")
?>">
                            <input name="Submit2" type="button" onClick="javascript:formsubmit(this.form,'edit');" value="修 改" />
                            <input name="Submit22" type="button" onClick="javascript:if(!confirm('真的要删除吗？')) return false;formsubmit(this.form,'del');" value="删 除" /></td>
                        </tr> </form>
<?php

	Rs.MoveNext
Loop
Rs.Close

?>						
                      </table>
					  
<?php
End If
?>
<script language="JavaScript" type="text/javascript">
function formsubmit(frm,action)
{
	if(frm.Title.value.trim()=="")
	{
		ShowErrMsg("标题不能为空，请输入");
		frm.Title.focus();
		return false;
	}
	 
	if(frm.Content.value.trim()=="")
	{
		ShowErrMsg("内容不能为空，请输入");
		frm.Content.focus();
		return false;
	}
	
	frm.action.value = action;
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
Set Rs = Nothing
Set Admin = Nothing

?>