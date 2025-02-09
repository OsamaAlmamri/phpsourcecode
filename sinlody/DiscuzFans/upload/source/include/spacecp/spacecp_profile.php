<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 34668 2014-06-23 08:11:09Z hypowang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$defaultop = '';
	$profilegroup = C::t('common_setting')->fetch('profilegroup', true);
	foreach($profilegroup as $key => $value) {
		if($value['available']) {
			$defaultop = $key;
			break;
		}
	}

$operation = in_array($_GET['op'], array('base', 'contact', 'edu', 'work', 'info', 'password', 'verify')) ? trim($_GET['op']) : $defaultop;
$space = getuserbyuid($_G['uid']);
space_merge($space, 'field_home');
space_merge($space, 'profile');

list($seccodecheck, $secqaacheck) = seccheck('password');
@include_once DISCUZ_ROOT.'./data/cache/cache_domain.php';
$spacedomain = isset($rootdomain['home']) && $rootdomain['home'] ? $rootdomain['home'] : array();
$_GET['id'] = $_GET['id'] ? preg_replace("/[^A-Za-z0-9_:]/", '', $_GET['id']) : '';
if($operation != 'password') {

	include_once libfile('function/profile');

	loadcache('profilesetting');
	if(empty($_G['cache']['profilesetting'])) {
		require_once libfile('function/cache');
		updatecache('profilesetting');
		loadcache('profilesetting');
	}
}

$allowcstatus = !empty($_G['group']['allowcstatus']) ? true : false;
$verify = C::t('common_member_verify')->fetch($_G['uid']);
$validate = array();
if($_G['setting']['regverify'] == 2 && $_G['groupid'] == 8) {
	$validate = C::t('common_member_validate')->fetch($_G['uid']);
	if(empty($validate) || $validate['status'] != 1) {
		$validate = array();
	}
}
if($_G['setting']['connect']['allow']) {
	$connect = C::t('#qqconnect#common_member_connect')->fetch($_G['uid']);
	$conisregister = $operation == 'password' && $connect['conisregister'];
}

if(in_array('wechat', $_G['setting']['plugins']['available'])) {
	if($_G['wechat']['setting']['wechat_qrtype']) {
		$wechatuser = C::t('#wechat#common_member_wechatmp')->fetch($_G['uid']);
		if($wechatuser && !$wechatuser['status']) {
			$wechatuser['isregister'] = 1;
		}
	} else {
		$wechatuser = C::t('#wechat#common_member_wechat')->fetch($_G['uid']);
	}

	$conisregister = $operation == 'password' && $wechatuser['isregister'];
}

