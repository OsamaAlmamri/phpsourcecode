<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" name="keywords" content="" />
	<meta http-equiv="content-type" name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{csrf_token()}}" />
	<link rel="icon" href="data:;base64,iVBORw0KGgo=" />
	<link rel="stylesheet" href="{{mix('css/app.css', '/dist')}}" />
	<title>EBank@电子银行--站内虚拟积分与聚合支付解决方案</title>
</head>
<body class="mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-theme-accent-blue mdui-loaded">
<div id="app"></div>

<script>
	var APP_URL = "{{config('app.url')}}";
	var PROJECT_NAME = "{{$project_name}}";
	var GITHUB_SHOW = parseInt("{{$github_show}}");
</script>
{{--<script src="{{mix('js/manifest.js', '/dist')}}"></script>--}}
{{--<script src="{{mix('js/vendor.js', '/dist')}}"></script>--}}
<script src="{{mix('js/app.js', '/dist')}}"></script>
</body>
</html>
<html>
