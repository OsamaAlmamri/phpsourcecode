<?php
require("./database.php");
if(empty($_SESSION['momocms_admin'])){
	header("Location:./index.php");	
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>MoMoCMS -- 更好用的企业建站系统</title>
<meta name="description" content="MoMoCMS -- 更好用的企业建站系统" />
<meta name="keywords" content="MoMoCMS" />
<!-- Favicons --> 
<link rel="icon" href="../resource/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="../resource/favicon.ico" type="image/x-icon">
<link rel="bookmark" href="../resource/favicon.ico" type="image/x-icon">
<!-- Main Stylesheet --> 
<link rel="stylesheet" href="css/style.css" type="text/css" />
<!-- jQuery with plugins -->
<script type="text/javascript" SRC="js/jquery-1.4.2.min.js"></script>
<!-- Could be loaded remotely from Google CDN : <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->
<script type="text/javascript" SRC="js/jquery.ui.core.min.js"></script>
<script type="text/javascript" SRC="js/jquery.ui.widget.min.js"></script>
<script type="text/javascript" SRC="js/jquery.ui.tabs.min.js"></script>
<!-- jQuery tooltips -->
<script type="text/javascript" SRC="js/jquery.tipTip.min.js"></script>
<!-- Superfish navigation -->
<script type="text/javascript" SRC="js/jquery.superfish.min.js"></script>
<script type="text/javascript" SRC="js/jquery.supersubs.min.js"></script>
<script type="text/javascript" src="js/html5.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	/* setup navigation, content boxes, etc... */
	Administry.setup();
	Administry.expandableRows();
});
</script>
</head>
<body>
	<!-- Header -->
	<header id="top">
		<div class="wrapper">
			<!-- Title/Logo - can use text instead of image -->
			<div id="title"><img SRC="../resource/logo.gif" /><!--<span>Administry</span> demo--></div>
			<!-- Top navigation -->
			<div id="topnav">
				管理员 <b><?php echo $_SESSION['momocms_admin']; ?></b>
				<span>|</span> <a href="./logout.php">注销</a><br />
			</div>
			<!-- End of Top navigation -->
			<!-- Main navigation -->
			<?php
			require("./public_menu.php");
			echo out_menu(6,$arr);
			?>
			<!-- End of Main navigation -->
		</div>
	</header>
	<!-- End of Header -->
	<!-- Page title -->
	<div id="pagetitle">
		<div class="wrapper">
			<h1>侧边栏设置</h1>
		</div>
	</div>
	<!-- End of Page title -->
	
	<!-- Page content -->
	<div id="page">
		<!-- Wrapper -->
		<div class="wrapper">
				<!-- Left column/section -->
				<section class="column width6 first">
				<div id="successMsg" style="display:none" class="box box-success">操作成功</div>
				<form name="form" enctype="multipart/form-data" method="post" action="./create_mix_sidebar.php" target="hiddenframe">
				<iframe id="hiddenframe" name="hiddenframe" style="width:0;height:0;"></iframe>
				<div style="float:right;margin-top:10px;">
					<label>侧栏模块</label>
					<select name="bars" style="width:150px;">
						<option value="product_sidebar.php">产品列表</option>
						<option value="contact_sidebar.php">联系我们</option>
					</select>&nbsp;
					<label>隶属页面</label>
					<select name="bars_page" style="width:150px;">
						<?php 
						$sql="select * from ".DB_PREFIX."pages where barsid=1";
						$query=$db->query($sql);
						$num=$query->rowCount();
						if($num>0){
							while($arr=$query->fetch()){	?>
								<option value="<?php echo $arr['id']; ?>"><?php echo $arr['title']; ?></option>
					<?php
							}
						}
						?>
					</select>
					<a href="javascript:;" class="btn" style="position:relative;top:2px;top:0px\9;" onclick="if(document.forms['form'].name.value!=''){document.forms['form'].submit()}"><span class="icon icon-add">&nbsp;</span>增加侧边栏</a>
				</div>
				</form>
				<label class="required" style="color:#ff0000;margin-top:2px;">注意：此处侧栏自定义模块功能需要手动编写代码来结合使用。</label>
				<table class="stylized full" style="">
						<thead>
							<tr>
								<th>侧边栏模块名称</th>
								<th>排列序号</th>
								<th>隶属</th>
								<th class="ta-right">操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
						   $sql="select * from ".DB_PREFIX."mix_sidebar  order by sort desc";
						   $query=$db->query($sql);
						   $num=$query->rowCount();
							if($num>0){
								while($arr=$query->fetch()){
							?>
							<tr>
								<td class="title">
									<div>
										<a href="#"><b><?php echo $arr['name']; ?></b></a>
										<div class="listingDetails">
											<div class="pad">
												<b>更新排序</b>
												<form name="form_mix_sidebar<?php echo $arr['id']; ?>" action="./update_mix_sidebar.php" method="post" target="hiddenframe">
													<input type="text" value="<?php echo $arr['sort']; ?>" name="sort" placeholder="排列序号">
													<input type="hidden" name="id" value="<?php echo $arr['id']; ?>">
													<a href="javascript:;" class="btn btn-green" style="position:relative;top:2px;top:0px\9;" onclick="document.forms['form_mix_sidebar<?php echo $arr['id']; ?>'].submit()">更新</a>
												</form>
											</div>
										</div>
									</div>
								</td>
								<td><?php echo $arr['sort']; ?></td>
								<td><?php 
										$sql_sub="select * from ".DB_PREFIX."pages where id=".$arr['pid'];
										$query_sub=$db->query($sql_sub);
										$arr_sub = $query_sub->fetch();
										echo $arr_sub['title']; ?></td>
								<td class="ta-right">
									<a href="./delete_mix_sidebar.php?id=<?php echo $arr['id']; ?>">删除</a></td>
							</tr>
						<?php	}}	?>
						</tbody>
					</table>
				</section>
				<!-- End of Left column/section -->
<?php require("./public_side_foot.php"); ?>