if (in_array('qq', $_G['setting']['plugins']['available'])) {

    $qquser = C::t('#qq#qq_member')->fetch($_G['uid']);
    $conisregister = $operation == 'password' && $qquser['conisregister'];
}
if(submitcheck('profilesubmit')) {

	require_once libfile('function/discuzcode');

	$forum = $setarr = $verifyarr = $errorarr = array();
	$forumfield = array('customstatus', 'sightml');

	$censor = discuz_censor::instance();

	if($_GET['vid']) {
		$vid = intval($_GET['vid']);
		$verifyconfig = $_G['setting']['verify'][$vid];
		if($verifyconfig['available'] && (empty($verifyconfig['groupid']) || in_array($_G['groupid'], $verifyconfig['groupid']))) {
			$verifyinfo = C::t('common_member_verify_info')->fetch_by_uid_verifytype($_G['uid'], $vid);
			if(!empty($verifyinfo)) {
				$verifyinfo['field'] = dunserialize($verifyinfo['field']);
			}
			foreach($verifyconfig['field'] as $key => $field) {
				if(!isset($verifyinfo['field'][$key])) {
					$verifyinfo['field'][$key] = $key;
				}
			}
		} else {
			$_GET['vid'] = $vid = 0;
			$verifyconfig = array();
		}
	}
	if(isset($_POST['birthprovince'])) {
		$initcity = array('birthprovince', 'birthcity', 'birthdist', 'birthcommunity');
		foreach($initcity as $key) {
			$_GET[''.$key] = $_POST[$key] = !empty($_POST[$key]) ? $_POST[$key] : '';
		}
	}
	if(isset($_POST['resideprovince'])) {
		$initcity = array('resideprovince', 'residecity', 'residedist', 'residecommunity');
		foreach($initcity as $key) {
			$_GET[''.$key] = $_POST[$key] = !empty($_POST[$key]) ? $_POST[$key] : '';
		}
	}
	foreach($_POST as $key => $value) {
		$field = $_G['cache']['profilesetting'][$key];
		if(in_array($field['formtype'], array('text', 'textarea')) || in_array($key, $forumfield)) {
			$censor->check($value);
			if($censor->modbanned() || $censor->modmoderated()) {
				profile_showerror($key, lang('spacecp', 'profile_censor'));
			}
		}
		if(in_array($key, $forumfield)) {
			if($key == 'sightml') {
				loadcache(array('smilies', 'smileytypes'));
				$value = cutstr($value, $_G['group']['maxsigsize'], '');
				foreach($_G['cache']['smilies']['replacearray'] AS $skey => $smiley) {
					$_G['cache']['smilies']['replacearray'][$skey] = '[img]'.$_G['siteurl'].'static/image/smiley/'.$_G['cache']['smileytypes'][$_G['cache']['smilies']['typearray'][$skey]]['directory'].'/'.$smiley.'[/img]';
				}
				$value = preg_replace($_G['cache']['smilies']['searcharray'], $_G['cache']['smilies']['replacearray'], trim($value));
				$forum[$key] = discuzcode($value, 1, 0, 0, 0, $_G['group']['allowsigbbcode'], $_G['group']['allowsigimgcode'], 0, 0, 1);
			} elseif($key=='customstatus' && $allowcstatus) {
				$forum[$key] = dhtmlspecialchars(trim($value));
			}
			continue;
		} elseif($field && !$field['available']) {
			continue;
		} elseif($key == 'timeoffset') {
			if($value >= -12 && $value <= 12 || $value == 9999) {
				C::t('common_member')->update($_G['uid'], array('timeoffset' => intval($value)));
			}
		} elseif($key == 'site') {
			if(!in_array(strtolower(substr($value, 0, 6)), array('http:/', 'https:', 'ftp://', 'rtsp:/', 'mms://')) && !preg_match('/^static\//', $value) && !preg_match('/^data\//', $value)) {
				$value = 'http://'.$value;
			}
		}
		if($field['formtype'] == 'file') {
			if((!empty($_FILES[$key]) && $_FILES[$key]['error'] == 0) || (!empty($space[$key]) && empty($_GET['deletefile'][$key]))) {
				$value = '1';
			} else {
				$value = '';
			}
		}
		if(empty($field)) {
			continue;
		} elseif(profile_check($key, $value, $space)) {
			$setarr[$key] = dhtmlspecialchars(trim($value));
		} else {
			if($key=='birthprovince') {
				$key = 'birthcity';
			} elseif($key=='resideprovince' || $key=='residecommunity'||$key=='residedist') {
				$key = 'residecity';
			} elseif($key=='birthyear' || $key=='birthmonth') {
				$key = 'birthday';
			}
			profile_showerror($key);
		}
		if($field['formtype'] == 'file') {
			unset($setarr[$key]);
		}
		if($vid && $verifyconfig['available'] && isset($verifyconfig['field'][$key])) {
			if(isset($verifyinfo['field'][$key]) && $setarr[$key] !== $space[$key]) {
				$verifyarr[$key] = $setarr[$key];
			}
			unset($setarr[$key]);
		}
		if(isset($setarr[$key]) && $_G['cache']['profilesetting'][$key]['needverify']) {
			if($setarr[$key] !== $space[$key]) {
				$verifyarr[$key] = $setarr[$key];
			}
			unset($setarr[$key]);
		}
	}
	if($_GET['deletefile'] && is_array($_GET['deletefile'])) {
		foreach($_GET['deletefile'] as $key => $value) {
			if(isset($_G['cache']['profilesetting'][$key]) && $_G['cache']['profilesetting'][$key]['formtype'] == 'file') {
				@unlink(getglobal('setting/attachdir').'./profile/'.$space[$key]);
				@unlink(getglobal('setting/attachdir').'./profile/'.$verifyinfo['field'][$key]);
				$verifyarr[$key] = $setarr[$key] = '';
			}
		}
	}
	if($_FILES) {
		$upload = new discuz_upload();
		foreach($_FILES as $key => $file) {
			if(!isset($_G['cache']['profilesetting'][$key])) {
				continue;
			}
			$field = $_G['cache']['profilesetting'][$key];
			if((!empty($file) && $file['error'] == 0) || (!empty($space[$key]) && empty($_GET['deletefile'][$key]))) {
				$value = '1';
			} else {
				$value = '';
			}
			if(!profile_check($key, $value, $space)) {
				profile_showerror($key);
			} elseif($field['size'] && $field['size']*1024 < $file['size']) {
				profile_showerror($key, lang('spacecp', 'filesize_lessthan').$field['size'].'KB');
			}
			$upload->init($file, 'profile');
			$attach = $upload->attach;

			if(!$upload->error()) {
				$upload->save();

				if(!$upload->get_image_info($attach['target'])) {
					@unlink($attach['target']);
					continue;
				}
				$setarr[$key] = '';
				$attach['attachment'] = dhtmlspecialchars(trim($attach['attachment']));
				if($vid && $verifyconfig['available'] && isset($verifyconfig['field'][$key])) {
					if(isset($verifyinfo['field'][$key])) {
						@unlink(getglobal('setting/attachdir').'./profile/'.$verifyinfo['field'][$key]);
						$verifyarr[$key] = $attach['attachment'];
					}
					continue;
				}
				if(isset($setarr[$key]) && $_G['cache']['profilesetting'][$key]['needverify']) {
					@unlink(getglobal('setting/attachdir').'./profile/'.$verifyinfo['field'][$key]);
					$verifyarr[$key] = $attach['attachment'];
					continue;
				}
				@unlink(getglobal('setting/attachdir').'./profile/'.$space[$key]);
				$setarr[$key] = $attach['attachment'];
			}

		}
	}
	if($vid && !empty($verifyinfo['field']) && is_array($verifyinfo['field'])) {
		foreach($verifyinfo['field'] as $key => $fvalue) {
			if(!isset($verifyconfig['field'][$key])) {
				unset($verifyinfo['field'][$key]);
				continue;
			}
			if(empty($verifyarr[$key]) && !isset($verifyarr[$key]) && isset($verifyinfo['field'][$key])) {
				$verifyarr[$key] = !empty($fvalue) && $key != $fvalue ? $fvalue : $space[$key];
			}
		}
	}
	if($forum) {
		if(!$_G['group']['maxsigsize']) {
			$forum['sightml'] = '';
		}
		C::t('common_member_field_forum')->update($_G['uid'], $forum);

	}

	if(isset($_POST['birthmonth']) && ($space['birthmonth'] != $_POST['birthmonth'] || $space['birthday'] != $_POST['birthday'])) {
		$setarr['constellation'] = get_constellation($_POST['birthmonth'], $_POST['birthday']);
	}
	if(isset($_POST['birthyear']) && $space['birthyear'] != $_POST['birthyear']) {
		$setarr['zodiac'] = get_zodiac($_POST['birthyear']);
	}
	if($setarr) {
		C::t('common_member_profile')->update($_G['uid'], $setarr);
	}

	if($verifyarr) {
		C::t('common_member_verify_info')->delete_by_uid($_G['uid'], $vid);
		$setverify = array(
				'uid' => $_G['uid'],
				'username' => $_G['username'],
				'verifytype' => $vid,
				'field' => serialize($verifyarr),
				'dateline' => $_G['timestamp']
			);

		C::t('common_member_verify_info')->insert($setverify);
		if(!(C::t('common_member_verify')->count_by_uid($_G['uid']))) {
			C::t('common_member_verify')->insert(array('uid' => $_G['uid']));
		}
		if($_G['setting']['verify'][$vid]['available']) {
			manage_addnotify('verify_'.$vid, 0, array('langkey' => 'manage_verify_field', 'verifyname' => $_G['setting']['verify'][$vid]['title'], 'doid' => $vid));
		}
	}

	if(isset($_POST['privacy'])) {
		foreach($_POST['privacy'] as $key=>$value) {
			if(isset($_G['cache']['profilesetting'][$key])) {
				$space['privacy']['profile'][$key] = intval($value);
			}
		}
		C::t('common_member_field_home')->update($space['uid'], array('privacy'=>serialize($space['privacy'])));
	}

	manyoulog('user', $_G['uid'], 'update');

	include_once libfile('function/feed');
	feed_add('profile', 'feed_profile_update_'.$operation, array('hash_data'=>'profile'));
	countprofileprogress();
	$message = $vid ? lang('spacecp', 'profile_verify_verifying', array('verify' => $verifyconfig['title'])) : '';
	profile_showsuccess($message);

} elseif(submitcheck('passwordsubmit', 0, $seccodecheck, $secqaacheck)) {

	$membersql = $memberfieldsql = $authstradd1 = $authstradd2 = $newpasswdadd = '';
	$setarr = array();
	$emailnew = dhtmlspecialchars($_GET['emailnew']);
	$smsnew = dhtmlspecialchars($_GET['smsnew']);
	$ignorepassword = 0;
	if($_G['setting']['connect']['allow']) {
        if ($qquser['uid']) {
            $connect = C::t('#qq#qq_member')->fetch($_G['uid']);
        } else {
            $connect = C::t('#qqconnect#common_member_connect')->fetch($_G['uid']);
        }
		if($connect['conisregister']) {
			$_GET['oldpassword'] = '';
			$ignorepassword = 1;
			if(empty($_GET['newpassword'])) {
				showmessage('profile_passwd_empty');
			}
		}
	}

	if(in_array('mobile', $_G['setting']['plugins']['available']) && $wechatuser['isregister']) {
		$_GET['oldpassword'] = '';
		$ignorepassword = 1;
		if(empty($_GET['newpassword'])) {
			showmessage('profile_passwd_empty');
		}
	}

	if($_GET['questionidnew'] === '') {
		$_GET['questionidnew'] = $_GET['answernew'] = '';
	} else {
		$secquesnew = $_GET['questionidnew'] > 0 ? random(8) : '';
	}

	if(!empty($_GET['newpassword']) && $_G['setting']['strongpw']) {
		$strongpw_str = array();
		if(in_array(1, $_G['setting']['strongpw']) && !preg_match("/\d+/", $_GET['newpassword'])) {
			$strongpw_str[] = lang('member/template', 'strongpw_1');
		}
		if(in_array(2, $_G['setting']['strongpw']) && !preg_match("/[a-z]+/", $_GET['newpassword'])) {
			$strongpw_str[] = lang('member/template', 'strongpw_2');
		}
		if(in_array(3, $_G['setting']['strongpw']) && !preg_match("/[A-Z]+/", $_GET['newpassword'])) {
			$strongpw_str[] = lang('member/template', 'strongpw_3');
		}
		if(in_array(4, $_G['setting']['strongpw']) && !preg_match("/[^a-zA-z0-9]+/", $_GET['newpassword'])) {
			$strongpw_str[] = lang('member/template', 'strongpw_4');
		}
		if($strongpw_str) {
			showmessage(lang('member/template', 'password_weak').implode(',', $strongpw_str));
		}
	}
	if(!empty($_GET['newpassword']) && $_GET['newpassword'] != addslashes($_GET['newpassword'])) {
		showmessage('profile_passwd_illegal', '', array(), array('return' => true));
	}
	if(!empty($_GET['newpassword']) && $_GET['newpassword'] != $_GET['newpassword2']) {
		showmessage('profile_passwd_notmatch', '', array(), array('return' => true));
	}

	loaducenter();
	if($emailnew != $_G['member']['email']) {
		include_once libfile('function/member');
		checkemail($emailnew);
	}
	if($smsnew != $_G['member']['sms']) {
		include_once libfile('function/member');
		checksms($smsnew);
	}

	$newsms = getcookie('newsms');
	if(!empty($newsms)) {
	    $smsinfo = explode("\t", $newsms);
	    $newsms = $smsinfo[0] == $_G['uid'] && issms($smsinfo[1]) ? $smsinfo[1] : '';
	}
	$smssave = '';
	if(!empty($newsms) && !empty($_GET['smscode'])){
	    if(check_smscode($_GET['smscode'], $newsms)){
	        $_G['member']['sms'] = $smssave = $smsnew = $newsms;
	        $setarr['sms'] = $newsms;
	        $setarr['smsstatus'] = 1;
	        if($_G['setting']['regverify'] == 1 && $space['groupid'] == 8) {
	            $membergroup = C::t('common_usergroup')->fetch_by_credits($space['credits']);
	            $setarr['groupid'] = $membergroup['groupid'];
	        }
	        updatecreditbyaction('realsms', $space['uid']);
	        dsetcookie('newsms', "", -1);
	    }else{
	        showmessage('profile_smscode_notmatch', '', array(), array('return' => true));
	    }
	}
	$ucresult = uc_user_edit_new(addslashes($_G['username']), $_GET['oldpassword'], $_GET['newpassword'], '', $smssave, $ignorepassword, $_GET['questionidnew'], $_GET['answernew']);
	if($ucresult == -1) {
		showmessage('profile_passwd_wrong', '', array(), array('return' => true));
	} elseif($ucresult == -4) {
		showmessage('profile_email_illegal', '', array(), array('return' => true));
	} elseif($ucresult == -5) {
		showmessage('profile_email_domain_illegal', '', array(), array('return' => true));
	} elseif($ucresult == -6) {
		showmessage('profile_email_duplicate', '', array(), array('return' => true));
	} elseif($ucresult == -7) {
		showmessage('profile_sms_illegal', '', array(), array('return' => true));
	} elseif($ucresult == -8) {
		showmessage('profile_sms_duplicate', '', array(), array('return' => true));
	}

	$loginData = C::t('common_member_login')->fetch($space['uid']);
	$loginName = $loginData['loginname'];
	$loginNameEnc = $loginName ? mb_substr($loginName, 0, 1, CHARSET).'******'.mb_substr($loginName, mb_strlen($loginName, CHARSET)-1, 1, CHARSET) : '';

	if($_GET['loginname'] != $loginNameEnc) {
		if($_GET['loginname']) {
			if(strlen($_GET['loginname']) < 3) {
				showmessage('profile_loginname_tooshort', '', array(), array('return' => true));
			}
			if(is_numeric($_GET['loginname'])) {
				showmessage('profile_loginname_is_numeric', '', array(), array('return' => true));
			}
			if(C::t('common_member_login')->checkExists($_GET['loginname'], $space['uid']) || C::t('common_member')->fetch_by_username($_GET['loginname'], 1)) {
				showmessage('profile_loginname_exists', '', array(), array('return' => true));
			}
			C::t('common_member_login')->insert(array('uid' => $space['uid'], 'loginname' => $_GET['loginname']), false, true);
		} else {
			C::t('common_member_login')->delete($space['uid']);
		}
	}

	if(!empty($_GET['newpassword']) || $secquesnew) {
		$setarr['password'] = md5(random(10));
	}
	if ($qquser['uid']) {
        if ($_G['setting']['connect']['allow']) {
            C::t('#qq#qq_member')->update($_G['uid'], array('conisregister' => 0));
        }
    } else {
        if ($_G['setting']['connect']['allow']) {
            C::t('#qqconnect#common_member_connect')->update($_G['uid'], array('conisregister' => 0));
        }
    }

	if(in_array('mobile', $_G['setting']['plugins']['available']) && $wechatuser['isregister']) {
		C::t('#wechat#common_member_wechat')->update($_G['uid'], array('isregister' => 0));
	}

	$authstr = 0;
	if($emailnew != $_G['member']['email']) {
		$authstr = 1;
		emailcheck_send($space['uid'], $emailnew);
		dsetcookie('newemail', "$space[uid]\t$emailnew\t$_G[timestamp]", 31536000);
	}
	if($smsnew != $_G['member']['sms']) {
	    $authstr = 2;
	    smscheck_send($space['uid'], $smsnew);
	    dsetcookie('newsms', "$space[uid]\t$smsnew\t$_G[timestamp]", 31536000);
	}
	if($setarr) {
		if($_G['member']['freeze'] == 1) {
			$setarr['freeze'] = 0;
		}
		C::t('common_member')->update($_G['uid'], $setarr);
	}
	if($_G['member']['freeze'] == 2) {
		C::t('common_member_validate')->update($_G['uid'], array('message' => dhtmlspecialchars($_G['gp_freezereson'])));
	}

	if($authstr == 1) {
		showmessage('profile_email_verify', 'home.php?mod=spacecp&ac=profile&op=password');
	} elseif ($authstr == 2) {
		showmessage('profile_sms_verify', 'home.php?mod=spacecp&ac=profile&op=password');
	} else {
		showmessage('profile_succeed', 'home.php?mod=spacecp&ac=profile&op=password');
	}
}

