

<?php require_once("chk.php");  ?>
<?php require_once("../AppCode/Pager.php");  ?>
<?php

Call CheckAdminFlag(1)

action = Trim(Request.Form("action"))
Select Case action
	Case "del"
		Call Del()
	Case "clear"
		Call clearLog()
End Select

Private Sub Del()
	ID = Trim(Request.Form("ID"))
	If logId<>"" Then
		sql="delete from Ok3w_Votelog where ID in(" & ID & ")"
		Conn.Execute sql
		Call SaveAdminLog("删除投票日志，ID=" & ID)
		Call ActionOk("Sys_Votelog.php")
	End If
End Sub

Private Sub clearLog()
	Sql = "delete from Ok3w_Votelog"
	Conn.Execute Sql
	Call SaveAdminLog("清空投票日志")
	Call ActionOk("Sys_Votelog.php")
End Sub

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="images/Style.css">
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
                background="images/tab_active_bg.gif">投票日志管理</td>
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
                      <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
						<form id="form2" name="form2" method="get" action="">
						  </form>
						<form id="form1" name="form1" method="post" action="">
						<tr>
                          <td height="25" align="center" bgcolor="#EBEBEB">ID</td>
                          <td height="25" align="center" bgcolor="#EBEBEB">pID</td>
                          <td height="25" align="center" bgcolor="#EBEBEB">vID</td>
                          <td height="25" align="center" bgcolor="#EBEBEB">vIP</td>
                          <td height="25" align="center" bgcolor="#EBEBEB">vTime</td>
                          <td height="25" align="center" bgcolor="#EBEBEB"><input type="checkbox" name="checkbox" value="checkbox" onClick="javascript:chkall(this,this.form);" /></td>
                        </tr>
                        
                        <?php

stype = Trim(Request.QueryString("stype")) 
Sql = "select * from Ok3w_Votelog where 1=1"
Sql = Sql & " order by ID desc"
Set Page = New TurnPage
Call Page.GetRs(Conn,Rs,Sql,20)
Do While Not Rs.Eof And Not Page.Eof

?>
                        <tr>
                          <td height="25" align="center" bgcolor="#FFFFFF"><?php
=Rs("ID")
?></td>
                          <td height="25" align="center" bgcolor="#FFFFFF"><?php
=Rs("pID")
?></td>
                          <td height="25" align="center" bgcolor="#FFFFFF"><?php
=Rs("vID")
?></td>
                          <td height="25" align="center" bgcolor="#FFFFFF"><?php
=Rs("vIP")
?></td>
                          <td height="25" align="center" bgcolor="#FFFFFF"><?php
=Rs("vTime")
?></td>
                          <td height="25" align="center" bgcolor="#FFFFFF"><input name="ID" type="checkbox" id="ID" value="<?php
=Rs("ID")
?>" /></td>
                        </tr>
                        <?php

	Rs.MoveNext
	Page.MoveNext
Loop
Rs.Close

?>
                        <tr>
                          <td height="25" colspan="6" align="right" bgcolor="#FFFFFF"><input name="clear" type="checkbox" id="clear" value="1" onClick="javascript:if(this.form.clear.checked) this.form.action.value='clear';else this.form.action.value='del';"/>
                          清空全部
                            <input name="action" type="hidden" id="action" value="del" />
                            <input type="submit" name="Submit" value=" 删 除 " onClick="if(!confirm('真的要删除吗？')) return false;" />
                            &nbsp;</td>
                        </tr>
                        <tr>
                          <td height="25" colspan="6" align="right" bgcolor="#FFFFFF"><?php
Call Page.GetPageList()
?>
                            &nbsp;</td>
                        </tr>
						</form>
                      </table>
                                       
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
<script language="javascript">
function chkall(obj,frm)
{
	for(var i=0;i<frm.ID.length;i++)
		frm.ID[i].checked = obj.checked;
}
</script>
</body>
</html>
<?php

Call CloseConn()
Set Rs = Nothing
Set Admin = Nothing

?>

