<?php if(!defined('HDPHP_PATH'))exit;C('SHOW_NOTICE',FALSE);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>欢迎界面</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" src="http://localhost/PHPUnion/Static/Pintuer/jquery-1.11.0.js"></script>
<link rel="stylesheet" type="text/css" href="http://localhost/PHPUnion/Static/Pintuer/pintuer.css" />
<script type="text/javascript" src="http://localhost/PHPUnion/Static/Pintuer/pintuer.js"></script>
<script type="text/javascript" src="http://localhost/PHPUnion/Static/Pintuer/respond.js"></script>
</head>
<body>
  <div class="margin-large-top padding-left text-small height-big">
    <div>
      <div class="float-left ico-w-45"><img src="http://localhost/PHPUnion/Home/Admin/Theme/Public/images/ico_sun.png" width="32" height="32" /></div>
      <div class="float-left"><strong><?php echo $_SESSION['username'];?> <span class="timePd"></span>，欢迎使用<?php echo $hd['config']['SYSTEM_WEBNAME'];?> </strong>系统版本：<?php echo $hd['config']['SYSTEM_VERSION'];?>
      更新时间：<?php echo $hd['config']['SYSTEM_VERSION_DATE'];?></div>
      <div class="clearfix"></div>
    </div>
    <hr />
    <div class="padding-top padding-bottom">
      <div class="float-left ico-w-45"><img src="http://localhost/PHPUnion/Home/Admin/Theme/Public/images/ico_dp.png" width="32" height="32" /></div>
      <div class="float-left">
        <strong>网站系统使用指南</strong><br />
        <ol class="list-unstyle height">
          <li>您可以使用本系统上传，发布网络微电影视频，删除视频等。</li>
          <li>您可以使用本系统进行用户管理，收费播放等。</li>
          <li>您可以进行密码修改、账户设置等操作。</li>
          <li>请尽量使用支持HTML5的浏览器登录后台。</li>
        </ol>
      </div>
      <div class="clearfix"></div>
    </div>
    <hr />
    <div class="padding-top padding-bottom">
      <div class="float-left ico-w-45"><img src="http://localhost/PHPUnion/Home/Admin/Theme/Public/images/ico_tag.png" width="32" height="32" /></div>
      <div class="float-left">
        <strong>网站程序说明</strong><br />
         <ol class="height">
          <li>本程序由 <span class="text-main"><?php echo $hd['config']['SYSTEM_AUTHOR'];?></span> 开发，提供专业视频管理系统！程序免费使用。</li>
          <li>本系统为共享程序，用户自由选择是否使用，在使用中出现任何问题而造成的损失 <span class="text-main"><?php echo $hd['config']['SYSTEM_AUTHOR'];?></span> 不负任何责任！</li>
          <li>尊重作者劳动成果，希望各站长在使用时不要修改版权和制作申明！</li>
          <li>如需要更好的程序插件请联系作者 <span class="text-main"><?php echo $hd['config']['SYSTEM_AUTHOR'];?></span> 订做。</li>
          <li class="text-red">请做好数据备份，最大限度保障您的数据安全，感谢您的使用！</li>
          </ol>
      </div>
      <div class="clearfix"></div>
    </div>
    <hr />
    <div class="padding-top">
      <div class="float-left ico-w-45"><img src="http://localhost/PHPUnion/Home/Admin/Theme/Public/images/ico_user.png" width="32" height="32" /></div>
      <div class="float-left">
        <strong>程序团队</strong><br />
        <p>
          系统名称：<?php echo $hd['config']['SYSTEM_WEBNAME'];?><br />
          系统版本：<?php echo $hd['config']['SYSTEM_VERSION'];?><br />
          官方网站：<a href="<?php echo $hd['config']['SYSTEM_DOMAIN'];?>" target="_blank"><?php echo $hd['config']['SYSTEM_DOMAIN'];?></a><br />
          更新时间：<?php echo $hd['config']['SYSTEM_VERSION_DATE'];?><br />
          作者昵称：<?php echo $hd['config']['SYSTEM_AUTHOR'];?><br />
          作者QQ：<?php echo $hd['config']['SYSTEM_QQ'];?><br />
          E-mail：<?php echo $hd['config']['SYSTEM_EMAIL'];?><br />
          QQ群：<?php echo $hd['config']['SYSTEM_FLOCK'];?>&nbsp;&nbsp;<a href="" target="_blank">点击加入</a>
        </p>
      </div>
    </div>
</div>
</body>
</html>