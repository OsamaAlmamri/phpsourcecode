

<?php require_once("chk.php");  ?>
<?php require_once("../AppCode/Pager.php");  ?>
<?php require_once("../AppCode/fun/function.php");  ?>
<?php require_once("../AppCode/Class/Ok3w_User.php");  ?>
<?php require_once("../AppCode/fun/inc_file.php");  ?>
<?php

Call CheckAdminFlag(5)

Set User = New Ok3w_User
Dim Cmd,CmdTmp
Cmd = Trim(Request.Form("cmd"))
If Cmd <> "" Then
	CmdTmp = Split(Cmd,"|")
	Lid = Trim(Request.Form("ID"))
	Select Case CmdTmp(0)
		Case "pass"
			Call User.Pass(CmdTmp(1),Lid)
			Set User = Nothing
			Call SaveAdminLog("��ͨ/�رգ�Action=" & CmdTmp(1) & "��ID=" & Lid)
			Call ActionOk("User_List.php")
		Case "del"
			Call User.Del(Lid)
			Set User = Nothing
			Call SaveAdminLog("ɾ����վ��Ա��ID=" & Lid)
			Call ActionOk("User_List.php")
	End Select
End If

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��̨����ϵͳ</title>
<link rel="stylesheet" type="text/css" href="images/Style.css">
<script language="javascript">
function ChkAll()
{
	var obj = document.form2.ID;
	for(var i=0;i<obj.length;i++)
		obj[i].checked = !obj[i].checked;
}
</script>
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
                background="images/tab_active_bg.gif" class="tab"><strong class="mtitle">��Ա����</strong></td>
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
                valign="top" bgcolor="#fffcf7"><?php

stype = Trim(Request.QueryString("stype"))
keyword = Trim(Request.QueryString("keyword"))
Sql = "select * from Ok3w_User where 1=1"
If keyword<>"" Then Sql = Sql & " and " & stype & " like '%" & keyword & "%'"
Sql = Sql & " order by Id desc"
Set Page = New TurnPage
Call Page.GetRs(Conn,Rs,Sql,20)

?>
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
                        <form id="form1" name="form1" method="get" action="">
                          <tr>
                            <td height="30" colspan="10" align="left" bgcolor="#dddddd">&nbsp;
                              <select name="stype" id="stype">
                                <option value="User_Name">�û���</option>
                                <option value="Name">��ʵ����</option>
                                <option value="Mail">����</option>
                              </select>
                                <input name="keyword" type="text" id="keyword" />
                                <input type="submit" name="Submit2" value="����" /></td>
                          </tr>
                        </form>
                        <tr>
                          <td align="center" bgcolor="#dddddd">���</td>
                          <td align="center" bgcolor="#dddddd">�û���</td>
                          <td align="center" bgcolor="#dddddd">����</td>
                          <td height="25" align="center" bgcolor="#dddddd">����</td>
                          <td align="center" bgcolor="#dddddd">����</td>
                          <td align="center" bgcolor="#dddddd">QQ</td>
                          <td align="center" bgcolor="#dddddd">����½</td>
                          <td align="center" bgcolor="#dddddd">״̬</td>
                          <td align="center" bgcolor="#dddddd">����</td>
                          <td height="25" align="center" bgcolor="#dddddd"><input type="checkbox" name="checkbox" value="checkbox" onClick="javascript:ChkAll();" /></td>
                        </tr>
                        <form id="form2" name="form2" method="post" action="">
                          <?php

If Not(Rs.Eof And Rs.Bof) Then	
n = Request.QueryString("PageNo")
If n = "" Then n = 1
n = (Cint(n)-1) * 20							  
Do While Not Rs.Eof And Not Page.Eof
n = n + 1
	
?>
                          <tr>
                            <td align="center" bgcolor="#FFFFFF"><?php
=n
?></td>
                            <td align="center" bgcolor="#FFFFFF"><?php
= Rs("User_Name")
?></td>
                            <td align="center" bgcolor="#FFFFFF"><?php
= Rs("Jifen")
?></td>
                            <td height="25" align="center" bgcolor="#FFFFFF"><?php
= Rs("Name")
?></td>
                            <td align="center" bgcolor="#FFFFFF"><?php
= Rs("Mail")
?></td>
                            <td align="center" bgcolor="#FFFFFF"><?php
= Rs("QQ")
?></td>
                            <td align="center" bgcolor="#FFFFFF"><?php
= Rs("LastLoginTime")
?></td>
                            <td align="center" bgcolor="#FFFFFF"><?php
If Rs("IsLock")=0 Then
?>��<?php
Else
?>��<?php
End If
?></td>
                            <td align="center" bgcolor="#FFFFFF"><a href="User_Edit.php?Id=<?php
=Rs("Id")
?>&action=edit">�޸�</a></td>
                            <td height="25" align="center" bgcolor="#FFFFFF"><input name="ID" type="checkbox" id="ID" value="<?php
=Rs("Id")
?>" /></td>
                          </tr>
                          <?php

		Rs.MoveNext
		Page.MoveNext
	Loop
	Rs.Close
	
?>
                          <tr>
                            <td height="25" colspan="10" align="left" bgcolor="#FFFFFF">&nbsp;
							<input type="radio" name="cmd" value="pass|1" />
							�ر�
                              <input type="radio" name="cmd" value="pass|0" />
                              ��ͨ
                              <input name="cmd" type="radio" value="del|1" checked="checked" />
                              ɾ�� 
                              <input type="submit" name="Submit" value="�ύ" onClick="javascript:if(!confirm('���Ҫִ�д˲�����')) return false;" />							  </td>
                          </tr>
                          <tr>
                            <td height="25" colspan="10" align="right" bgcolor="#FFFFFF"><?php
Call Page.GetPageList()
?></td>
                          </tr>
<?php
Else
?>						  
						   <tr>
                            <td height="25" colspan="10" align="center" bgcolor="#FFFFFF">û�л�Ա</td>
                          </tr>
<?php
End If
?>						  
                        </form>
                      </table></td>
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

Set User = Nothing
Set Rs = Nothing
Call CloseConn()
Set Admin = Nothing

?>

