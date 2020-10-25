gitbook 在线文档：

[https://djunny.gitbooks.io/mzphp/content/index.html](https://djunny.gitbooks.io/mzphp/content/index.html)

enphp 在线加密：

[http://enphp.djunny.com/](http://enphp.djunny.com/)

### 更新历史

[2016.12.30]
新增：
1. 增加 domain router 可加入默认 request 参数
2. 增加模板引擎压缩 html.
3. CACHE 类可同时实例化多个缓存 CACHE::instance('xxx')
4. 增加 DB::select() 查询列表，可返回索引下标
5. 增加 hook 方法，可用于实现插件或者扩展。
6. base_control 加入缓存页面功能，在 control 对应方法中调用 $this->cache_page($cache_key, $cache_time = 60);，即可缓存当前页面渲染结果，下次访问直接读内存。
7. 模板 static 语法增加自动检测同目录 xxx.min.js，用于自动压缩后，可不需要调用 mzphp 的 js 压缩引擎，获得更高性能。

修复：
1. 修复 abs_url dir='0' 的问题
2. 修复 pdo_mysql 在 PHP7下的BUG
3. 重命名 hook 缓存文件
4. 修复在 cli 命令行下运行脚本太久导致运行时间显示不正确的问题.
5. 修复 spider 类在自动识别 charset 时对于 jpg 图片有 xml 代码时的问题，$header 传入 array('charset'=>'bin') 关闭自动识别 html charset功能。

优化：
1. 优化 redis 类的性能，更加友好支持 redis 扩展方法,例如 CACHE::instance('redis')->lpop();
2. 优化 mysql build_where 方法

[2016.1.27]

新增：
   1. 支持从入口文件中加载 control
   2. 新增独创的 EnPHP 工具：可将 PHP 项目混淆加密，欢迎试用
   3. 模板引擎新增编译前缀，用于站群模式多个站点可复用不同目录下相同文件名的模板
   4. 新增地址重写支持多级数组，例：/where[time][0]/20120102/where[time][1]/20120103/
   5. url 方法 action 增加默认值
   6. spider 类在 HTTPS 请求时支持 SSL 证书
   7. 模板引擎新变量{CDN}，用于绑定 CDN 路径

修复：
   1. core::json_encode 不支持部分中文字符集的 BUG
   2. spider 在 5.6 版本中无法支持 @ 上传文件的问题
   3. 在某些环境下的 warning 提示

优化：
   1. 优化模板引擎，去除兼容变量，部分无用注释


### 性能评测

既然大家都对性能这么有疑问，那么我拿 Hello World 来做一个简单的评测：

**如何评测性能？**

XDEBUG 后能产生一个 profile
通过 wincachegrind 能直观看到框架每一行的性能损耗。

**测试环境**

Windows 虚拟机，i7 2核 + 1G内存。PHP 最基础环境。



**测试框架**

1. mzphp 最新版本（2015.11.30）
2. TP 最新版本（2015.11.30）
3. CI 最新版本（3.02）


**测试结果**

为了最佳性能，会让三个框架连续运行 20 次，然后输出 XDEBUG 的 profile：

最终的 profile 文件大小对比：

![PROFILE 对比](http://static.oschina.net/uploads/space/2015/1216/135255_ScCR_130215.jpg "在这里输入图片标题")


**结果对比**

mzphp 的 profile line-by-line结果：

![mzphp overall](http://static.oschina.net/uploads/space/2015/1130/191415_eURD_130215.jpg "在这里输入图片标题")

TP 的 profile line-by-line结果：

![tp overall](http://static.oschina.net/uploads/space/2015/1130/191726_JDUS_130215.jpg "在这里输入图片标题")

CI 的 profile line-by-line结果：

![ci overall](http://static.oschina.net/uploads/space/2015/1216/135255_buEP_130215.jpg "在这里输入图片标题")

mzphp 的 profile overall结果：

![mzphp overall](http://static.oschina.net/uploads/space/2015/1130/191415_5XqX_130215.png "在这里输入图片标题")

TP 的 profile overall结果：

![tp overall](http://static.oschina.net/uploads/space/2015/1130/191726_RBVC_130215.jpg "在这里输入图片标题")


CI 的 profile overall结果：

![ci overall](http://static.oschina.net/uploads/space/2015/1216/135558_aIk4_130215.jpg "在这里输入图片标题")

mzphp 跑热后，项目加载以及页面运行的时间为 **1.6ms**

CI 跑热后，项目加载以及页面运行的时间为 **2.7ms**

而 TP 跑热后，页面运行时间为：**5.5ms**


具体的 profile 数据，大家可以下载自行查看（window 下使用 [wincachegrind](https://github.com/ceefour/wincachegrind/releases/tag/1.1) 查看）：

传送地址：[profile下载](http://git.oschina.net/mz/mzphp2/attach_files/download?i=18030&u=http%3A%2F%2Ffiles.git.oschina.net%2Fgroup1%2FM00%2F00%2FB5%2FfMqNk1ZcM52ALi1XAAAvR4KUI0E424.rar%3Ftoken%3Dbea927e70f52c4dde2ed7901222ffe18%26ts%3D1448883042%26attname%3Dprofile-xdebug.rar)

### 序

很久以来，楼主开发站点无数，一直希望能有一个枚，日开数站的 PHP 框架出现。

### mzphp 介绍


PHP 开发框架 mzphp，拥有特点：

- 性能，高性能极致加载、高效率编译和读取！
- 清晰，大量注释及实例，几分钟就上马进门！
- 小巧，整个框架 100k，几乎没有冗余代码！
- 奔放，支持 http 和 cli 双运行，php index.php {$control} {$action} 就能体验 PHP 在命令行下的奔放。（可用于后台常驻内存、爬虫等）
- 易用，优化过的 discuz 模板引擎，使模板调用更加简约（兼容 php 标签，支持static、block、{method()}等标签）
- 安全，简单又安全的取参数过程，有效防止 xss，封装的 SQL 防注入过程，确保系统安全。
- 扩展，丰富的库和插件：MySQL、PDO 引擎、scss 语法支持、css 压缩、css sprite 生成、js 合并、js 压缩等。（前后端开发相亲相爱不再分离）
- 调试，详细的 DEBUG、运行时间和SQL查询信息，只需在 URL 中加上固定参数，简单好用。

mzphp 做为一直追求高效、简单、快速开发的 PHP 框架，希望大家多多支持。

### mzphp 运行的环境

- 操作系统：windows、linux 
- PHP 环境：PHP 5.3+
- PHP 扩展：php-gd、php-mbstring、php-curl
- PHP 扩展（数据库）：php-mysql 或 php-pdo
- PHP 扩展（缓存）：php-memcached 或 php-redis


如果需要 php 在 cli (命令行) 下运行，而您的操作系统是 windows。

请在系统 -> 环境变量 -> PATH -> 添加你的 php.exe 所在路径


### 拉取 mzphp 代码

`
git clone git@git.oschina.net:mz/mzphp2.git
`

完后，可更新子项目例子：

`
	git submodule update
`

### 生成第一个项目

假我们需要建立的项目名称为：hello_world

请在项目目录建立一个hello_world(与 mzphp 目录同级)

复制 tools 目录下的 create_project.php 到　hello_world 目录。

提供两种方法生成项目结构：

- 第一种：打开浏览器访问 create_project.php ，生成
- 第二种：在命令行下 

```
cd hello_world

php create_project.php
```


访问生成的 index.php，可看到 mzphp Framework 字样，即访问成功.

### mzphp 目录文件

- cache         缓存支持（file、memcache、redis）
- core          核心方法
- db            数据库支持（mysql、pdo_mysql、pdo_sqlite）
- debug         调试支持
- helper        一些扩展库以及方法（log、misc、spider）
- plugin        插件目录
- mzphp.php     入口文件


### 项目目录文件

- conf         项目配置目录
- control      项目控制器目录
- core         默认 include 的库、基类、方法等
- data         项目数据保存目录
- static       所有静态文件（css/js/图片）存储目录
- view         项目模板目录
- index.php    入口文件

### 配置项目参数

打开项目 conf/conf.{env}.php 可按照注释编辑对应的参数。

{env}代表运行环境，由 $_SERVER['ENV'] 来控制 (默认为 debug)。

在 nginx 中，您可以通过添加：

`
fastcgi_param ENV 'online';
`

来切换线上、线下环境。


**附上一些小的参数修改**

- index.php 中，在定义 DEBUG 处，来设置启动 debug 的 url 参数。
- index.php 中，在定义 FRAMEWORK_PATH 处，可修改 mzphp 框架所处的路径


### 调试方法

index.php 中，你可以直接设置 **define('DEBUG', 1)** 永久打开 debug

也可以修改 **hello_world_debug** 这个字符串来告之项目当 url 中包含该字符时，自动开启 debug。

debug 开启时，右下角将出现一个浮动的运行时间展示，点击开来可以看到具体页面运行的信息。

**注意**

mzphp 框架为了减少磁盘 I/O，提高加载性能，会在项目第一次运行时，

- 生成的缓存文件在 data/runtime.php 中。
- 缓存的代码为： mzphp 框架下所有文件和项目 core 目录中所有 *.class.php 文件。
- 当开启 debug 后，所有文件均为动态加载，会跳过加载缓存 runtime.php。
- 当开启 debug 后，模板编译后的缓存文件不会加载。
- 当开启 debug 后，css 合并、js 合并等静态文件会重新生成。


### mzphp 控制器

在项目 control 目录中建立一个 user_control.class.php

同时，在该文件中定义一个 user_control 继承 base_control

```
<?php
!defined('FRAMEWORK_PATH') && exit('Accesss Deined');
class user_control extends base_control {
    public function on_index(){
        // define user
        $user = 'djunny';
        // assign variables
        VI::assign('user', $user);
        // default template path is view/user_index.htm
        $this->show();
    }
}
```
**$this->show** 调用显示模板，

可以自动识别为 view/user_index.htm,

即 {$control}_{$action}.htm

假如，view/user_index.htm 如下：
```
hello {$user}
```


使用 index.php?c=user-index 可访问到 user_control 的 on_index 方法，显示结果：

```
hello djunny
```


同时，使用命令行下：
```
php index.php user index 
```

也能在命令行下看到同样的结果

小建议：

- 在命令行下，你可以 core 中建立一个 cmd_control，在析构方法中做一些限制，例如判断是否 cmd 下运行（可以用 core::is_cmd() 方法）, user_control 继承 cmd_control，能有效的防止 control 被 http 请求到。
- 在命令行下，不建议使用 echo 来输出 log，可以使用帮助类 log::info($output) 来输入出 log。$output 可以为字符串、数字、数组、MAP。
- VI::assign 是传**引用**绑定变量、VI::assign_value 是**传值**绑定变量
- 如果调用 $this->show('user/index')，代表渲染 view/user/index.htm 模板文件。


### mzphp DAO 数据层

第一步：配置 conf 文件中的 db。

第二步：创建 user 表：
```
CREATE TABLE `user` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  PRIMARY KEY (`uid`)
) DEFAULT CHARSET=utf8 COMMENT='user'
```


第三步，model 层有二种调用方法。

第一种，不定义 model，直接使用

```
<?php
!defined('FRAMEWORK_PATH') && exit('Accesss Deined');
class user_control extends base_control {
    public function on_index(){
        // SELECT * FROM user WHERE id =1
        $user = DB::T('user')->get(1);
        // ...
    }
}
```

此方法用于简单的 DAO 层调用。

第二种，在项目 model 目录中，新建一个 user.class.php 定义 user 继承自 base_model， 
```
<?php
!defined('FRAMEWORK_PATH') && exit('Accesss Deined');
class user extends base_model {
	function __construct() {
		parent::__construct('user', 'id');
	}
}
?>
```

在控制器中使用

```
<?php
!defined('FRAMEWORK_PATH') && exit('Accesss Deined');
class user_control extends base_control {
    public function on_index(){
        // SELECT * FROM user WHERE id =1
        $user = $this->user->get(1);
        // ...
    }
}
```

mzphp 中，控制器会自动加载并初始化对应的 model 层。

另外：

- mzphp 也支持原生查询 SQL，使用以下方法：


```
// add table prefix

$table = DB::table('user');

// build SQL

$sql = sprintf("SELECT * FROM %s WHERE id=%d", $table, 1);

// get query and return first row

$first_row = DB::query($sql, 1);

// get query 

$query = DB::query($sql);

while($val = DB::fetch($query)){

    log::info($val);


}

// select method

// get first row from user where id =1

$user = DB::select('user', array('id'=>1), ' id ASC', 0);

// get all user array

$user_list = DB::select('user');

// get user count

$user_count = DB::select('user', 0, 0, -2);

```


### mzphp 模板引擎 - 基础语法

1. <!--{code}-->                             默认注释语法 
2. {code}                                    嵌入属性语法
3. {$variable}                               输出变量
4. {CONSTANT}                                输出常量
5. {$array['index']}                         输出数组下标
6. {function(param1, param2, param3...)}     调用原生方法

模板中动态渲染内容，基本上由以上语法组成。

- 语法1 多用于 html 块级输出，例：
```
<!--{if 1==2}--><html></html><!--{/if}-->
```

- 语法2 多用于元素属性输出，例：
```
<form {if 1==2}method="post"{/if}>
```

- 语法6 调用原生方法，输出值必须为方法结果 return


### mzphp 模板引擎 - 子模板

**说明：**

加载一个子模板。(子模板最多支持三层嵌套)

**语法：**
```
<!--{template header.htm}-->
```

### mzphp 模板引擎 - 逻辑判断

**说明：**

if else 逻辑判断

**语法：**

```	
<!--{if $a == $b && $b == 1}-->
	a = b AND b = $b
<!--{elseif $b == 2 || $a == 3}-->
	b = $b OR a = $a
<!--{elseif $a == 1}-->
	a = 1
<!--{else}-->
	a != 1
<!--{/if}-->
```

### mzphp 模板引擎 - 循环

**说明：**

循环输出列表，可嵌套输出

**语法：**

```
<!--{loop $categories $index $cate}-->
	categories[$index]= {$cate['name']}
<!--{/loop}-->

```

### mzphp 模板引擎 - eval 标签
**说明：**

执行 php 代码。

**语法：**

```
<!--{eval 
$a = 1;
$b = array(
	'a' => 1,
	'b' => 2,
);
}-->

<!--{eval echo $my_var;}-->
<!--{eval $my_arr = array(1, 2, 3);}-->
<!--{eval print_r($my_arr);}-->
<!--{eval exit();}-->
```


### mzphp 模板引擎 - 方法调用标签

**说明：**

调用的方法并输出返回的内容。(方法必须以返回值)
	
**语法：**
```
{date('Y-m-d')}
{substr('123', 1)}
{print_r(array(1), 1)}
{spider::html2txt('<html></html>')}
```


### mzphp 模板引擎 - 静态文件

**语法：**
```
<!--{static source_file target_file is_compress}-->
```
**说明：**

source_file 文件后缀为 scss 或者  js 时，会调用对应的编译类对文件进行编译和生成，生成的文件路径：static/{$target_file}

is_compress 参数可以为 0 或 1(默认 1)，为 1 时，source_file 压缩，反之而不压缩。

在 debug 开启后，每次访问页面，都会生成一遍静态文件。在代码 push 的时候，需要产生的所有静态文件提交（线上不会再编译静态资源）
	
**规范**

- 建议编译后的缓存文件名都使用 _ 开头命名，这样后期方便清理。
- 编译后的 css 文件只能在原 css 目录（否则相对路径的图片可能加载失败）
- 编译后的 js 文件最好在原 js 目录（除非你的 js 文件没有对路径有依赖）

**静态文件寻找先后顺序**

1. static/
2. view/


**关于 css sprite**

当 source_file 以 * 结尾时, 识别为 css sprite 模式，程序将会对 source_file 目录中的所有文件进行合并，读取所有图片的 size ，生成对应的 scss 文件和合并的 png 文件：

1. target_file.scss （命名方式：.filename-png，文件名中_和.替换成-）
2. target_file.png（不需要重复的图片合并文件）
3. target_file-x.png （需要 x 轴重复的图片合并文件）
4. target_file-y.png（需要 y 轴重复的图片合并文件）

**实例**
```
<!--{static scss/png/* scss/sprite}-->
<!--{static scss/test.scss scss/_a.css}-->
<!--{static js/test1.js js/_a.js}-->
<!--{static js/test2.js js/_a.js 0}-->
<!--{static js/test3.js js/_a.js 1}-->
```

在以上例子中,系统做了以下处理：

1.先合并 scss/png/ 中所有图片为: scss/sprite.png，同时生成坐标 scss 文件：scss/sprite.scss

2.scss/test.scss 中调用 @import 'sprite'; 然后用 scss 语法来继承对应的 icon 或 图片即可，例（图片路径中.会变成-，更具体可以先成生一次后，打开生成后的sprite.scss看看）：


```
.find{
    @extend .loading-png;
}
```

3.开启合并文件 js/_a.js ，并将 js/test1.js 写入。

4.读取 js/test2.js，写入 js/_a.js 尾部。

5.读取 js/test3.js，并压缩，写入 js/_a.js

6.<!--{static scss/png/* scss/sprite}--> 替换成 空

7.<!--{static scss/test.scss scss/_a.css}--> 替换成 < link rel="stylesheet" href="scss/_a.css">

8.<!--{static js/test1.js js/_a.js}--> 替换成 < script src="js/_a.js"></ script>

9.<!--{static js/test2.js js/_a.js 0}--> <!--{static js/test3.js js/_a.js 1}--> 均替换成空



### mzphp 地址重写

地址重写规则：

**.htaccess**
```
Options 
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

#control + action
RewriteRule ^(\w+)(?:[/\-_\|\.,])(\w+)(?:[/\-_\|\.,])(.*)$ index.php\?c=$1-$2&rewrite=$3 [L]

#control 
RewriteRule ^(\w+)(?:[/\-_\|\.,])(.+)$ index.php\?c=$1&rewrite=$2 [L]
```

**apache httpd.ini**
```
[ISAPI_Rewrite]
RepeatLimit 32
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

#control + action
RewriteRule ^(\w+)(?:[/\-_\|\.,])(\w+)(?:[/\-_\|\.,])(.*)$ index.php\?c=$1-$2&rewrite=$3 [L]

#control 
RewriteRule ^(\w+)(?:[/\-_\|\.,])(.+)$ index.php\?c=$1&rewrite=$2 [L]
```
**nginx.conf**
```
#control + action
rewrite ^(\w+)(?:[/\-_\|\.,])(\w+)(?:[/\-_\|\.,])(.*)$ index.php?c=$1-$2&rewrite=$3 last;
#control 
rewrite ^(\w+)(?:[/\-_\|\.,])(.+)$ index.php?c=$1&rewrite=$2 last;
```

**iis 7**
```
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
    <rewrite>
       <rules>
			<rule name="mzphp_1" stopProcessing="true">
				<match url="(\w+)(?:[/\-_\|\.,])(\w+)(?:[/\-_\|\.,])(.*)$" />
				<action type="Rewrite" url="index.php?c={R:1}-{R:2}&rewrite={R:3}" appendQueryString="true" />
			</rule>
			<rule name="mzphp_2" stopProcessing="true">
				<match url="(\w+)(?:[/\-_\|\.,])(.+)$" />
				<action type="Rewrite" url="index.php?c={R:1}&rewrite={R:2}" appendQueryString="true" />
			</rule>
        </rules>
    </rewrite>
    </system.webServer>
</configuration>
```

### mzphp 自定义 URL

**1.非地址重写链接：**

```
index.php?c=control-action&var=...
```

**2.系统地址重写(该地址中的 / 分隔符和 [.html] 均可在conf文件中配置):**

```
/control/action/param1/param1value/param2/param2value...[.html]
```

conf/conf.[env].php 配置文件：

```
    //url rewrite params
	'rewrite_info' => array(
		'comma' => '/', // options: / \ - _  | . , 
		'ext' => '.html',// for example : .htm
	),
```

使用系统地址重写时，您可以使用url方法，来生成url:
```
function url($control, $action, $params = array()) ;
```
例：
```
echo url('index', 'index', array('id'=>1));
echo url('index-index', array('id'=>1));
echo url('index-index', 'id=1&time=2015');
```


**3.自定义地址重写：**
```
index.php?c=control-action&rewrite=param1/param1value...
```
例如，我们需要使用：**/help/123/** 来映射 **index.php?c=article-help&id=123**


可以在 urlrewrite 重写文件中配置（以 .htaccess 语法为例）：

```
RewriteRule ^(help)/(\d+)/?$ index.php\?c=article-$1&rewrite=id/$2 [L]
```

注：本例中 rewrite 参数传入的分割符（/），视 **rewrite_info** 中的 **comma** 而定。

****

### 钩子方法 HOOK

通过钩子方法，能在系统任何代码处调用在 hook 中注册的方法。

可用于插件或者扩展功能实现。

 **运行 hook** 

```
function hook($name) {}
```

 **输出 hook** 

输入 hook 一般在模板中直接调用比较多

```
function out_hook($returns, $implode = '') {}
```

### 钩子使用例子

在项目根目录的 index.php 中 ，定义存放钩子类的目录（例子中定义为项目根目录的 hook目录）

```
define('HOOK_PATH', ROOT_PATH.'hook/');
```

在对应目录下，创建一个 hook 目录 。

hook 目录下建立一个 hook_xxx.php，内容如下：

```
class hook_xxx {
   static function func($abc = ''){
       return '<a>';
   }

}
```

TIP：hook_xxx.php 和 PHP 文件 class hook_xxx 必须一致。

此时，清理 tmp 目录（如果没有开启 DEBUG 模式则需要每次修改 hook 文件后操作）后，

在任意代码地方调用：

```
hook('func');
```

也支持参数传入：

```
hook('func', 'abc');
```

即调用对应 hook 到所有类包含的 func 静态方法。

TIP：并不是每个方法都会被 hook，记住，hook_xxx 类中带 _ (下划线)开头的静态方法是无法调用的。

TIP：同理，您可以将不想公开的方法用 _ (下划线)开头，防止 hook cache 到过多的方法，这样既可以优化 hook 扫描时的性能，又可以避免外部访问到。

hook 方法 返回结果类型为：Array

因为在 hook 目录中，存在的同样静态方法的类会有多个，调用时每个方法都会去调用一遍，所以会以数组形式返回。

循环输出或者在模板中可用

```
{out_hook(hook('func'), '<Br>')}
```

即可。

 **小技能** 

hook 可以按文件名顺序来排序，加排序的方法：

1.hook_xxx.php

2.hook_aaa.php

3.hook_ccc.php

数字越大，越优先加载，

TIP：插件排序，非特殊情况建议不要使用~

TIP： 如果调用 hook 途中，需要中止加载其它类的调用。

请在方法中返回：


```
return array(
    // 通知 hook 系统不需要再运行其它 hook 类里的方法了

   'HOOK' => HOOK_STOP,
    // 返回的结果
   'return' => 'xxxxx',

);
```



### EnPHP 加密工具的说明

EnPHP 工具的路径：tools/enphp_project.php

EnPHP 是一款可以加密混淆 PHP 5-7 的工具，现内置于 mzphp Framework 中。
EnPHP 经多方测试已经支持加密混淆 99% 以上的 PHP 代码，欢迎测试反馈。

使用方法：

1. 将 enphp_project.php 拷至您的项目中
2. 修改 enphp 中适合你的 option 参数（选）
3. 修改 enphp_control 中 on_index 方法里需要加密的文件(选)
4. 在浏览器中访问本文件，或者使用 php enphp_project.php 命令行模式执行加密

P.S. 重要的事情说三遍：备份！备份！备份！请在加密前备份好您的源码文件，通过 EnPHP 加密的文件均无法恢复！否则请不要使用。

P.S.S. 您也可以将 enphp 类与 spider 类拷出去单独使用。

enphp 在线加密地址：

[http://enphp.djunny.com/](http://enphp.djunny.com/)

更多实例敬请期待。