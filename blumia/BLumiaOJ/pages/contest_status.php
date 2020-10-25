<!DOCTYPE html>
<html>
	<head>
		<?php require_once('./include/common_head.inc.php'); ?>
		<title><?php echo L_CONTEST." {$cid} ".L_STATUS." - {$OJ_NAME}";?></title>
		<style>
tr > td.result {
  text-align: center;
}
		</style>
	</head>	
	<body>
		<?php require("./pages/components/navbar.php");?>
		<div class="container">
			<?php require("./pages/components/contest_heading.php");?>
			<div class="row">
				<div class="col-md-3">
					<div class="btn-group" role="group" aria-label="...">
						<button type="button" class="btn btn-default">《</button>
						<button type="button" class="btn btn-default">1</button>
						<button type="button" class="btn btn-default">》</button>
					</div>
				</div>
				<div class="col-md-9">
					<form method="get" class="form-inline text-right">
						<div class="form-group">
							<input name="pid" value="<?php echo $problem_id;?>" type="text" class="form-control" placeholder="<?php echo L_PROB_ID;?>">
							<input name="uid" value="<?php echo $user_id;?>" type="text" class="form-control" placeholder="<?php echo L_UID;?>">
							<select name="language" class="form-control">
								<option value="-1">==<?php echo L_LANG;?>==</option>
								<?php
								$lang=(~((int)$langmask))&((1<<($lang_count))-1);
								for($i=0;$i<$lang_count;$i++){
									if($lang&(1<<$i))
										echo "<option value='{$i}'>{$LANGUAGE_NAME[$i]}</option>";
								}
								?>
							</select>
							<select name="judgeresult" class="form-control">
								<option value="-1">==<?php echo L_RESULT;?>==</option>
								<?php 
								$i=0;
								foreach($JUDGE_RESULT as $row) {
									echo "<option value='{$i}'>{$row}</option>";
									$i++;
								}
								?>
							</select>
						</div>
						<button type="submit" class="btn btn-default"><?php echo L_FIND;?></button>
					</form>
				</div><!-- /.col-md-6 -->
			</div><!-- /.row -->
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped table-hover" id="tableID">
						<thead>
							<tr>
								<th width="10%"><?php echo L_RUN_ID;?></th>
								<th width="14%"><?php echo L_UID;?></th>
								<th width="10%"><?php echo L_PROB_ID;?></th>
								<th width="20%" style="text-align: center;"><?php echo L_RESULT;?></th>
								<th width="8%"><?php echo L_MEMORY;?></th>
								<th width="8%"><?php echo L_TIME_COST;?></th>
								<th width="8%"><?php echo L_COMPILER;?></th>
								<th width="8%"><?php echo L_LENGHT;?></th>
								<th width="14%"><?php echo L_SUBMIT_TIME;?></th>
							</tr>
						</thead>
						<tbody id="oj-statue-list">
						<?php 
						foreach($statusResult as $row) { 
							$passRate = "";
							$problemNum = intval($problemIDPair[$row['problem_id']]);
							if (isset($row['pass_rate'])) {
								if($row['result']!=4 && $row['pass_rate']>0 && $row['pass_rate']<0.98) {
									$rate = 100 - $row['pass_rate'] * 100;
									$passRate = "<span class='label label-warning'>{$rate}%</span>";
								}	
							}
							$resUrl = "<span class='label label-{$JUDGE_ROW_CSS_CLASS[$row['result']]}'>{$JUDGE_RESULT[$row['result']]}</span>{$passRate}";
							if (havePrivilege("SOURCE_VIEWER") || (isset($_SESSION['user_id']) && $_SESSION['user_id']==$row['user_id']) ) {
								$codeUrl = "<a href='./source_view.php?id={$row['solution_id']}'>{$LANGUAGE_NAME[$row['language']]}</a> | <a href='./problemsubmit.php?sid={$row['solution_id']}&pid={$problemNum}&cid={$cid}'>Edit</a>"; 
								if($row['result'] == 6 && $SOLUTION_WA_INFO) {
									$resUrl = "<i class='fa fa-question-circle'></i><a href='./error_view.php?id={$row['solution_id']}&type=6'>{$resUrl}</a><i class='fa fa-question-circle'></i>";
								} 
								if($row['result'] == 11) {
									$resUrl = "<i class='fa fa-question-circle'></i><a href='./error_view.php?id={$row['solution_id']}&type=11'>{$resUrl}</a><i class='fa fa-question-circle'></i>";
								} 
							} else {
								$codeUrl = "{$LANGUAGE_NAME[$row['language']]}"; 
							}
						?>
							<tr class="<?php echo $JUDGE_ROW_CSS_CLASS[$row['result']]; ?>">
								<td><?php echo $row['solution_id']; ?></td>
								<td><?php echo "<a href='./userinfo.php?uid={$row['user_id']}'>{$row['user_id']}</a>";?></td>
								<td><?php echo "<a href='./contest_problem.php?cid={$cid}&pid={$problemNum}'>{$ALPHABET_N_NUM[$problemNum]}</a>"; ?></td>
								<td class="result"><?php echo $resUrl; ?></td>
								<td><?php echo $row['memory']; ?></td>
								<td><?php echo $row['time']; ?></td>
								<td><?php echo $codeUrl; ?></td>
								<td><?php echo $row['code_length']; ?></td>
								<td><?php echo $row['in_date']; ?></td>
							</tr>
						<?php } ?>
						</tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div><!--main wrapper end-->
		<?php require("./pages/components/footer.php");?>
	</body>
</html>