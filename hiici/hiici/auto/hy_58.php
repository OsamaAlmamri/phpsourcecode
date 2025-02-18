<?php

require_once('pub_inc.php');		//公共包含

$hy_58 = 'http://hy.58.com/job/';
for ($i = 1; $i <= 25; $i++) {
	$html_h = file_get_contents_rand_ip($hy_58.'pn'.$i.'/');
	if (preg_match_all('/name="zaixian_([^"]*)"/', $html_h, $m)) {
		foreach ($m[1] as $h_id) {
			do_pick_content($h_id);
		}
	} 
}
return true;

function do_pick_content($h_id) {
	$forum_id = 14;

	//避免频繁更新
	$topic = get_topic_by_order_l_n($h_id, $forum_id);
	if (check_topic_live($topic)) return true; 

	$html_c = file_get_contents_rand_ip('http://qy.58.com/'.$h_id);

	if (preg_match('/<h1>[^<]*<a href="[^"]*" title="[^"]*">([^<]*)<\/a>[^<]*<\/h1>/', $html_c, $m)) {
		$c_name = $m[1];
	} else if (preg_match('/<div class="compTit clearfix">[^<]*<h3>([^<]*)<\/h3>/', $html_c, $m)) {
		$c_name = $m[1];
	} 

	//提取内容
	if (preg_match('/<img src="[^"]*">/', $html_c, $m)) {
		$tel = $m[0];
	} else return false;

	if (preg_match('/<div class="compIntro">([^`]*)<div class="compAppear">/', $html_c, $m)) {
		$compIntro = preg_replace('/ style=[^>]*>/', '>', $m[1]);
	} 

	if (preg_match('/<table class="jobList">.*/', $html_c, $m)) {
		$job = preg_replace('/href="[^"]*"/', '', $m[0]);
	}

	if (preg_match('/<td class="adress"[^>]*>[^<]*<[^>]*>([^<]*)<\/[^>]*>/', $html_c, $m)) {
		$adr = $m[1];
	}

	//标题
	$title = '【'.trim($c_name).'】';
	preg_match_all('/<a target="_blank"[^>]*>([^<]*)<\/a>/', $html_c, $m);
	foreach ($m[1] as $t) {
		$title = $title.$t.'、';
	}
	$content = $job.'<hr>'.$tel.'<hr>'.$adr.'<hr>'.$compIntro.'<br><br>';

	$start_t_s = time()+15*24*3600;

	do_topic_add_x($forum_id, $title, $content, $start_t_s, null, $h_id, $topic['id']);

	return true;
}
