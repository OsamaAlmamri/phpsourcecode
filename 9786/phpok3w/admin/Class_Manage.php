
<?php
Const dbdns="../"
Dim ChannelID,Htmldns
ChannelID = Request.QueryString("ChannelID")
Tmp = Split(Request.ServerVariables("PATH_INFO"),"/")
Htmldns = ""
For i=0 To Ubound(Tmp) - 2
	Htmldns = Htmldns & Tmp(i) & "/"
Next

?>
<?php require_once("chk.php");  ?>
<?php require_once "../AppCode/Class/Ok3w_Class.php" ?>
<?php require_once("../AppCode/fun/function.php");  ?>
<?php require_once("../AppCode/fun/inc_file.php");  ?>
<?php

Select Case ChannelID
	Case 1
		Call CheckAdminFlag(3)
	Case 2
		Call CheckAdminFlag(2)
	Case 3
		Call CheckAdminFlag(6)
	Case Else
		Response.End()
End Select
Set myClass = New Ok3w_Class
ParentID = Request.QueryString("ParentID")
SortPath = Request.QueryString("SortPath")
If SortPath="" Then SortPath = "0,"

Select Case Request.Form("action")
	Case "add"
		Call myClass.Add()
		Call myClass.Html_ClassJs()
		Call ActionOk("Class_Manage.php?ChannelID=" & ChannelID & "&ParentID=" & ParentID & "&SortPath=" & SortPath)
	Case "edit"
		Call myClass.Edit()
		Call myClass.Html_ClassJs()
		Call ActionOk("Class_Manage.php?ChannelID=" & ChannelID & "&ParentID=" & ParentID & "&SortPath=" & SortPath)
	Case "del"
		Call myClass.Del()
		Call myClass.Html_ClassJs()
		Call ActionOk("Class_Manage.php?ChannelID=" & ChannelID & "&ParentID=" & ParentID & "&SortPath=" & SortPath)
End Select

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��̨����ϵͳ</title>
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
                background="images/tab_active_bg.gif">�������</td>
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
                valign="top" bgcolor="#fffcf7">��ǰλ�ã�<a href="?ChannelID=<?php
=ChannelID
?>&ParentID=0"><?php
=GetChannelName(ChannelID)
?></a>
                            <?php

	Tmp = Split(SortPath,",")
	For i=1 To Ubound(Tmp)-1
		Sql="select * from Ok3w_Class where ID=" & Tmp(i)
		Set oRs = Conn.Execute(Sql)
	
?>
                          &gt;&gt; <a href="?ChannelID=<?php
=ChannelID
?>&ParentID=<?php
=oRs("ID")
?>&amp;SortPath=<?php
=oRs("SortPath")
?>"><?php
=oRs("SortName")
?></a>
       <?php

		oRs.Close
		Set oRs = Nothing
	Next	
	
?>
	   <br /><br />
       <table border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
         <tr>
           <td bgcolor="#EBEBEB">��������</td>
           <td bgcolor="#EBEBEB">������ʾ</td>
           <td bgcolor="#EBEBEB">ͼƬ�б�</td>
           <td bgcolor="#EBEBEB">ÿҳ����</td>
           <td bgcolor="#EBEBEB">�ⲿ����</td>
           <td bgcolor="#EBEBEB">��ʾ˳��</td>
           <td bgcolor="#EBEBEB">����</td>
           </tr>
		   <form action="" method="post" onSubmit="return chkform(this);">
         <tr>
           <td bgcolor="#FFFFFF"><input name="SortName" type="text" id="SortName" size="15" maxlength="50" /></td>
           <td bgcolor="#FFFFFF"><input name="IsNav" type="checkbox" id="IsNav" value="1" checked></td>
           <td bgcolor="#FFFFFF"><input name="IsPic" type="checkbox" id="IsPic" value="1" /></td>
           <td bgcolor="#FFFFFF"><input name="PageSize" type="text" id="PageSize" value="20" size="4" maxlength="2"></td>
           <td bgcolor="#FFFFFF"><input name="gotoURL" type="text" id="gotoURL" size="25" maxlength="50" /></td>
           <td bgcolor="#FFFFFF"><input name="OrderID" type="text" id="OrderID" value="<?php
=myClass.GetMaxClassOrder(ParentID)+1
?>" size="4" maxlength="4" /></td>
           <td bgcolor="#FFFFFF"><input name="Submit3" type="submit" class="bntStyle" value="�� ��" />
             <input name="ChannelID" type="hidden" id="ChannelID" value="<?php
=ChannelID
?>" />
             <input name="SortPath" type="hidden" id="SortPath" value="<?php
=SortPath
?>" />
             <input name="ParentID" type="hidden" id="ParentID" value="<?php
