<!DOCTYPE html>
<html>
<head>
	<?php require_once('../include/admin_head.inc.php'); ?>
	<title><?php echo LA_RESET_PSW." - {$OJ_NAME}";?></title>
</head>	
<body>
	<?php require('./pages/components/offcanvas.php');?>
	<div class="container" id="mainContent">
		<div class="page-header">
			<h1><?php echo LA_RESET_PSW;?> <small><?php echo LA_USER_MGR;?></small></h1>
		</div>
		<p class="lead">
			您可以通过这里重置用户的密码，但请注意：
			<ul>
				<li>如无需要，请勿随意重置他人密码</li>
				<li>重置密码功能不能够重置Administrator的的密码</li>
			</ul><br/>
		</p>
		<div class="well">
			<form class="form-inline" action="../api/reset_password.php" method="POST">
				<div class="form-group">
					<label>输入用户ID（非昵称）: </label><br/>
					<input type="text" class="form-control" name="user_id" placeholder="User ID Here">
				</div><br/>
				<div class="form-group">
					<label>输入重置的密码: </label><br/>
					<input type="password" class="form-control" name="new_password" placeholder="Password Here">
				</div><br/><br/>
				<?php require_once('../include/pageauth_post.php'); ?>
				<button type="submit" class="btn btn-default"><?php echo L_SUBMIT;?></button>
			</form><br/>
		</div>
	</div>
</body>
</html>