<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<?php require_once('Connections/localhost.php'); ?>
<?php
 
$colname_catalog = "-1";
if (isset($_GET['catalog_id'])) {
  $colname_catalog = (get_magic_quotes_gpc()) ? $_GET['catalog_id'] : addslashes($_GET['catalog_id']);
}
mysql_select_db($database_localhost, $localhost);
$query_catalog = sprintf("SELECT * FROM `catalog` WHERE id = %s", $colname_catalog);
$catalog = mysql_query($query_catalog, $localhost) or die(mysql_error());
$row_catalog = mysql_fetch_assoc($catalog);
$totalRows_catalog = mysql_num_rows($catalog);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_catalog['name']; ?></title>
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
.sort_box {font-size: 12px}
.STYLE3 {
	font-size: 14px;
	font-weight: bold;
}
.STYLE4 {color: #666}
.to_all_sort {
	font-size: 14px;
	color: #FFFFFF;
	font-weight: bold;
	margin-left:10px;
}
-->
a{
	text-decoration:none;
	color:#666;
	font-size:12px;
}

table{
	border-collapse:collapse;
}

.asc,.desc{
	display:none;
}

.sort_box_activated{
	color:white;
	background-color:#FF0000;
}
.sort_box_activated a{
	color:white;
	text-decoration:none;
	border:1px solid #FF0000;
}
</style>
</head>
<body style="margin:0px;" >
<?php include_once('widget/top_full_nav.php'); ?>
<?php include_once('widget/logo_search_cart.php'); ?>
<?php include_once('widget/full_ori_nav_1210.php'); ?>
 
<table style="margin-top:10px;margin-bottom:10px;" width="1210" height="24" border="0" align="center" cellspacing="0">
  <tr>
    <td height="24"><strong  style="margin-left:10px;"><?php echo $row_catalog['name']; ?></strong> &gt;产品列表</td>
  </tr>
</table>
<table style="border-top:1px solid #DDD;border-bottom:1px solid #DDD;" width="1210" height="35" align="center" cellpadding="0" cellspacing="0"  bgcolor="#F1F1F1">
  <tr>
    <td height="24"><strong  style="margin-left:10px;"><?php echo $row_catalog['name']; ?></strong></td>
  </tr>
</table>
<br />
<table width="1210" border="0" align="center">
  <tr valign="top">
    <td width="210" rowspan="2" bordercolor="#ddd">
	<?php include_once('widget/sub_cata.php');?>
	<?php   include_once('widget/ad_product_verticle.php');?></td>
    <td height="38" align="left"><table width="990" height="38" border="0" bgcolor="#F1F1F1">
      <tr valign="left">
        <td><?php include('widget/product/sort.php'); ?></td>
        <td>&nbsp;</td>
        </tr>
    </table></td>
  </tr>
   
  <tr>
    <td valign="top"><?php include('widget/product/product_list.php'); ?></td>
  </tr>
</table>
 <?php include('widget/footer.php'); ?>
<script language="JavaScript" type="text/javascript" src="../../js/jquery.validate.min.js"></script>
</body>
</html>
<?php
mysql_free_result($catalog);
?>
