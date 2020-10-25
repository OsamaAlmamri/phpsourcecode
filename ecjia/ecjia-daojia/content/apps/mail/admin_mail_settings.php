<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

class admin_mail_settings extends ecjia_admin {
	private $db;
	
	public function __construct() {
		parent::__construct();
		$this->db = RC_Loader::load_model('shop_config_model');
		
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		
		RC_Script::enqueue_script('mail_settings', RC_App::apps_url('statics/js/mail_settings.js', __FILE__), array(), false, 1);

        RC_Script::localize_script('mail_settings', 'mail_settings', config('app-mail::jslang.mail_settings_page'));
    }
	
	/**
	 * 邮件服务器设置
	 */
	public function init() {
		$this->admin_priv('mail_settings_manage');
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('邮件服务器设置', 'mail')));
		$this->assign('ur_here',      __('邮件服务器设置', 'mail'));
		$this->assign('ur_heretest',      __('测试邮件', 'mail'));
		
		ecjia_screen::get_current_screen()->add_help_tab( array(
		'id'        => 'overview',
		'title'     => __('概述', 'mail'),
		'content'   =>
		'<p>' . __('欢迎访问ECJia智能后台邮件服务器设置页面，可通过以下两种方式进行配置。', 'mail') . '</p>'
		) );
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . __('更多信息：', 'mail') . '</strong></p>' .
		'<p><a href="https://ecjia.com/wiki/帮助:ECJia智能后台:系统设置#.E9.82.AE.E4.BB.B6.E6.9C.8D.E5.8A.A1.E5.99.A8.E8.AE.BE.E7.BD.AE" target="_blank">' . __('关于邮件服务器设置帮助文档', 'mail') . '</a></p>'
		);
	
		$items = \Ecjia\App\Setting\ShopConfigAdminSetting::make()->load_items('smtp');
		$this->assign('items', $items);
		
		$this->assign('form_action', RC_Uri::url('mail/admin_mail_settings/update'));
		return $this->display('shop_config_mail_settings.dwt');
	}
	
	/**
	 * 商店设置表单提交处理
	 */
	public function update() {
		$this->admin_priv('mail_settings_manage', ecjia::MSGTYPE_JSON);
	
		$arr  = array();
		$data = $this->db->field('id, value')->select();
		foreach ($data as $row) {
			$arr[$row['id']] = $row['value'];
		}
		foreach ($_POST['value'] AS $key => $val) {
			if($arr[$key] != $val){
				$data = array(
					'value' => trim($val),
				);
				$this->db->where(array('id'=>$key))->update($data);
			}
		}
	
		/* 记录日志 */
		ecjia_admin::admin_log('', 'edit', 'shop_config');
	
		/* 清除缓存 */
		ecjia_config::instance()->clear_cache();
		ecjia_cloud::instance()->api('product/analysis/record')->data(ecjia_utility::get_site_info())->run();

		ecjia_admin_log::instance()->add_object('mail', '邮件服务器');
		ecjia_admin::admin_log(__('修改邮件服务器设置', 'mail'), 'edit', 'mail');
		
		return $this->showmessage(__('邮件服务器设置成功。', 'mail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS,array('pjaxurl' => RC_Uri::url('mail/admin_mail_settings/init')));
	}
	
	
	/**
	 * 发送测试邮件
	 */
	public function send_test_email() {
		$this->admin_priv('mail_settings_manage', ecjia::MSGTYPE_JSON);

		$mail_config = [
            'smtp_host'     => $_POST['smtp_host'],
            'smtp_port'     => $_POST['smtp_port'],
            'smtp_mail'     => $_POST['reply_email'],
            'shop_name'     => ecjia::config('shop_name'),
            'smtp_user'     => $_POST['smtp_user'],
            'smtp_pass'     => $_POST['smtp_pass'],
            'mail_charset'  => $_POST['mail_charset'],
            'smtp_ssl'      => $_POST['smtp_ssl'],
            'mail_service'  => $_POST['mail_service'],
        ];

		/* 取得参数 */
		RC_Hook::remove_action('reset_mail_config', ['Ecjia\System\Frameworks\Component\Mailer', 'ecjia_mail_config']);
		RC_Hook::add_action('reset_mail_config', function () use ($mail_config) {
            Ecjia\System\Frameworks\Component\Mailer::ecjia_mail_config($mail_config);
        });

		$test_mail_address = trim($_POST['test_mail_address']);

		$error = RC_Mail::send_mail($test_mail_address, $test_mail_address, __('测试邮件', 'mail'),  __('您好！这是一封检测邮件服务器设置的测试邮件。收到此邮件，意味着您的邮件服务器设置正确！您可以进行其它邮件发送的操作了！', 'mail'), 0);
		if ( RC_Error::is_error($error) ) {
			return $this->showmessage(sprintf(__('测试邮件发送失败！%s', 'mail'), $error->get_error_message()) , ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
            return $this->showmessage(sprintf(__('恭喜！测试邮件已成功发送到 %s。', 'mail'), $test_mail_address), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		}
	}

}

// end