<?php 

/*
+--------------------------------------------------------------------------
|   thinkask [#开源系统#]
|   ========================================
|   http://www.thinkask.cn
|   ========================================
|   如果有兴趣可以加群{开发交流群} 485114585
|   ========================================
|   更改插件记得先备份，先备份，先备份，先备份
|   ========================================
+---------------------------------------------------------------------------
 */
return [
     ##name  _name 
      'glob_set_name'=>'全局设置',
      'web_set_name'=>'站点信息',
      'base_set_name'=>'基本设置',
      'reg_view_name'=>'注册访问',
      'web_function_name'=>'站点功能',
      'category_set_name'=>'分类设置',
      'mail_set_name'=>'邮件设置',
      'admin_open_name'=>'开放平台',
      'visual_set_name'=>'界面设置',
      'navigation_cat_name'=>'导航分类',
      'content_model_name'   =>'模块管理',
      'plus_model_name'=>'插件管理',
      'plus_list_name'        =>'插件列表',
      'hook_list_name'=>'勾子管理',
      'user_center_name'=>'用户中心',
      'user_list_name'=>'用户列表',
      'admin_list_name'=>'管理员列表',
      'datebase_backup_name'=>'数据备份',
      'datebase_import_name'=>'数据恢复',
       "operation_management_name"=>'运营管理',
       "adv_manager_name"       =>"广告管理",
       "power_message_name"   =>'权限管理',

       'question_set_name'=>'问题设置',
       'caiji_role_name'=>'采集规则',
       'creat_user_name'=>'生成用户',




     'window_action'  => 'Windows',
     'home_action'  => '首页',
     'theme_action'  => '模板',

     //error
     'close_all'=>'关闭所有',
     'error'=>'错误',
     'action_error'=>'操作错误',
     'power_error'=>'权限错误',
     '404_error'=>'404错',
     //menu
     'admin_menu_index'=>'首页',
     
     
     
     
     'admin_content_set'=>'内容设置',
     
     'admin_score'=>'威望积分',
     'admin_user_power'=>'用户权限',
     
     
     'admin_performance_optimization'=>'性能优化',
     

     'admin_content_manager'=>'内容管理',
     'admin_question'=>'问题',
     'admin_question_manager'=>'问题管理',
     'admin_article'          =>'文章',
     'admin_article_manager'=>'文章管理',
     'admin_topic'         =>'话题',
     'admin_topic_manager'=>'话题管理',


     
     
     'admin_user_group'=>'用户组',
     'admin_invitation'=>'批量邀请',
     'admin_job_management'=>'职位管理',

     'admin_audits_management'=>'审核管理',
     'admin_authentication_audit'=>'认证审核',
     'admin_reg_audit'=>'注册审核',
     'admin_user_accusation'=>'用户举报',
     
     
     

     'admin_content_set'=>'内容设置',
     'admin_navigation_set'=>'导航设置',
     
     
     'admin_subject_set'=>'专题设置',
     'admin_page_set'=>'页面设置',
     'admin_help'=>'帮助中心',

     'admin_sns_api'=>'第三方扩展开发',
     'admin_wexin_menu'=>'微信菜单',
     'admin_wexin_reply'=>'微信自定义回复',
     'admin_wexin_api'=>'微信第三方接入',
     'admin_wexin_code'=>'微信二维码管理',
     'admin_wexin_msg_group'=>'微信消息群发',
     'admin_webo_msg_get'=>'微博消息接收',
     'admin_mail_import'=>'邮件导入',

     'admin_mail_manager'=>'邮件管理',
     'admin_duty_manager'=>'任务管理',
     'admin_user_group_manager'=>'用户群管理',

     'admin_tool'=>'工具',
     'admin_system_maintenance'=>'系统维护',
     'admin_collection'=>'采集',
     'creat_user'=>'生成用户',
  
     'admin_data type error' => '数据类型错误',

     
     'model_list'   =>'模块列表',
     'system_name'=>'系统管理',
     'shop_name' =>'应用商城',
     'shop_page_name' =>'商城页面',

     

     //相关按钮
     'BACK'=>'后退',
     'REFRESH'=>'刷新',
     'NEW_WINDWOWS_OPEN'=>'新窗口打开',
     'ClangEAN_CACHE'=>'清空缓存',
     'GO_FORWARD'=>'前进',


     'update_success'=>'更新成功',
     '_CLASS_NOT_EXIST_'=>'类不存在',


     //official
     'admin_official_function'=>'官网功能',
     'admin_official_apply'=>'需求申请',
     
     // 'admin_official'=>'',
     // 'admin_official'=>'',
     // 'admin_official'=>'',
     // 'admin_official'=>'',
  
     'install_title' => 'thinkask安装向导',
     'install_license' => 'thinkask问答系统-安装用户协议',
     'install_is_lock' => '您已成功安装thinkask，需重新安装请手动删除网站目录下install/install.lock文件', 
     'install_db_error' => '数据库文件无法读取，请检查install/inc/thinkask.sql是否存在。',     
     'agree_and_accept' => '同意并接受',     
     'detection_environment' => '检测环境',
     'data_create' => '创建数据',
     'complete_installation' => '完成升级',
     'environmental_testing' => '环境检测',
     'recommended_configuration' => '推荐配置',
     'current_status' => '当前状态',
     'operating_system' => '操作系统',
     'version' => '版本',
     'attachment_upload' => '附件上传',
     'extension' => '扩展',
     'directory_permissions' => '目录权限检测',
     'write' => '写入',
     'read' => '读取',
     'previous' => '上一步',
     'next' => '下一步',    
     'install' => '安装',
     'faile' => '失败',
     'success' => '成功',
     'database_information' => '数据库信息',
     'database_information_tip' => '安装后,原数据库会被清空,请做好备份',

     'install_mysql_host' => '数据库服务器',
     'install_mysql_host_intro' => '本地填写：127.0.0.1或IP',
     'install_mysql_port' => '数据库端口',
     'install_mysql_port_intro' => '数据库端口一般为3306',

     'install_mysql_username' => '数据库用户名',
     'install_mysql_password' => '数据库密码',
     'install_mysql_name' => '数据库名称',
     'install_mysql_name_intro' => '不存在则自动创建',
     'install_mysql_prefix' => '数据库表前缀',
     'install_mysql_prefix_intro' => '推荐使用默认',
     'site_configuration' => '网站配置',
     'site_name' => '网站名称',
     'site_name_default' => '我的网站',
     'site_url' => '网站网址',
     'site_url_intro' => '请以http://或https://开头',
     'site_style' => '网站风格',
     'site_style_c' => '企业站',
     'site_style_b' => '博客站',
     'website_administrator' => '网站超级管理员',
     'username' => '用户名',
     'password' => '密　码',
     'password_intro' => '最少6位',
     'test_data' => '测试数据',
     'test_data_intro' => '添加默认数据！(适合新手第一次使用)',
     'Installation' => '安装中',
     'Data_initialization' => '数据初始化中',

     'installation_failed' => '安装失败',
     'installation_complete' => '安装完成',
     'installation_successful' => '安装成功',
     'installation_failed_reinstall' => '安装失败,请重新升级安装',
     'congratulations_installation_success' => '恭喜您，安装成功！',
     'please_delete_folder' => '请手动删除Update文件夹',  
     'visit_home' => '访问网站首页',
     'enter_admin' => '进入后台管理',

     'safe_notes' => '为了您站点的安全，安装完成后请立即删除网站根目录下的“Install”文件夹删除。',
     'system_installation_requirements_php' => '系统安装要求：PHP版本最低不能低于',
     'install_on' => '开启',

     'install_mysql_host_empty' => '请填写数据库服务器!',
     'install_mysql_port_empty' => '请填写数据库端口!',   
     'install_mysql_username_empty' => '请填写数据库用户名!',
     'install_mysql_name_empty' => '请填写数据库名!',
     'install_mysql_prefix_empty' => '请填写数据库表前缀!',
     'site_url_empty' => '请填写网站网址!',
     'install_founder_name_empty' => '创建管理员帐号不能为空',
     'install_founder_password_length' => '管理员密码必须大于6位',
     'founder_invalid_password' => '密码长度必须大于或等于6位',
     'email_failed' => 'E-mail格式不正确!',
     'database_connection_failed' => '数据库连接失败',
     'database_create_failed' => '数据库创建失败',
     'database_select_fails' => '选择数据库失败',
     'error_message' => '错误信息',
     'write_tmp_failed' => '写入临时文件失败!',
     'conf_not_write' => 'Conf目录没有写权限',
     'load_failed_reinstall' => '加载文件失败，请重新安装',
     'data_table' => '数据表',
     'write_data_table' => '写入数据表',
     'create_administrator_failed' => '创建管理员失败',
     'please_refresh_installation' => '请重新刷新安装',


     'support' => '支持',
     'unsupport' => '不支持',
    'mustopen' => '必须开启',
     'suggestopen' => '建议开启',

     'plus_caiji'=>'采集',
     
     


     

    



];