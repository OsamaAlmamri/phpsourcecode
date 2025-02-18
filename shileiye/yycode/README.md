#医疗网站代码库(yycode)	v20150703110

###代码库简介
	本代码库旨在规范网站代码，实现对网站扩展代码的统一调用及管理，希冀用最简洁易维护的代码达成各类"奇葩"的网站需求。
	本着共享精神，现将本代码开源，欢迎二次开发，但请保留版权。
	代码库不定期更新，若自动更新失败，请到shileiye.com获取最新补丁，如有疑问请在网站留言。
	演示网址：http://www.shileiye.com/yycode/swt/demo.html

	下面的说明可能比较多，简而言之，配置一下config.php文件内的参数，把swt文件夹上传到网站空间，在页面上加入以下代码
	<script src="/swt/swt.php?strblqw"></script>
	即可调用出各个模块，swt目录可随意命名以及放在任何目录，具体参数含义及个性化样式制作请自行参看文件说明以及相关文件代码注释。

###代码库文件结构
	/LICENSE						看不懂的自动生成的授权协议
	/README.md				代码库说明文件，使用记事本打开查看即可
	/代码库说明.txt				同上
	/UpdateList.txt				代码库更新记录列表
	/网络部技术标准.doc		一些能想到的问题处理方法（非强制）
	/other			织梦插件文件夹
	/swt			商务通相关文件夹
	..../html		模版文件目录
	..../img			系统图片资源目录
	..../inc			PHP类库目录
	..../js				系统JS资源目录（内置了代码库模版制作经常会用到的JS方法，文件内有注释用法。）
	..../config.php			参数配置文件
	..../index.php			链接跳转页面
	..../showclick.php		点击元素查看页面
	..../showtext.php		文字调用文件
	..../showimg.php		图片调用文件
	..../swt.php				模块调用文件
	..../uplist.php			程序升级页面
	..../demo.html			系统演示页面


###代码库文件说明
####一、参数配置文件
	文件路径：/swt/config.php
	说明：请在这个文件里填写站点相关参数。

####二、链接跳转文件
	文件路径：/swt/index.php
	参数：默认跳转商务通，可选参数：?qq，?tel，?sq，?mq
	说明：
		1、?后面的参数可选
		2、给a标签加上onclick="gotoswt(event,this,'thisname')"可传入点击统计参数('thisname'选填，可作标记)，此方法仅限无参数连接
	调用例子：
		<a href="/swt/" onclick="gotoswt(event,this,'ThisName')" target="_blank">商务通咨询</a>
		<a href="/swt/?qq" target="_blank">QQ咨询</a>
		<a href="/swt/?tel" target="_blank">免费电话</a>
		<a href="/swt/?sq" target="_blank">商桥咨询</a>
		<a href="/swt/?mq" target="_blank">美洽咨询</a>

####三、模块调用文件
	文件路径：/swt/swt.php
	参数：默认显示所有浮动广告，可选参数：?strblqw
		s：商务通邀请框
		t：页顶浮动菜单
		r：页面右边浮动广告
		b：页面底步浮动广告
		l：页面左边浮动广告
		q：页底QQ抖动框
		w：微信通知消息（仅在手机访问时显示）
		m：美洽JS调用
	参数说明：
		要显示哪块内容就把哪块内容的代码写到参数里，无先后顺序。配置文件内可以设置无参数默认显示内容，本参数优先级高于文件内部默认配置。
	调用方法：
		在页面底部</html>标签后加入以下代码
		<script src="/swt/swt.php?strblqw"></script>

