<?php
/**
 * VgotFaster Framework
 * Ӧ�ó�����������ļ�
 */

$config = array(
/**
 * �����Ŀ¼���ʵ�ַ
 *
 * ����Ӧ�ó���ķ��ʵ�ַ,��β�����б�� "/"
 * ����˴�������Ϊ��,�������Զ�ʶ���ȡ���Ŀ¼��ַ
 */
'base_url' => '',

/**
 * ��ܽӿ��ļ���ַ
 *
 * ���ʹ��α��̬���� QUERY_STRING �ķ�ʽ·��
 * ���ʹ��α��̬,�뽫��������Ϊ��
 */
'index_file' => 'index.php',

/**
 * Ĭ�Ͽ��������� Default Controller Name
 *
 * �����������,Ϊ URI ȱʡ������ʱ���ʵ�Ĭ�Ͽ�����
 */
'default_controller' => 'welcome',

/**
 * ����·�ɷ�ʽ PATH_INFO|GET|QUERY_STRING
 *
 * PATH_INFO:    welcome/index
 * GET:          ?ctrl=welcome&act=index
 * QUERY_STRING: ?welcome-index
 */
'router_method' => 'PATH_INFO',

/**
 * ����·��GET��ʽ����
 *
 * ��·�ɷ���Ϊ GET ��ʱ�������
 * controller Ϊ����������,actionΪ��������,����������������
 * Example: index.php?ctrl=welcome&act=index
 */
'router_get_params' => array(
	'controller' => 'ctr',
	'action'     => 'act'
),

/**
 * URI �ָ���
 *
 * �趨 URI �����ַ�����������·��ģʽ�¾���Ч��
 */
'uri_separator' => '/',

/**
 * �Ƿ��Զ��滻�ָ���
 *
 * �Ƿ� URL ����������� / ת�����趨�ķָ���
 */
'uri_separator_replace' => false,

/**
 * ����ҳ���׺
 *
 * ������ʹ�� .html �ȣ���� Rewrite ����������α��ҳ���ļ���׺
 * ע�⣺���ô˴�ʱ���ں�׺ǰ�������ĵ㣬�硰.html�������ǡ�html��
 */
'url_suffix' => '',

/**
 * Encrypt �ӽ�����Կ
 *
 * Encrypt ���ܵ��ִ���ʱЧ�Ժͻ����ԣ��������û���¼
 */
'auth_key' => 'auth_key_123',

/**
 * Cookie ȫ������
 *
 * �������,�� Session �����뺯������Ĭ�ϲ���
 */
'cookie_prefix' => '',
'cookie_domain' => '',
'cookie_path'   => '/',

/**
 * Session ��������
 *
 * ���� Session ��Ķ���������
 */
'session_cookie_name'     => 'vf_session_id', //ͨ�� Cookie ����
'session_expire'          => 2592000,         //��Ч��Ծ�ڣ������һ�λ�Ծ������ʱ��� Session ���ݽ�������(��)
'session_use_database'    => TRUE,            //�Ƿ�ʹ�����ݿⷽʽ�洢���ݣ�Ĭ���ǣ����ڽ������ݿ�洢��ʽ
'session_db_table'        => 'vf_sessions',   //���ݱ����ƣ�����û�а���ǰ׺��������ʱ��Ҫ�������ݿ������е�ǰ׺
'session_time_to_update'  => 3600,            //���ڸ�������Ծʱ���Ա�������Ч��(��)
'session_match_ip'        => TRUE,            //�Ƿ���֤�û� IP ��ַ
'session_match_useragent' => TRUE,            //�Ƿ���֤�û��� UserAgent,ֻȡǰ 50 λ�ַ�

/**
 * Ĭ������
 *
 * ϵͳ�Դ�����غ���������в��ô�Ĭ�ϵ����ԣ�ѡ��Ҳ���������ļ������ļ��е�����
 */
'default_language' => 'zh-cn',


/**
 * ��ͼ�ļ���׺
 *
 * ��ѡ�������ģ����鱣��Ĭ�ϵ� php
 * ע�⣺�˴���׺��Ҫ��д����� "."
 */
'view_file_extension' => 'php',

/**
 * ��ͼ�̱�ǩ֧��
 *
 * �Ƿ���ϵͳ��֧�ֶ̱�ǩʱ�Զ�������ͼ��ģ��̱�ǩ֧��
 * ���������ʹ php.ini �����йرն̱�ǩ֧�֣���Ҳ��������ͼ��ʹ�� <?=?> ��ʽ�Ķ̱�ǩ���������
 */
'open_short_tag' => TRUE,

/**
 * ģ���ļ�Ĭ�Ϻ�׺
 * ģ������Ƿ�����հ���������
 *
 * ע�⣺�˴���׺��Ҫ��д����� "."
 */
'template_file_extension' => 'htm',
'template_clean_blank' => TRUE,

/**
 * URI �����ַ���������ʽ
 *
 * Ĭ������ "/^[\w\d\|\/\-_~\.]+$/i" ����������ĸ���֣����� | / - _ ~ . ���Ҳ����ִ�Сд
 * ϵͳʹ�ô˷�ʽ���ٿ�������URI�İ�ȫ���⡣���ǵ���ȫ���⣬�����˽�ϵͳ������������Ĵ˴�
 */
'uri_allowed_regular' => '/^[\w\d\|\/\-_~\.]+$/i',

/**
 * �Ƿ��� MAGIC_QUOTES_GPC ֧��
 *
 * ͬ���� php.ini �е� magic_quotes_gpc ѡ��
 * ע�⣺�˴������ý��Ḳ�� php.ini �е����ã���Ӧ�ó�������ʱ��ϵͳ���ݴ˴����ö����ȫ�ֱ�������
 */
'open_magic_quotes_gpc' => TRUE
);
