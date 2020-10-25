<body>
	<?php require('./pages/components/offcanvas.php');?>
	<div class="container" id="mainContent">
		<div class="page-header">
			<h1>Welcome <small>Super User</small></h1>
		</div>
		<p class="lead">
			To start management, please <b>click a link in the side-menu</b> on the left side of this page.
		</p>
		<p>If you are using mobile device (Pad, Smart Phone, etc.) or small screen device, you should click the button on the left-top side of the page. <br/>
		Click <a href="#">here</a> if you need a tortual.</p>
		<div id="summernote">Hello Summernote</div>
	</div>
<script>
$(document).ready(function() {
	$('#summernote').summernote({
		height: 300,  
		focus : true
	});
});
//var sHTML = $('#summernote').code();
</script>
</body>