####四、模版文件夹
	文件夹路径：/swt/html
	说明：文件夹中的每个文件对应一个功能模块，代码使用JS脚本，对应模版请参照/swt/config.php文件内模版参数注释。
	
	模版文件夹结构
	/html
	..../css			CSS文件目录
	..../img			图片资源目录
	..../other		其他资源目录
	..../pc_swt_body.js			PC中间邀请框模版
	..../pc_swt_bottom.js		PC底部浮动广告栏
	..../pc_swt_left.js			PC左侧浮动广告栏
	..../pc_swt_right.js			PC右侧浮动广告栏
	..../pc_swt_top.js			PC顶部浮动广告栏
	..../sj_swt_body.js			手机中间邀请框模版
	..../sj_swt_left.js				手机左侧浮动广告栏
	..../sj_swt_right.js			手机右侧浮动广告栏
	..../sj_swt_wx.js				手机微信通知广告栏
	..../pc_swt_qq.js				QQ抖动窗口
	..../other_meiqia.js			美洽JS调用

	模版制作说明
		1、模版实现简单标签化，模版中使用{swtdir}代表系统参数中的swtdir值。
		2、在配置文件中的所有info参数均可标签化，可任意增加参数。
		3、模版为JS代码，支持直接写JS效果，模版内置了一些JS方法可以直接调用（/swt/js/other_jsfun.js）。
		4、模版内不要使用window.onload=function(){...}方法，请使用内置的：$$$(function(){...})方法执行。
		5、如非必要，尽量不要在模版标签中写style属性，使用class调用。
		6、增加模块或者删除模块请修改/swt/config.php文件内的模版调用参数，具体方法参见注释。
		7、容易混淆理解的代码请添加注释。


####五、文字调用文件
	文件路径：/swt/showtext.php
	可选参数：配置文件中设置的所有参数均可调用。
	调用方法：
		1、在页头调用文件
		<script src="/swt/showtext.php"></script>
		2、在需要调用文字的地方使用以下代码调用
		<script>showtext("web");</script>
	注意事项：
		1、电话连接（tel:12345678）请使用新窗口打开，否则部分手机浏览器会报错。

####六、点击元素查看页面
	文件路径：/swt/showclick.php
	说明：利用这个文件可以查看访客点击了页面的哪个位置进行商务通对话。
	参数格式：?浏览器类型|网页分辨率|点击坐标|页面URL
	地址实例：
		http://www.tyek120.com/swt/showclick.php?Chrome|1344,5970|573,4637|http%3A%2F%2Fwww.tyek120.com%2Findex.html
	注意事项：
		1、这个功能属于测试，不同浏览器下可能不太准确，大致位置不会偏移太多。
		2、如果出现错位，请脑补一下。
		3、文件未完善，页面URL最好跟文件所在站点同域名。

####七、图片调用文件
	文件路径：/swt/showimg.php
	文件说明：主要用于切换修改一段时间后可能会换回来的图片，如带医院名称的图片。
	参数说明：
		1、?后面跟上图片绝对地址。
		2、地址中需要切换的文件夹使用[...]包括起来，用|隔开。
	调用例子：<img src="/swt/showimg.php?/swt/html/[img|imgs]/wx.png" />

####八、程序升级页面
	文件路径：/swt/uplist.php
	升级说明：
		1、用浏览器访问此文件查看可用更新，可在页面点击一键升级程序。
		2、请每隔一段时间打开看看程序有木有更新。保持程序最新版本是个好习惯！
		3、20150422+版本已支持每天自动更新。


##通用JS库（请注意路径的大小写）

	jQuery：（2.x以上版本不支持IE9以下浏览器）
	<script src="http://js.cdmz.net/js/jQuery/2.1.1.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.9.1.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.8.3.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.7.2.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.6.4.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.5.2.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.4.4.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.3.2.min.js"></script>
	<script src="http://js.cdmz.net/js/jQuery/1.2.3.min.js"></script>

	jQuery滚动&幻灯片插件：
	<script src="http://js.cdmz.net/js/jQuery/Plug-in/jquery.SuperSlide.2.1.1.js"></script>
		调用参考：http://www.superslide2.com/demo.html

	html5兼容补丁（为IE6-9兼容HTML5提供支持 ）
	<!--[if IE]>
		<script src="http://js.cdmz.net/js/html5shiv/3.7.2.min.js"></script>
	<![endif]-->

	CSS3兼容补丁（为IE6-9兼容CSS3提供支持 ）
	<!--[if IE]>
		<script src="http://js.cdmz.net/js/respond/1.4.2.min.js"></script>
	<![endif]-->

	PNG图片透明补丁（为IE6显示PNG透明图片提供支持，参数可为'*'或HTML标签或CSS类名）
	<!--[if IE 6]>
		<script src="http://js.cdmz.net/js/DD_belatedPNG/0.0.8a-min.js"></script>
		DD_belatedPNG.fix('img,div,ul,li,input,a,.trans,.box a:hover'); 
	<![endif]-->
#
		Author:shileiye
		MailTo:shileiye@qq.com
			2015.4.18