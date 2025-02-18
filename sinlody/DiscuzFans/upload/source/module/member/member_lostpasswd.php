<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_lostpasswd.php 35030 2014-10-23 07:43:23Z laoguozhang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

$discuz_action = 141;

if(submitcheck('lostpwsubmit')) {
	loaducenter();
	$_GET['email'] = strtolower(trim($_GET['email']));
	$_GET['sms'] = trim($_GET['sms']);
	if(empty($_GET['email']) && empty($_GET['sms'])){
	    showmessage('lostpasswd_emall_sms_all_not_exist');
	}
	if($_GET['email']){
	    	if($_GET['username']) {
	    		list($tmp['uid'], , $tmp['email']) = uc_get_user(addslashes($_GET['username']));
	    		$tmp['email'] = strtolower(trim($tmp['email']));
	    		if($_GET['email'] != $tmp['email']) {
	    			showmessage('getpasswd_account_notmatch');
	    		}
	    		$member = getuserbyuid($tmp['uid'], 1);
	    	} else {
	    		$emailcount = C::t('common_member')->count_by_email($_GET['email'], 1);
	    		if(!$emailcount) {
	    			showmessage('lostpasswd_email_not_exist');
	    		}
	    		if($emailcount > 1) {
	    			showmessage('lostpasswd_many_users_use_email');
	    		}
	    		$member = C::t('common_member')->fetch_by_email($_GET['email'], 1);
	    		list($tmp['uid'], , $tmp['email']) = uc_get_user(addslashes($member['username']));
	    		$tmp['email'] = strtolower(trim($tmp['email']));
	    	}
	    	if(!$member) {
	    		showmessage('getpasswd_account_notmatch');
	    	} elseif($member['adminid'] == 1 || $member['adminid'] == 2) {
	    		showmessage('getpasswd_account_invalid');
	    	}

	    	$table_ext = $member['_inarchive'] ? '_archive' : '';
	    	if($member['email'] != $tmp['email']) {
	    		C::t('common_member'.$table_ext)->update($tmp['uid'], array('email' => $tmp['email']));
	    	}

	    	$idstring = random(6);
	    	C::t('common_member_field_forum'.$table_ext)->update($member['uid'], array('authstr' => "$_G[timestamp]\t1\t$idstring"));
	    	require_once libfile('function/mail');
	    	$get_passwd_subject = lang('email', 'get_passwd_subject');
	    	$get_passwd_message = lang(
	    		'email',
	    		'get_passwd_message',
	    		array(
	    			'username' => $member['username'],
	    			'bbname' => $_G['setting']['bbname'],
	    			'siteurl' => $_G['siteurl'],
	    			'uid' => $member['uid'],
	    			'idstring' => $idstring,
	    			'clientip' => $_G['clientip'],
	    			'sign' => make_getpws_sign($member['uid'], $idstring),
	    		)
	    	);
	    	if(!sendmail("$_GET[username] <$tmp[email]>", $get_passwd_subject, $get_passwd_message)) {
	    		runlog('sendmail', "$tmp[email] sendmail failed.");
	    	}
	    	showmessage('getpasswd_send_succeed', $_G['siteurl'], array(), array('showdialog' => 1, 'locationtime' => true));
	}else{
	    if($_GET['username']) {
	        list($tmp['uid'], , $tmp['sms']) = uc_get_user(addslashes($_GET['username']));
	        $tmp['sms'] = trim($tmp['sms']);
	        if($_GET['sms'] != $tmp['sms']) {
	            showmessage('getpasswd_account_notmatch');
	        }
	        $member = getuserbyuid($tmp['uid'], 1);
	    }else{
	        $smscount = C::t('common_member')->count_by_sms($_GET['sms'], 1);
	        if(!$smscount) {
	            showmessage('lostpasswd_sms_not_exist');
	        }
	        if($smscount > 1) {
	            showmessage('lostpasswd_many_users_use_sms');
	        }
	        $member = C::t('common_member')->fetch_by_sms($_GET['sms'], 1);
	        list($tmp['uid'], , , $tmp['sms']) = uc_get_user(addslashes($member['username']));
	        $tmp['sms'] = trim($tmp['sms']);
	    }
	    if(!$member) {
	        showmessage('getpasswd_account_notmatch');
	    } elseif($member['adminid'] == 1 || $member['adminid'] == 2) {
	        showmessage('getpasswd_account_invalid');
	    }

	    $table_ext = $member['_inarchive'] ? '_archive' : '';
	    if($member['sms'] != $tmp['sms']) {
	        C::t('common_member'.$table_ext)->update($tmp['uid'], array('sms' => $tmp['sms']));
	    }

	    $pwd = random(6);
	    $res = uc_user_edit($member['username'], '', $pwd, '', '', 1, 0);
	    if($res == -10){
	        showmessage('getpasswd_account_invalid');
	    }

	    $smsval = array(
	        'username' => addslashes($_GET['username']),
	        'newpass' => $pwd,
	        'bbname' => $_G['setting']['bbname'],
	        'sitename' => $_G['setting']['sitename'],
	        'siteurl' => $_G['setting']['siteurl'],
	    );

	    $smsconfig = dunserialize($_G['setting']['sms']);
	    $smstemp = $smsconfig['template']['get_passwd'];
	    $sms = $_GET['sms'];
	    require_once libfile('function/sms');
	    if(!sendsms($sms, $smsval, $smstemp)) {
	        runlog('sendsms', "$sms sendsms failed.");
	    }

	    showmessage('getpasswd_send_newpass', $_G['siteurl'], array(), array('showdialog' => 1, 'locationtime' => true));
	}
}

?>