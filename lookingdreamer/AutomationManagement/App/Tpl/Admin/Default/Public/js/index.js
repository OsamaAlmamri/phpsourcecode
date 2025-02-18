$(document).ready(function() {

	$(window).resize(function () {
	  var width = $(this).width();
		if (width < 1367) {
			$('.three-columns .appendable').appendTo('#left-sidebar');
			$('.three-columns .content').css('marginRight', '0');
			$.sparkline_display_visible();
		}else {
			$('.three-columns .appendable').appendTo('#right-sidebar');
			$('.three-columns .content').css('marginRight', '240px')
			$.sparkline_display_visible();
		 }
	}).resize();
	
	//===== Top nav and responsive functions =====//

	$('a[data-toggle="dropdown"]').click(function () {
		$(this).next('ul.dropdown-menu').fadeToggle(50,'',function(){
			$(this).mouseleave(function(){$(this).fadeOut();});
		});
	});
	$('.sidebar-button > a').toggle(function () {
			$('.sidebar').addClass('show-sidebar').removeClass('hide-sidebar');
		},
		function () {
			$('.sidebar').removeClass('show-sidebar').addClass('hide-sidebar');
		}
	);
	
	//===== Sparklines =====//
	
	$('#spiders').sparkline(
		'html', {type: 'bar', barColor: '#db6464', height: '35px', barWidth: "5px", barSpacing: "2px", zeroAxis: "false"}
	);
	$('#todaynews').sparkline(
		'html', {type: 'bar', barColor: '#a6c659', height: '35px', barWidth: "5px", barSpacing: "2px", zeroAxis: "false"}
	);
	$('#todayviews').sparkline(
		'html', {type: 'bar', barColor: '#4fb9f0', height: '35px', barWidth: "5px", barSpacing: "2px", zeroAxis: "false"}
	);
	
	$('.slim .bar').progressbar();
	
	$('.expand').collapsible({
		defaultOpen: 'current',
		cookieName: 'navAct',
		cssOpen: 'subOpened',
		cssClose: 'subClosed',
		speed: 200
	});
	
	$('.table-checks tbody tr td:first-child input[type=checkbox]').change(function() {
        $(this).closest('tr').toggleClass("row-checked", this.checked);
	});
	
	//$(".style").uniform({ radioClass: 'choice' });
	$(".checkboxall").click(function(){
			if($(this).attr("checked")){
				var flag = true;
			}else{
				var flag = false;
			}
			$(".checkboxrow").each(function(){
				$(this).attr("checked", flag);
				$(this).closest('tr').toggleClass("row-checked", flag);
			});
	});
	$(".table-checks tr.row td:not(.notallow)").click(function(){
			var node = $(this).closest('tr').find("input");
			if(node.attr("checked")){
				var flag = false;
			}else{
				var flag = true;
			}
			node.attr("checked", flag);
			$(this).closest('tr').toggleClass("row-checked", flag);
	});
	
	$("a.dialog-action").click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href')||"";
		var checkbox = $(this).attr('checkbox')||"";
		var dialog = $(this).attr('dialog')||"";
		var params = $(this).attr('params')||"";
		var message = $(this).attr('message')||"确定要执行此操作吗？";
		var ids = "";
	        var cmd = $(this).attr('cmd')||"";
		var tr = $(this).closest('tr');
		if(checkbox=="true"){
			$(".checkboxrow").each(function(){
				if($(this).attr("checked"))ids += $(this).val()+","
			});
			if(ids == ""){
				var dlg = bootbox.dialog("请先选择要执行本次操作的数据！",[{
					"label" : "确定",
					"class" : "btn-primary",
					"icon"  : "icon-ok-sign icon-white",
					"callback": function() {
						dlg.modal('hide');
					}
				}]);
				return false;
			}else{
				if ( cmd  == ""){
				params += (params==""?"":"&")+"ids="+ids;
				}else{
				params += (params==""?"":"&")+"key="+ids;	
				}
			}
		}
		if(dialog=="true"){
				var dlg = bootbox.dialog(message, [{
					"label" : "取消",
					"class" : "btn-primary",
					"icon"  : "icon-warning-sign icon-white",
					"callback": function() {
						dlg.modal('hide');
					}
				}, {
					"label" : "确定",
					"class" : "btn-danger",
					"icon"  : "icon-ok-sign icon-white",
					"callback": function() {
						params += (params==""?"":"&")+"isajax=1";
						$.ajax({
						  type: 'POST',
						  url: url,
						  data: params,
						  success: function(data){
								dlg.modal('hide');
								if(data.status==0){
									dlg = bootbox.dialog(data.info, [{
										"label" : "确定",
										"class" : "btn-primary",
										"icon"  : "icon-ok-sign icon-white",
										"callback": function() {
											dlg.modal('hide');
										}
									}]);
								}
								if(url.indexOf("delete")!=-1){
									if(checkbox == "true"){
										$(".checkboxrow").each(function(){
											if($(this).attr("checked"))$(this).closest('tr').hide();
										});										
									}else{
										tr.hide();
									}
								}
						  }
						});
					}
				}]);
		}else if(dialog=="after"){
				params += (params==""?"":"&")+"isajax=1";
				$.ajax({
						type: 'POST',
						url: url,
						data: params,
						success: function(data){
								dlg = bootbox.dialog(data.info, [{
										"label" : "确定",
										"class" : "btn-primary",
										"icon"  : "icon-ok-sign icon-white",
										"callback": function() {
											dlg.modal('hide');
										}
								}]);
								if(url.indexOf("delete")!=-1){
									if(checkbox == "true"){
										$(".checkboxrow").each(function(){
											if($(this).attr("checked"))$(this).closest('tr').hide();
										});										
									}else{
										tr.hide();
									}
								}
						}
				});			
		}else{
				if(params!="")url += (url.indexOf("?")==-1?"?":"&")+params;
				window.location.href = url;
		}
	});
	
	$(".uploadstyle").uniform({fileDefaultText:"未选择文件"});
	
});
