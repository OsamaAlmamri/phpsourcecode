<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/kuaiqianbob.php');
$kuaiqianbob = new KuaiqianBob();
if($kuaiqianbob->analyzeReturn())
{
	echo "success";
}

?>