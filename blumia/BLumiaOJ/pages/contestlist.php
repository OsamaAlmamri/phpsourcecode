<!DOCTYPE html>
<html>
	<head>
		<?php require_once('./include/common_head.inc.php'); ?>
		<?php require_once('./include/contest_functions.inc.php'); ?>
		<title><?php echo L_CONTEST." - {$OJ_NAME}";?></title>
	</head>	
	<body>
		<?php require("./pages/components/navbar.php");?>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-xs-4">
					<div class="btn-group" role="group" aria-label="...">
						<?php
							$prev = $p-1; $next = $p+1;
							if ($p != 1) echo "<a href='contestlist.php?p={$prev}' type='button' class='btn btn-default'>&lt;</a>"; 
							echo "<a href='contestlist.php?p={$p}' type='button' class='btn btn-default'>{$p}</a>"; 
							if ($p != $pageCnt) echo "<a href='contestlist.php?p={$next}' type='button' class='btn btn-default'>&gt;</a>"; 
						?>
					</div>
				</div>
				<div class="col-md-4 col-xs-4 text-right">
					<!--<button type="button" class="btn btn-default">Create Contest</button>-->
				</div><!-- /.col-lg-3 -->
				<div class="col-md-4 col-xs-4 text-right">
					<div class="btn-group" role="group">
					<a href="?private=2" type="button" class="btn btn-default"><?php echo L_ALL;?></a>
					<a href="?private=0" type="button" class="btn btn-default"><?php echo L_Public;?></a>
					<a href="?private=1" type="button" class="btn btn-default"><?php echo L_Private;?></a>
					</div>
				</div><!-- /.col-lg-3 -->
			</div><!-- /.row -->
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped table-hover" id="tableID">
						<thead>
							<tr>
								<th width="15%"><?php echo L_CONTEST_ID;?></th>
								<th width="45%"><?php echo L_TITLE;?></th>
								<th width="15%"><?php echo L_STATUS;?></th>
								<th width="10%"><?php echo L_Privilege;?></th>
								<th width="15%"><?php echo L_Creator;?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($contestList as $row) { // list ------------ ?>
							<tr>
								<td><?php echo $row['contest_id'];?></td>
								<td><a href="contest.php?cid=<?php echo $row['contest_id'];?>"><?php echo $row['title'];?></a></td>
								<td><?php echo $row['content_status'];?></td>
								<td><?php echo $row['private'];?></td>
								<td><?php echo $row['user_id'];?></td>
							</tr>
						<?php } //User list end --------------------------------------- ?>
						</tbody>
					</table>
				</div>
			</div>
		</div><!--main wrapper end-->
		<?php require("./pages/components/footer.php");?>
	</body>
</html>
