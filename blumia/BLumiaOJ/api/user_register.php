<?php
	session_start();
	$ON_ADMIN_PAGE="Yap";
	require_once("../include/setting_oj.inc.php");
	require_once("../include/login_functions.php");
	require_once("../include/user_check_functions.php");
	
	if(!isset($_SESSION['SessionAuth']) || !isset($_POST['pageauth'])) {
		exit("Auth failed");
	}
	if($_SESSION['SessionAuth'] != $_POST['pageauth']) {
		exit("Auth failed");
	}
	
	if($OJ_LARGE_CONTEST_MODE == true && $OJ_LOGIN_FILTER != false) {
		exit("Register disabled!");
	}
    
	$user_id=trim($_POST['username']);
	$user_nick=$_POST['nickname'];
	$user_pwd=$_POST['password'];
	$user_pwdII=$_POST['password_again'];
	$user_school=$_POST['school'];
	$user_email=$_POST['email'];
	$user_ip=$_SERVER['REMOTE_ADDR'];
	if (get_magic_quotes_gpc ()) {
		$user_id= stripslashes ($user_id);
		$user_nick= stripslashes ($user_nick);
		$user_pwd= stripslashes ($user_pwd);
	}
	
	$password=pwGen($user_pwd);
	
	if (isUseridExist($user_id,$pdo)) {
		exit("User Exist!");
	}

	$sql=$pdo->prepare("INSERT INTO `users` 
	(`user_id`,`email`,`ip`,`accesstime`,`password`,`reg_time`,`nick`,`school`)
	VALUES(?,?,?,NOW(),?,NOW(),?,?)");
	$sql->execute(array($user_id,$user_email,$user_ip,$password,$user_nick,$user_school));
	
	$login=check_login($user_id,$user_pwd,$pdo);
	
	if ($login) {
		$_SESSION['user_id']=$login;
		//权限部分未添加
		//$_SESSION['administrator']=true;
		//权限部分未添加
		echo "success";
		echo "<script language='javascript'>\n";
		echo "window.location.replace('../userinfo.php');\n";
		echo "</script>";
	} else {
		echo "failed";
		//echo "<script language='javascript'>\n";
		//echo "alert('UserName or Password Wrong!');\n";
		//echo "history.go(-1);\n";
		//echo "</script>";
	}
	
?>
