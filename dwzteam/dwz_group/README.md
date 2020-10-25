# DWZ框架 + ThinkPHP 实现小组工作日志系统

主要功能是可以清楚的看到一周，每人手上都有什么任务，方便合理安排和调整小组任务

## Quick Start：

	1）安装部署：

		a）下载代码，Apahce PHP环境创建虚拟主机

		b）导入Mysql数据 mysql -u root -p dwz_group < dwz_group.sql

		c）打开dwz_group/Admin/Conf/config.php 配制数据库用户名和密码

		d）打开dwz_group/Admin/constants.inc.php 配制部门

	2）admin用户登入系统(默认密码admin)，创建用户，分配角色（系统默认有组员和组长2个角色，已经设置好权限，一般情况无需再创建新角色）

	3）用户登入，开始使用工作日志系统

	注意：配制完成后，如果浏览器访问报错，请检查php.ini配制中mysql相关extension是否放开.
	如果登入验证码无法显示请检查是否已经开启GD库支持【命令行输入: php -i | grep -i --color gd】

	因为ThinkPHP路径支持多种配置，解决手机端不能登录问题需要修改JS配置文件(/dwz_group/Public/wap/resources/js/biz.js)
	中路径 baseUrl:{DEV:'../../../Admin/index.php', LIVE:'../../../Admin/index.php'}

jUI富客户端框架在线demo： http://jui.org/

技术支持：0571-88517625	17767167745

## DWZ工作日志系统移动客户端：

	移动端和Web版同一入口地址，服务器端根据手机和电脑自动重定向。

	主要功能是智能手机中看周报，写工作日志，方便合理安排和调整小组任务。
	方便外出时身边没有电脑，也只可以使用智能手机写工作日志，并查看团队成员工作周报。
	工作周报是系统自动汇总团队中每位成员每天的工作日志动态生成，无需管理人员花费核外的时间去总结整理，从而提高工作效率。
	移动客户端是Web版的一个简化版本，Web版中组长可以修改组内其它成员的工作日志，移动客户端没有此功能。手机屏幕小主要也就写自己的工作日志和看周报。

## DWZ工作日志系统截屏

### 电脑屏幕截屏
<img src="/doc/pic/1.jpg">

### 手机屏幕截屏
<img src="/doc/pic/2.jpg" width="320">
<img src="/doc/pic/3.jpg" width="320">
<img src="/doc/pic/4.jpg" width="320">
<img src="/doc/pic/5.jpg" height="320">