if($operation == 'password') {

	$resend1 = getcookie('resendemail');
	$resend1 = empty($resend1) ? true : (TIMESTAMP - $resend1) > 300;
	$newemail = getcookie('newemail');
	$space['newemail'] = !$space['emailstatus'] ? $space['email'] : '';
	if(!empty($newemail)) {
		$mailinfo = explode("\t", $newemail);
		$space['newemail'] = $mailinfo[0] == $_G['uid'] && isemail($mailinfo[1]) ? $mailinfo[1] : '';
	}
	$resend2 = getcookie('resendsms');
	$resend2 = empty($resend2) ? true : (TIMESTAMP - $resend2) > 300;
	$newsms = getcookie('newsms');
	$space['newsms'] = !$space['smsstatus'] ? $space['sms'] : '';
	if(!empty($newsms)) {
		$smsinfo = explode("\t", $newsms);
		$space['newsms'] = $smsinfo[0] == $_G['uid'] && issms($smsinfo[1]) ? $smsinfo[1] : '';
	}

	if($_GET['resend'] && $resend1) {
		$toemail = $space['newemail'] ? $space['newemail'] : $space['email'];
		emailcheck_send($space['uid'], $toemail);
		dsetcookie('newemail', "$space[uid]\t$toemail\t$_G[timestamp]", 31536000);
		dsetcookie('resendemail', TIMESTAMP);
		showmessage('send_activate_mail_succeed', "home.php?mod=spacecp&ac=profile&op=password");
	} elseif($_GET['resend'] && $resend2) {
		$tosms = $space['newsms'] ? $space['newsms'] : $space['sms'];
		smscheck_send($space['uid'], $tosms);
		dsetcookie('newsms', "$space[uid]\t$tosms\t$_G[timestamp]", 31536000);
		dsetcookie('resendsms', TIMESTAMP);
		showmessage('send_activate_sms_succeed', "home.php?mod=spacecp&ac=profile&op=password");
	} elseif ($_GET['resend'] == 1) {
		showmessage('send_activate_mail_error', "home.php?mod=spacecp&ac=profile&op=password");
	} elseif ($_GET['resend'] == 2) {
		showmessage('send_activate_sms_error', "home.php?mod=spacecp&ac=profile&op=password");
	}
	if(!empty($space['newemail'])) {
		$acitvemessage1 = lang('spacecp', 'email_acitve_message', array('newemail' => $space['newemail'], 'imgdir' => $_G['style']['imgdir']));
	}
	if(!empty($space['newsms'])) {
		$acitvemessage2 = lang('spacecp', 'sms_acitve_message', array('newsms' => $space['newsms'], 'imgdir' => $_G['style']['imgdir']));
	}
	$actives = array('password' =>' class="a"');
	$navtitle = lang('core', 'title_password_security');
	if($_G['member']['freeze'] == 2) {
		$fzvalidate = C::t('common_member_validate')->fetch($space['uid']);
		$space['freezereson'] = $fzvalidate['message'];
	}

	$loginData = C::t('common_member_login')->fetch($space['uid']);
	$loginName = $loginData['loginname'];
	$loginNameEnc = $loginName ? mb_substr($loginName, 0, 1, CHARSET).'******'.mb_substr($loginName, mb_strlen($loginName, CHARSET)-1, 1, CHARSET) : '';
} else {

	space_merge($space, 'field_home');
	space_merge($space, 'field_forum');

	require_once libfile('function/editor');
	$space['sightml'] = html2bbcode($space['sightml']);

	$vid = $_GET['vid'] ? intval($_GET['vid']) : 0;

	$privacy = $space['privacy']['profile'] ? $space['privacy']['profile'] : array();
	$_G['setting']['privacy'] = $_G['setting']['privacy'] ? $_G['setting']['privacy'] : array();
	$_G['setting']['privacy'] = is_array($_G['setting']['privacy']) ? $_G['setting']['privacy'] : dunserialize($_G['setting']['privacy']);
	$_G['setting']['privacy']['profile'] = !empty($_G['setting']['privacy']['profile']) ? $_G['setting']['privacy']['profile'] : array();
	$privacy = array_merge($_G['setting']['privacy']['profile'], $privacy);

	$actives = array('profile' =>' class="a"');
	$opactives = array($operation =>' class="a"');
	$allowitems = array();
	if(in_array($operation, array('base', 'contact', 'edu', 'work', 'info'))) {
		$allowitems = $profilegroup[$operation]['field'];
	} elseif($operation == 'verify') {
		if($vid == 0) {
			foreach($_G['setting']['verify'] as $key => $setting) {
				if($setting['available'] && (empty($setting['groupid']) || in_array($_G['groupid'], $setting['groupid']))) {
					$_GET['vid'] = $vid = $key;
					break;
				}
			}
		}

		if(empty($_G['setting']['verify'][$vid]['groupid']) || in_array($_G['groupid'], $_G['setting']['verify'][$vid]['groupid'])) {
			$actives = array('verify' =>' class="a"');
			$opactives = array($operation.$vid =>' class="a"');
			$allowitems = $_G['setting']['verify'][$vid]['field'];
		}
	}
	$showbtn = ($vid && $verify['verify'.$vid] != 1) || empty($vid);
	if(!empty($verify) && is_array($verify)) {
		foreach($verify as $key => $flag) {
			if(in_array($key, array('verify1', 'verify2', 'verify3', 'verify4', 'verify5', 'verify6', 'verify7')) && $flag == 1) {
				$verifyid = intval(substr($key, -1, 1));
				if($_G['setting']['verify'][$verifyid]['available']) {
					foreach($_G['setting']['verify'][$verifyid]['field'] as $field) {
						$_G['cache']['profilesetting'][$field]['unchangeable'] = 1;
					}
				}
			}
		}
	}
	if($vid) {
		if($value = C::t('common_member_verify_info')->fetch_by_uid_verifytype($_G['uid'], $vid)) {
			$field = dunserialize($value['field']);
			foreach($field as $key => $fvalue) {
				$space[$key] = $fvalue;
			}
		}
	}
	$htmls = $settings = array();
	foreach($allowitems as $fieldid) {
		if(!in_array($fieldid, array('sightml', 'customstatus', 'timeoffset'))) {
			$html = profile_setting($fieldid, $space, $vid ? false : true);
			if($html) {
				$settings[$fieldid] = $_G['cache']['profilesetting'][$fieldid];
				$htmls[$fieldid] = $html;
			}
		}
	}

}

include template("home/spacecp_profile");

function profile_showerror($key, $extrainfo = '') {
	echo '<script>';
	echo 'parent.show_error("'.$key.'", "'.$extrainfo.'");';
	echo '</script>';
	exit();
}

function profile_showsuccess($message = '') {
	echo '<script type="text/javascript">';
	echo "parent.show_success('$message');";
	echo '</script>';
	exit();
}

?>