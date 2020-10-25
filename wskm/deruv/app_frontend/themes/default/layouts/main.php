<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use service\Block;
use common\widgets\Alert;

frontend\assets\AppAsset::register($this);

if (!isset($this->metaTags['keywords'])) {
    $this->registerMetaTag([
        'name' => 'keywords',
        'content' => \service\Setting::getParamConf('webKeywords')
    ]);
}

if (!isset($this->metaTags['description'])) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => \service\Setting::getParamConf('webDescription')
    ]);
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | <?= Html::encode(\service\Setting::getParamConf('webName')) ?></title>
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <?php $this->head() ?>
	<link rel="stylesheet" href="./themes/default/css/home.css" />		
</head>
<body>
<?php $this->beginBody() ?>

	<div class="head" >
		<div class="container-fluid dnav " >
			<!--
			<div class="col-xs-1" style="z-index: 99;" >
				<div class="logo "  >
					<h1>
						<a href="" class="bg" >web</a>
					</h1>
				</div>
			</div>
			-->
			<nav class="navbar navbar-inverse " role="navigation" style="background-color: #0c0c0c;border: none;" >
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-navbar-header">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <h1><a class="navbar-brand" href="<?= Url::home(); ?>" style="padding:0;color:transparent;" ><img alt="Brand" src="./themes/default/img/logo.png" height="50" >&nbsp;</a></h1>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="site-navbar-header">
				    <ul class="nav navbar-nav">
						<li class="active"><a href="<?= Url::home(); ?>"><?= \Wskm::t('Home') ?> <span class="sr-only">(current)</span></a></li>
						<?php $navs = Block::shows('nav'); ?>
						<?php foreach($navs as $v){ ?>
						<li ><a href="<?= $v['url'] ?>"><?= $v['title'] ?></a></li>
						<?php } ?>
				    </ul>
				    <form class="navbar-form navbar-right" role="search" action="<?= Wskm::Url(['/search']) ?>" method="get" >
						<input type="hidden" name="r" value="search" >
						<div class="form-group">
							<input type="text" name="query" value="<?= Yii::$app->formatter->asText(Wskm::viewVal('query')) ?>" class="form-control" placeholder="<?= Wskm::t('Search') ?>">
						</div>
						<button type="submit" class="btn btn-default" style="border: none;background: transparent;color: #fff;" >
						  <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
				    </form>
					<ul class="nav navbar-nav navbar-right">
						<li style="background-color: maroon;display:none" id="site-login" ><a href="<?= Wskm::url(['/site/login']) ?>"><?= \Wskm::t('Login') ?></a></li>
						<li id="site-signup"  style="display:none"  ><a href="<?= Wskm::url(['/site/signup']) ?>"><?= \Wskm::t('Signup') ?></a></li>
						<li class="dropdown" id="site-user" style="display:none"  >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span id='user-name'></span> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							  <li><a href="<?= Wskm::url(['/user']) ?>"><?= Wskm::t('Profile') ?></a></li>
							  <li class="divider"></li>
							  <li><a href="<?= Wskm::url(['/site/logout']) ?>"><?= \Wskm::t('Logout') ?></a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</nav>

		</div>
	</div>
	<div class="main-wrap" >
		<div class="container-fluid main" id="site-main" >
			<?= Alert::widget() ?>
			<?= $content ?>
		</div>
	</div>
	<div class="container-fluid foot"   >
		<div class="foot-main" >
			&copy; <?= \service\Setting::getParamConf('webName').'&nbsp;'.date('Y') ?><!--&nbsp;&nbsp;|&nbsp;&nbsp;SiteMap -->
		</div>
	</div>
	
	<div id="sidepanel" style="display:none" >
		<a href="javascript:;" class="glyphicon glyphicon-circle-arrow-up" ></a>
	</div>
	<script src="./themes/default/js/site.js"></script>
	<script>
		$(function(){
			getUser('<?= Wskm::url(['site/userinfo']) ?>');
			
			stat('<?= Wskm::url(['/stat']) ?>');
		});
	</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
