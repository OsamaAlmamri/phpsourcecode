<?php if(isset($_GET['id'])){
		$id = $_GET['id'];
	}

	if(!empty($_POST['title'])){
		$title = $_POST['title'];
		$desc1 = $_POST['desc1'];
		$url = $_POST['url'];
		$pic = $_POST['pic'];

		//更新数据到USER详情表当中
		
		$sql = "update bbs_fri title='$title',desc1='desc1',url='$url',pic='$Pic'";
		echo $sql;
		exit;
		$row = mysql_func($sql);
		if(!$row===0){
			echo "<script>alert('抱歉！写入数据库失败，请稍后再试！')</script>";
			echo "<script>window.location.href='../index.phpm=fri&a=list'<script/>";
			exit;
		}

		//执行过程中没有出现以为，将跳转到LIST列表当中
		echo "<script>window.location.href='../index.phpm=fri&a=list'</script>";
		exit;
	}
	
	//POST不存在，将查询表中数据
	$sql = "select * from bbs_fri where  id='$id'";
	//echo $sql;
	//exit;
	$row = mysql_func($sql);
	$fri = $row[0];
	/*echo "<pre>";
	var_dump($fri);
	echo "</pre>";*/
?>