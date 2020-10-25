<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once('./include/common_head.inc.php'); ?>
		<title>Problem Set</title>
	</head>	
	
<?php
	//Vars
	require_once('./include/setting_oj.inc.php');
	
	//Prepare
	$p=isset($_GET['p']) ? $_GET['p'] : 0;
	if($p<0){$p=0;}
	$front=intval($p*$PAGE_ITEMS);
	
	$onOJ=isset($_GET['oj']) ? $_GET['oj'] : "HDU";
	
	$totalCount = 5;
	
	//Page Includes
	require("./pages/problemset_vj.php");
?>
	
</html>