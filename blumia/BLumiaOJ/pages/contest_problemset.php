<!DOCTYPE html>
<html>
<head>
	<?php require_once('./include/common_head.inc.php'); ?>
	<title><?php echo L_CONTEST." {$cid} ".L_PROB_SET." - {$OJ_NAME}";?></title>
</head>	
<body>
	<?php require("./pages/components/navbar.php");?>
	<div class="container">
		<?php require("./pages/components/contest_heading.php");?>
		<?php if ($contestAuthResult) { ?>
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="btn-group" role="group" aria-label="...">
				<?php 
				for($i=0;$i<$problemCount;$i++) { 
					echo "<a href='contest_problem.php?cid={$cid}&pid={$i}'><button type='button' class='btn btn-default'>{$ALPHABET_N_NUM[$i]}</button></a>";
				} 
				?>
				</div>
			</div>
		</div><!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-hover" id="tableID">
					<thead>
						<tr>
							<th width="5%">AC</th>
							<th width="5%">ID</th>
							<th width="40%"><?php echo L_TITLE;?></th>
							<th width="20%"><?php echo L_DIFFICUTY;?></th>
							<th width="16%"><?php echo L_SOURCE;?></th>
							<th width="14%">AC / <?php echo L_SUBMIT;?></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0;$i<$problemCount;$i++) { //problem list ------------ ?>
					<?php 
							$problemList[$i]['submit'] = $problemList[$i]['submit'] == NULL ? 0 : $problemList[$i]['submit'];
							$problemList[$i]['accepted'] = $problemList[$i]['accepted'] == NULL ? 0 : $problemList[$i]['accepted'];
							if ($problemList[$i]['submit'] == 0) {
								$pctText = "Unavalible";
								$procBarNum = 0;
								$pctNum = 0;
							} else {
								$pctNum = ($problemList[$i]['accepted']/$problemList[$i]['submit'])*100;
								$procBarNum = (1-($problemList[$i]['accepted']/$problemList[$i]['submit']))*100;
								$pctText = sprintf("%.2f%%",$pctNum);
							}
							$solved = (isset($acceptedPair[$problemList[$i]['pid']])) ? true :false;
						?>
						<tr <?php if ($solved) echo "class='success'";?>>
							<td><?php if($solved) echo $acceptedPair[$problemList[$i]['pid']];?></td>
							<td><?php echo $ALPHABET_N_NUM[$i];?></td>
							<td>
								<a href="contest_problem.php?cid=<?php echo $cid;?>&pid=<?php echo $i;?>"><?php echo $problemList[$i]['title'];?></a>
							</td>
							<td><div class="progress"><div class="progress-bar" style="width:<?php echo $procBarNum;?>%;"></div></div></td>
							<td><?php echo $problemList[$i]['source'];?></td>
							<td>(<?php echo $problemList[$i]['accepted']." / ".$problemList[$i]['submit'];?>) <?php echo $pctText;?></td>
						</tr>
					<?php } //problem list end --------------------------------------- ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div><!--main wrapper end-->
	<?php require("./pages/components/footer.php");?>
</body>
</html>