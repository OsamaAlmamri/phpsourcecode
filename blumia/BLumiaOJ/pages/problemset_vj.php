
	<body>
		<?php require("./pages/components/navbar.php");?>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<div class="btn-group" role="group" id="oj-ps-pager">
						<?php
							for($totnum = 1,$pagenum = 1;$totnum<=$totalCount;$totnum+=$PAGE_ITEMS,$pagenum++) {
								echo "<button id='page".$pagenum."' onclick='changepage(".($pagenum-1).");' type='button' class='btn btn-default'>".$pagenum."</button>";
							}
						?>
					</div>
				</div>
				<div class="col-md-3 col-xs-6">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="输入题目编号">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">走起</button>
						</span>
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
				<div class="col-md-3 col-xs-6">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="输入标题关键字">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">搜索</button>
						</span>
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			</div><!-- /.row -->
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped table-hover" id="tableID">
						<thead>
							<tr>
								<th width="5%">AC</th>
								<th width="5%">ID</th>
								<th width="40%">Title</th>
								<th width="20%">Difficulty</th>
								<th width="16%">Source</th>
								<th width="14%">AC/Submit</th>
							</tr>
						</thead>
						<tbody id="oj-ps-problemlist">
						
						</tbody>
					</table>
				</div>
			</div>
		</div><!--main wrapper end-->
		<?php require("./pages/components/footer.php");?>
	<script type="text/javascript">
	var tour = new Tour({
		backdrop: true,
		debug: true,
		steps: [
		{
			element: "#oj-ps-pager",
			title: "This is Pager",
			content: "You can switch page by click here",
			placement: "bottom"
		},
		{
			element: "#oj-ps-problemlist",
			title: "This is the Problem list",
			content: "Click a problem title to challenge the problem.",
			placement: "top"
		}]
	});
	</script>
	<script type="text/javascript">
	function changepage(num){
		NProgress.start();
		$.ajax({
			url:"./api/ajax_problemset_vj.php?p="+num+"&oj=<?php echo $onOJ; ?>",
			async:false,
			contentType:"application/x-www-form-urlencoded; charset=utf-8",
			success:function(data/*返回的数据*/, textStatus, jqXHR){
				document.getElementById("oj-ps-problemlist").innerHTML=data;
				$("tr").fadeIn();
			},
			complete:function(jqXHR, textStatus){
				NProgress.done();
			}
		});
	}
	/*
	function pagerModify(onePageItem, allPageItem) {
		htmlText = "";
		for(i = 1;i<=allPageItem;i+=onePageItem)
			htmlText = htmlText+"<button id='page"+i+"' onclick='changepage("+(i-1)+");' type='button' class='btn btn-default'>"+i+"</button>";
		$("#oj-ps-pager").innerHTML = htmlText;
		console.log(htmlText);
	}
	*/
	changepage(<?php echo $p;?>);
	</script>
	</body>