=ParentID
?>" />
             <input name="action" type="hidden" id="action" value="add" /></td>
           </tr>
		   </form>
       </table>

       <br>
       <table border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">
                      <tr bgcolor="#EBEBEB">
                        <td>��������</td>
                        <td>����ID</td>
                        <td>��������</td>
                        <td>����</td>
                        <td>ͼƬ</td>
                        <td>ÿҳ����</td>
                        <td>�ⲿ����</td>
                        <td>����</td>
                        <td>С����</td>
                        <td>����</td>
                      </tr>
                      <?php

Sql="select * from Ok3w_Class where ChannelID=" & ChannelID & " and ParentID=" & ParentID & " order by OrderID"
Rs.Open Sql,Conn,1,1
If Not(Rs.Eof And Rs.Bof) Then
Do While Not Rs.Eof

?>
                      <form action="" method="post" onSubmit="return chkform(this);">
                        <tr bgcolor="#FFFFFF">
                          <td><?php
=GetClassName(Rs("ParentID"))
?></td>
                          <td><input name="ID" type="text" id="ID" value="<?php
=Rs("ID")
?>" size="4" disabled="disabled" /></td>
                          <td><input name="SortName" type="text" id="SortName" value="<?php
= Rs("SortName")
?>" size="15" /></td>
                          <td><input name="IsNav" type="checkbox" id="IsNav" value="1" <?php
If Rs("IsNav")=1 Then
?>checked="checked"<?php
End If
?> /></td>
                          <td><input name="IsPic" type="checkbox" id="IsPic" value="1" <?php
If Rs("IsPic")=1 Then
?>checked="checked"<?php
End If
?> /></td>
                          <td><input name="PageSize" type="text" id="PageSize" value="<?php
=Rs("PageSize")
?>" size="4" maxlength="2"></td>
                          <td><input name="gotoURL" type="text" id="gotoURL" value="<?php
=Rs("gotoURL")
?>" size="25" maxlength="50" /></td>
                          <td><input name="OrderID" type="text" id="OrderID" value="<?php
=Rs("OrderID")
?>" size="4" maxlength="4" /></td>
                          <td><?php
If ChannelID=2 Or Rs("gotoURL")<>"" Then
?><?php
Else
?><a href="?ChannelID=<?php
=ChannelID
?>&ParentID=<?php
=Rs("ID")
?>&SortPath=<?php
=Rs("SortPath")
?>">&gt;&gt;&gt;</a><?php
End If
?></td>
                          <td><input name="Submit" type="submit" class="bntStyle" value="�� ��" />
                              <input name="Submit2" type="submit" class="bntStyle" value="ɾ ��" onClick="if(confirm('���Ҫɾ����')){this.form.action.value='del';}else{return false;}" <?php
If myClass.IsHaveNextClass(Rs("ID")) Then
?>disabled="disabled"<?php
End If
?>/>
                              <input name="SortPath" type="hidden" id="SortPath" value="<?php
=Rs("SortPath")
?>" />
                              <input name="ParentID" type="hidden" id="ParentID" value="<?php
=Rs("ParentID")
?>" />
							  <input name="ChannelID" type="hidden" id="ChannelID" value="<?php
=Rs("ChannelID")
?>" />
							  <input name="action" type="hidden" id="action" value="edit" />
                              <input name="ID" type="hidden" id="ID" value="<?php
=Rs("ID")
?>" /></td>
                        </tr>
                      </form>
                      <?php

	Rs.MoveNext
Loop
Else

?>
                      <tr bgcolor="#FFFFFF">
                        <td colspan="10" align="center">������Ŀ���������</td>
                      </tr>
                      <?php

End If
Rs.Close

?>
                    </table><br />
					
<div style="border:1px solid #CCCCCC; padding:5px;">
	   <strong>ʹ��ǰ�ض���</strong><br>
	   һ�����ѡ�С�ͼƬ�б�����÷�����б�ҳ��ͼƬ��ͼ����ʽ��ʾ����������ͨ������ʾ��<br />
	   ������������ⲿ���ӣ����ⲿ���ӡ���һ��Ҫ���գ�<br />
	   ��������㲻����ĳһ�����ڵ�������ʾ����ȡ����������ѡ�񼴿ɣ����������������أ������������ڸ�������أ���<br>
	   �ġ�ֻ����ɾ��С���࣬����ɾ�������
	   ��      </div>					
					
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
function chkform(frm)
{
	if(frm.SortName.value=="")
	{
		alert("�������������")
		frm.SortName.focus();
		return false;
	}
}

<?php
If ChannelID=2 Then
?>
parent.left.location.reload();
<?php
End If
?>
</script>
</body>
</html>
<?php

Set myClass = Nothing
Set Rs = Nothing
Call CloseConn()

?>

