<?php namespace Phpcmf;

/**
 * http://www.xunruicms.com
 * 本文件是框架系统文件，二次开发时不可以修改本文件
 **/

// 公共类
abstract class Common extends \CodeIgniter\Controller
{

    private static $instance;

    private $load_init;

    public $uid;
    public $admin;
    public $member;
    public $module;
    public $member_cache;

    public $site; // 网站id信息
    public $site_info; // 网站配置信息
    public $site_domain; // 全部站点域名
    public $is_hcategory; // 模块不使用栏目

    public $session; // 网站session对象
    public $is_mobile; // 是否移动端
    public $temp; // 临时数据存储

    protected $is_module_init; // 防止模块重复初始化
    protected $cmf_version; // 版本信息
    protected $cmf_license; // 版本信息


    /**
     * 初始化共享控制器
     */
    public function __construct(...$params)
    {
        //parent::initController(...$params);

        // 部分虚拟主机会报500错误
        //\Config\Services::response()->removeHeader('Content-Type');

        self::$instance =& $this;

        if (defined('IS_INSTALL')) {
            return;
        }

        // 站点配置
        if (is_file(WRITEPATH.'config/site.php')) {
            $this->site_info = require WRITEPATH.'config/site.php';
            foreach ($this->site_info as $id => $t) {
                !$t['SITE_DOMAIN'] && $t['SITE_DOMAIN'] = DOMAIN_NAME;
                $this->site[$id] = $id;
                $this->site_info[$id] = $t;
                $this->site_info[$id]['SITE_ID'] = $id;
                $this->site_info[$id]['SITE_URL'] = dr_http_prefix($t['SITE_DOMAIN'].'/');
                $this->site_info[$id]['SITE_MURL'] = dr_http_prefix(($t['SITE_MOBILE'] ? $t['SITE_MOBILE'] : $t['SITE_DOMAIN']).'/');
                $this->site_info[$id]['SITE_IS_MOBILE'] = $t['SITE_MOBILE'] ? 1 : 0;
            }
        } else {
            //!defined('IS_NOT_301') && define('IS_NOT_301', 1);
            $this->site_info[1] = [
                'SITE_ID' => 1,
                'SITE_URL' => dr_http_prefix(DOMAIN_NAME.'/'),
                'SITE_MURL' => dr_http_prefix(DOMAIN_NAME.'/'),
            ];
        }

        // 版本
        if (!is_file(MYPATH.'Config/Version.php')) {
            $this->cmf_version = [
                'id' => 10,
                'name' => '迅睿CMS框架',
                'version' => '开发版',
                'downtime' => SYS_TIME,
            ];
        } else {
            $this->cmf_version = require MYPATH.'Config/Version.php';
        }
        define('CMF_VERSION', $this->cmf_version['version']);
        // 版本更新时间字符串
        define('CMF_UPDATE_TIME', str_replace(['-', ' ', ':'], '', $this->cmf_version['downtime'] ? $this->cmf_version['downtime'] : $this->cmf_version['updatetime']));

        $client = []; // 电脑域名对应的手机域名
        if (is_file(WRITEPATH.'config/domain_client.php')) {
            $client = require WRITEPATH.'config/domain_client.php';
        }

        $this->site_domain = []; // 全网域名对应的站点id
        if (is_file(WRITEPATH.'config/domain_site.php')) {
            $this->site_domain = require WRITEPATH.'config/domain_site.php';
        }

        // 站点id
        !defined('SITE_ID') && define('SITE_ID', 1);

        // 站点共享变量
        define('SITE_URL', $this->site_info[SITE_ID]['SITE_URL']);
        define('SITE_MURL', $this->site_info[SITE_ID]['SITE_MURL']);
        define('SITE_NAME', $this->site_info[SITE_ID]['SITE_NAME']);
        define('SITE_LOGO', $this->site_info[SITE_ID]['SITE_LOGO']);
        define('SITE_IS_MOBILE', $this->site_info[SITE_ID]['SITE_IS_MOBILE']); // 是否存在移动端
        define('SITE_IS_MOBILE_HTML', (int)$this->site_info[SITE_ID]['SITE_IS_MOBILE_HTML']);
        define('SITE_MOBILE_NOT_PAD', (int)$this->site_info[SITE_ID]['SITE_MOBILE_NOT_PAD']); // pad不归类为移动端
        define('SITE_THEME', strlen($this->site_info[SITE_ID]['SITE_THEME']) ? $this->site_info[SITE_ID]['SITE_THEME'] : 'default');
        define('SITE_SEOJOIN', strlen($this->site_info[SITE_ID]['SITE_SEOJOIN']) ? $this->site_info[SITE_ID]['SITE_SEOJOIN'] : '_');
        define('SITE_REWRITE', (int)$this->site_info[SITE_ID]['SITE_REWRITE']);
        define('SITE_TEMPLATE', strlen($this->site_info[SITE_ID]['SITE_TEMPLATE']) ? $this->site_info[SITE_ID]['SITE_TEMPLATE'] : 'default');
        define('SITE_LANGUAGE', strlen($this->site_info[SITE_ID]['SITE_LANGUAGE']) ? $this->site_info[SITE_ID]['SITE_LANGUAGE'] : 'zh-cn');
        define('SITE_TIME_FORMAT', strlen($this->site_info[SITE_ID]['SITE_TIME_FORMAT']) ? $this->site_info[SITE_ID]['SITE_TIME_FORMAT'] : 'Y-m-d H:i:s');

        // 客户端识别
        $this->is_mobile = defined('IS_MOBILE') ? 1 : (IS_ADMIN ? 0 : $this->_is_mobile());

        // 后台域名
        !defined('ADMIN_URL') && define('ADMIN_URL', dr_http_prefix(DOMAIN_NAME.'/'));

        // 设置时区
        if (strlen($this->site_info[SITE_ID]['SITE_TIMEZONE']) > 0) {
            date_default_timezone_set('Etc/GMT'.($this->site_info[SITE_ID]['SITE_TIMEZONE'] > 0 ? '-' : '+').abs($this->site_info[SITE_ID]['SITE_TIMEZONE'])); // 设置时区
        }

        // 全局URL
        define('PAY_URL', $this->is_mobile ? SITE_MURL : SITE_URL); // 付款URL
        define('ROOT_URL', $this->site_info[1]['SITE_URL']); // 主站URL
        define('OAUTH_URL', PAY_URL); // 第三方登录URL
        define('LANG_PATH', ROOT_URL.'api/language/'.SITE_LANGUAGE.'/'); // 语言包

        !defined('THEME_PATH') && define('THEME_PATH', (SYS_THEME_ROOT ? SITE_URL : ROOT_URL).'static/'); // 系统风格
        !defined('ROOT_THEME_PATH') && define('ROOT_THEME_PATH', ROOT_URL.'static/'); // 系统风格绝对路径

        if (strpos(SITE_THEME, '/') !== false) {
            // 远程资源
            define('HOME_THEME_PATH', SITE_THEME); // 站点风格
            define('MOBILE_THEME_PATH', SITE_THEME); // 移动端站点风格
        } else {
            // 本地资源
            define('HOME_THEME_PATH', (SYS_THEME_ROOT ? SITE_URL : ROOT_URL).'static/'.SITE_THEME.'/'); // 站点风格
            if (!defined('IS_MOBILE') && ($this->_is_mobile() && $this->site_info[SITE_ID]['SITE_AUTO']) && SITE_URL == SITE_MURL) {
                // 当开启自适应移动端，没有绑定域名时
                define('MOBILE_THEME_PATH', SITE_URL.'mobile/static/'.SITE_THEME.'/'); // 移动端站点风格
            } else {
                define('MOBILE_THEME_PATH', SITE_MURL.'static/'.SITE_THEME.'/'); // 移动端站点风格
            }
        }

        // 本地附件上传目录和地址
        if (SYS_ATTACHMENT_PATH
            && (strpos(SYS_ATTACHMENT_PATH, '/') === 0 || strpos(SYS_ATTACHMENT_PATH, ':') !== false)
            && is_dir(SYS_ATTACHMENT_PATH)) {
            // 相对于根目录
            // 附件上传目录
            define('SYS_UPLOAD_PATH', rtrim(SYS_ATTACHMENT_PATH, DIRECTORY_SEPARATOR).'/');
            // 附件访问URL
            define('SYS_UPLOAD_URL', trim(SYS_ATTACHMENT_URL, '/').'/');
        } else {
            // 在当前网站目录
            $path = trim(SYS_ATTACHMENT_PATH ? SYS_ATTACHMENT_PATH : 'uploadfile', '/');
            // 附件上传目录
            define('SYS_UPLOAD_PATH', ROOTPATH.$path.'/');
            // 附件访问URL
            define('SYS_UPLOAD_URL', ROOT_URL.$path.'/');
        }

        // 设置终端模板
        $is_auto_mobile_page = 0;
        if (defined('IS_CLIENT')) {
            // 存在自定义终端
            define('CLIENT_URL', dr_http_prefix($this->get_cache('site', SITE_ID, 'client', IS_CLIENT)) . '/');
            \Phpcmf\Service::V()->init(defined('IS_CLIENT_TPL') && IS_CLIENT_TPL ? IS_CLIENT_TPL : IS_CLIENT);
        } elseif (defined('IS_MOBILE') || ($this->_is_mobile() && $this->site_info[SITE_ID]['SITE_AUTO'])) {
            // 移动端模板 // 开启自动识别移动端
            \Phpcmf\Service::V()->init('mobile');
            $is_auto_mobile_page = 1;
            define('CLIENT_URL', SITE_MURL);
        } else {
            // 默认情况下pc模板
            define('CLIENT_URL', SITE_URL);
            \Phpcmf\Service::V()->init('pc');
        }
        !defined('IS_CLIENT') && define('IS_CLIENT', '');

        // 用户系统
        $this->member_cache = $this->get_cache('member');
        if (IS_CLIENT) {
            define('MEMBER_URL', CLIENT_URL.(defined('MEMBER_PAGE') && MEMBER_PAGE ? MEMBER_PAGE : 'index.php?s=member'));
        } else {
            if (!$is_auto_mobile_page && isset($this->member_cache['domain'][SITE_ID]['domain'])
                && $this->member_cache['domain'][SITE_ID]['domain']) {
                // 电脑端绑定域名时
                define('MEMBER_URL', dr_http_prefix($this->member_cache['domain'][SITE_ID]['domain'].'/'));
            } elseif ($is_auto_mobile_page && isset($this->member_cache['domain'][SITE_ID]['mobile_domain'])
                && $this->member_cache['domain'][SITE_ID]['mobile_domain']) {
                // 移动端绑定域名时
                define('MEMBER_URL', dr_http_prefix($this->member_cache['domain'][SITE_ID]['mobile_domain'].'/'));
            } else {
                // 默认域名
                define('MEMBER_URL', (!$is_auto_mobile_page ? SITE_URL : SITE_MURL).(defined('MEMBER_PAGE') && MEMBER_PAGE ? MEMBER_PAGE : 'index.php?s=member'));
            }
        }

        // 姓名字段
        define('MEMBER_CNAME', dr_lang($this->member_cache['config']['cname'] ? $this->member_cache['config']['cname'] : '姓名'));

        // 网站常量
        define('SITE_ICP', $this->get_cache('site', SITE_ID, 'config', 'SITE_ICP'));
        define('SITE_TONGJI', $this->get_cache('site', SITE_ID, 'config', 'SITE_TONGJI'));
        // 默认登录时间
        define('SITE_LOGIN_TIME', $this->member_cache['config']['logintime'] ? max(intval($this->member_cache['config']['logintime']), 500) : 36000);
        // 定义交易变量
        define('SITE_SCORE', dr_lang($this->member_cache['pay']['score'] ? $this->member_cache['pay']['score'] : '金币'));
        define('SITE_EXPERIENCE', dr_lang($this->member_cache['pay']['experience'] ? $this->member_cache['pay']['experience'] : '经验'));

        // 验证api提交认证
        if (dr_is_app('httpapi') && \Phpcmf\Service::L('input')->request('appid')) {
            define('IS_API_HTTP', 1);
            \Phpcmf\Service::M('http', 'httpapi')->check_auth();
        } else {
            define('IS_API_HTTP', 0);
            $this->uid = (int)\Phpcmf\Service::M('member')->member_uid();
            $this->member = \Phpcmf\Service::M('member')->get_member($this->uid);
            if (!$this->member) {
                $this->uid = 0;
            }
            // 验证账号cookie的有效性
            if ($this->member && !\Phpcmf\Service::M('member')->check_member_cookie($this->member)) {
                $this->uid = 0;
                $this->member = [];
            }
        }

        // 开启自动跳转手机端(api、admin、member不跳转)
        if (!IS_API // api不跳转
            && !IS_ADMIN // 后台不跳转
            && !IS_MEMBER // 会员中心不跳
            && !IS_API_HTTP // API请求不跳
            && !IS_CLIENT // 终端不跳
            //&& !defined('IS_NOT_301') // 定义禁止301不跳
            //&& !defined('IS_NOT_301') // 定义禁止301不跳
            && $client // 没有客户端不跳
            && $this->site_info[SITE_ID]['SITE_MOBILE'] // 没有绑定移动端域名不跳
            //&& !in_array(DOMAIN_NAME, $client) // 当前域名不存在于客户端中时
            && $this->site_info[SITE_ID]['SITE_AUTO'] // 开启自动识别跳转
        ) {
            if ($this->_is_mobile()) {
                // 这是移动端
                if (isset($client[DOMAIN_NAME])) {
                    // 表示这个域名属于电脑端,需要跳转到移动端
                    \Phpcmf\Service::L('Router')->is_redirect_url(str_replace(dr_http_prefix(DOMAIN_NAME), dr_http_prefix($client[DOMAIN_NAME]), dr_now_url()), 1);
                }
            } else {
                // 这是电脑端
                if (in_array(DOMAIN_NAME, $client)) {
                    // 表示这个域名属于移动端,需要跳转到pc
                    $arr = array_flip($client);
                    \Phpcmf\Service::L('Router')->is_redirect_url(str_replace(dr_http_prefix(DOMAIN_NAME), dr_http_prefix($arr[DOMAIN_NAME]), dr_now_url()) , 1);
                }
            }
        }
        
        // 判断网站是否关闭
        if (!IS_DEV && !IS_ADMIN && !IS_API
            && $this->site_info[SITE_ID]['SITE_CLOSE']
            && (!$this->member || !$this->member['is_admin'])) {
            // 挂钩点 网站关闭时
            \Phpcmf\Hooks::trigger('cms_close');
            $this->_msg(0, $this->get_cache('site', SITE_ID, 'config', 'SITE_CLOSE_MSG'));
        }

        // 站群系统接入
        if (is_file(ROOTPATH.'api/fclient/sync.php')) {
            $sync = require ROOTPATH.'api/fclient/sync.php';
            if ($sync['status'] == 4) {
                if ($sync['close_url']) {
                    dr_redirect($sync['close_url']);
                } else {
                    $this->_msg(0, '网站被关闭');
                }
            } elseif ($sync['status'] == 3 || ($sync['endtime'] && SYS_TIME > $sync['endtime'])) {
                if ($sync['pay_url']) {
                    dr_redirect($sync['pay_url']);
                } else {
                    $this->_msg(0, '网站已过期');
                }
            }
        }

        if (IS_ADMIN) {
            // 开启session
            $this->session();
            // 版本
            if (!is_file(MYPATH.'Config/License.php')) {
                define('IS_OEM_CMS', 0);
                $this->cmf_license = [];
            } else {
                $this->cmf_license = require MYPATH.'Config/License.php';
                define('IS_OEM_CMS', $this->cmf_license['oem'] ? 1 : 0);
            }
            // 后台登录判断
            $this->admin = \Phpcmf\Service::M('auth')->is_admin_login($this->member);
            \Phpcmf\Service::V()->admin();
            \Phpcmf\Service::V()->assign([
                'admin' => $this->admin,
                'is_ajax' => \Phpcmf\Service::L('input')->get('is_ajax'),
                'is_mobile' => $this->_is_mobile() ? 1 : 0,
            ]);
            // 权限判断
            $uri = \Phpcmf\Service::L('Router')->uri();
            if (!$this->_is_admin_auth($uri)) {
                // 无权限操作
                list($a, $action) = explode('_',\Phpcmf\Service::L('Router')->method);
                !$action && $action = $a;
                // 获取操作名称
                switch ($action) {
                    case 'add':
                        $name = dr_lang('【增】');
                        break;
                    case 'edit':
                        $name = dr_lang('【改】');
                        break;
                    case 'del':
                        $name = dr_lang('【删】');
                        break;
                    default:
                        $name = dr_lang('【使用】');
                        break;
                }
                $this->_admin_msg(0, dr_lang('没有%s权限（#%s）', $name, $uri));
            }
        }

        // 判断是否存在授权登录
        if (!IS_ADMIN && $code = \Phpcmf\Service::L('input')->get_cookie('admin_login_member')) {
            list($uid, $adminid) = explode('-', $code);
            $uid = (int)$uid;
            if ($this->uid != $uid) {
                $admin = \Phpcmf\Service::M()->table('member')->get((int)$adminid);
                if ($this->session()->get('admin_login_member_code') == md5($uid.$admin['id'].$admin['password'])) {
                    $this->uid = $uid;
                    $this->member = \Phpcmf\Service::M('member')->get_member($this->uid);
                }
            }
        }

        if (IS_MEMBER) {
            // 开启session
            $this->session();
            // 登录状态验证
            if (!$this->member && !in_array(\Phpcmf\Service::L('Router')->class, ['register', 'login', 'api', 'pay'])) {
                if (APP_DIR && dr_is_module(APP_DIR)
                    && \Phpcmf\Service::L('Router')->class == 'home' && \Phpcmf\Service::L('Router')->method == 'add') {
                    // 游客发布权限
                } else {
                    // 会话超时，请重新登录
                    if (IS_API_HTTP) {
                        $this->_json(0, dr_lang('无法获取到登录用户信息'));
                    } else {
                        \Phpcmf\Service::L('Router')->go_member_login(dr_now_url());
                    }
                }
            }
            // 判断用户的权限
            if ($this->member && !in_array(\Phpcmf\Service::L('Router')->class, ['register', 'login', 'api'])) {
                $this->_member_option(0);
            }

            \Phpcmf\Service::V()->assign(\Phpcmf\Service::L('Seo')->member(\Phpcmf\Service::L('cache')->get('menu-member')));
            //\Phpcmf\Service::V()->assign([ ]);
        }

        // 初始化处理
        \Phpcmf\Service::M('member')->init_member($this->member);

        if (!IS_ADMIN && !IS_API  && !in_array(\Phpcmf\Service::L('Router')->class, ['register', 'login', 'api'])) {
            // 判断网站访问权限
            if (!defined('SC_HTML_FILE') && !IS_MEMBER && \Phpcmf\Service::M('member_auth')->home_auth('show', $this->member)) {
                $this->_msg(0, dr_lang('您的用户组无权限访问站点'));
            }
            // 账户被锁定
            if ($this->member && $this->member['is_lock']) {
                if (dr_is_app('login') && $this->member['is_lock'] == 2) {
                    // 被插件锁定
                    if (APP_DIR != 'login') {
                        $this->_msg(0, dr_lang('账号被锁定'), dr_url('login/home/index'));
                    }
                } else {
                    $this->_msg(0, dr_lang('账号被锁定'));
                }
            }
        }

        \Phpcmf\Service::V()->assign([
            'member' => $this->member,
        ]);

        // 附加程序初始化文件
        if (is_file(MYPATH.'Init.php')) {
            require MYPATH.'Init.php';
        }

        // 插件目录初始化
        APP_DIR && $this->init_file(APP_DIR);

        // 预览开发的id
        !defined('SITE_FID') && define('SITE_FID', 0);

        // 挂钩点 程序初始化之后
        \Phpcmf\Hooks::trigger('cms_init');
    }

    /**
     * 插件目录初始化文件
     */
    public function init_file($namespace) {

        if (!$namespace) {
            return;
        }

        $file = dr_get_app_dir($namespace).'Config/Init.php';
        if (in_array($file, $this->load_init)) {
            return;
        }

        if (is_file($file)) {
            $this->load_init[] = $file;
            require_once $file;
        }
    }

    /**
     * 开启session
     */
    public function session() {

        if ($this->session) {
            return $this->session;
        }

        $this->session = \Config\Services::session();
        $this->session->start();

        return $this->session;
    }

    /**
     * 缓存页面
     */
    protected function cachePage(int $time) {
        return;// 暂时不使用页面缓存
        if (isset($this->site_info[SITE_ID]['SITE_CLOSE']) && $this->site_info[SITE_ID]['SITE_CLOSE']) {
            // 网站关闭状态时不进行缓存页面
            return;
        } elseif ($this->site_info[SITE_ID]['SITE_AUTO']) {
            // 开启了自动识别移动端，不进行缓存
            return;
        }
        parent::cachePage($time);
    }

    /**
     * 读取缓存
     */
    public function get_cache(...$params) {
        return \Phpcmf\Service::L('cache')->get(...$params);
    }

    // 初始化模块
    public function _module_init($dirname = '', $siteid = SITE_ID) {

        !$dirname && $dirname = APP_DIR;

        if ($this->is_module_init == $dirname.'-'.$siteid) {
            // 防止模块重复初始化
            return;
        }

        $this->is_module_init = $dirname.'-'.$siteid;

        // 判断模块是否安装在站点中
        $cache = \Phpcmf\Service::L('cache')->get('module-'.$siteid);
        $this->module = [];
        if ($dirname == 'share' || (isset($cache[$dirname]) && $cache[$dirname])) {
            $this->module = \Phpcmf\Service::L('cache')->get('module-'.$siteid.'-'.$dirname);
        }

        // 判断模块是否存在
        if (!$this->module) {
            // 重新生成一次缓存
            \Phpcmf\Service::M('cache')->sync_cache('');
            $this->module = \Phpcmf\Service::L('cache')->get('module-'.$siteid.'-'.$dirname);
            if (!$this->module) {
                if (IS_ADMIN) {
                    if ($dirname == 'share') {
                        $this->_admin_msg(0, dr_lang('系统未安装共享模块，无法使用栏目'));
                    } else {
                        $this->_admin_msg(0, dr_lang('模块【%s】不存在', $dirname));
                    }
                } else {
                    $this->goto_404_page(dr_lang('模块【%s】不存在', $dirname));
                }
                return;
            }
        }

        // 无权限访问模块
        if (!defined('SC_HTML_FILE') && !IS_ADMIN && !IS_MEMBER
            && \Phpcmf\Service::M('member_auth')->module_auth($dirname, 'show', $this->member)) {
            $this->_msg(0, dr_lang('您的用户组无权限访问模块'), $this->uid || !defined('SC_HTML_FILE') ? '' : dr_member_url('login/index'));
            return;
        }

        // 初始化数据表
        $this->content_model = \Phpcmf\Service::M('Content', $dirname);
        $this->content_model->_init($dirname, $siteid, $this->module['share']);

        // 共享模块时，单页界面时，排除
        if ($dirname == 'share') {
           return;
        }

        $this->module['comment'] = dr_is_app('comment') && \Phpcmf\Service::L('cache')->get('app-comment-'.SITE_ID, 'module', $dirname, 'use') ? 1 : 0;

        // 兼容老版本
        define('MOD_DIR', $dirname);
        define('IS_SHARE', $this->module['share']);
        define('IS_COMMENT', $this->module['comment']);
        define('MODULE_URL', $this->module['share'] ? '/' : $this->module['url']); // 共享模块没有模块url
        define('MODULE_NAME', $this->module['name']);

        $this->content_model->is_hcategory = $this->is_hcategory = isset($this->module['config']['hcategory']) && $this->module['config']['hcategory'];

        // 设置模板到模块下
        !$this->module['url'] && \Phpcmf\Service::V()->module($dirname);

        // 初始化加载
        $this->init_file($dirname);
    }

    /**
     * 统一返回json格式并退出程序
     */
    public function _json($code, $msg, $data = []){

        // 如果是来自api判断回调
        if (IS_API_HTTP) {
            $call = \Phpcmf\Service::L('input')->request('api_call_function');
            if ($call) {
                $call = dr_safe_replace($call);
                if (!method_exists(\Phpcmf\Service::L('http'), $call)) {
                    echo dr_array2string(dr_return_data(0, '回调方法(' . $call . ')未定义'));exit;
                }
                $data = \Phpcmf\Service::L('http')->$call($data);
            }
        }

        echo dr_array2string(dr_return_data($code, $msg, $data));exit;
    }

    /**
     * 统一返回jsonp格式并退出程序
     */
    public function _jsonp($code, $msg, $data = []){

        $callback = dr_safe_replace(\Phpcmf\Service::L('input')->get('callback'));
        !$callback && $callback = 'callback';

        if (IS_API_HTTP) {
            $this->_json($code, $msg, $data);
        } else {
            echo $callback.'('.dr_array2string(dr_return_data($code, $msg, $data)).')';exit;
        }
    }

    /**
     * 加载数组配置文件
     */
    public function _require_array($file) {

        if (!is_file($file)) {
            return [];
        }

        $array = require $file;

        return $array;
    }

    /**
     * 后台提示信息
     */
    public function _admin_msg($code, $msg, $url = '', $time = 3) {

        if (\Phpcmf\Service::L('input')->get('callback')) {
            $this->_jsonp($code, $msg, $url);
        } elseif ((\Phpcmf\Service::L('input')->get('is_ajax') || IS_API_HTTP || IS_AJAX)) {
            $this->_json($code, $msg, $url);
        }

        if (!$url) {
            $backurl = $_SERVER['HTTP_REFERER'];
            $backurl && strpos(dr_now_url(), $backurl) === 0 && $backurl = '';
        } else {
            $backurl = $url;
        }

        // 不存在URL时进入提示页面
        \Phpcmf\Service::V()->assign([
            'msg' => $msg,
            'url' => \Phpcmf\Service::L('input')->xss_clean($url),
            'time' => $time,
            'mark' => $code,
            'backurl' => \Phpcmf\Service::L('input')->xss_clean($backurl),
            'meta_title' => dr_clearhtml($msg),
            'is_msg_page' => 1,
        ]);

        \Phpcmf\Service::V()->display('msg.html', 'admin');
        exit;
    }

    /**
     * 前台提示信息
     */
    public function _msg($code, $msg, $url = '', $time = 3) {

        if (\Phpcmf\Service::L('input')->get('callback')) {
            $this->_jsonp($code, $msg, $url);
        } elseif ((\Phpcmf\Service::L('input')->get('is_ajax') || IS_API_HTTP || IS_AJAX)) {
            $this->_json($code, $msg, $url);
        }

        if (!$url) {
            $backurl = $_SERVER['HTTP_REFERER'];
            (!$backurl || $backurl == dr_now_url() ) && $backurl = SITE_URL;
        } else {
            $backurl = $url;
        }

        \Phpcmf\Service::V()->assign([
            'msg' => $msg,
            'url' => $url,
            'time' => $time,
            'mark' => $code,
            'code' => $code,
            'backurl' => $backurl,
            'meta_title' => SITE_NAME
        ]);
        \Phpcmf\Service::V()->display('msg.html');
        !defined('SC_HTML_FILE') && exit();
    }


    /**
     * 附件信息
     */
    public function get_attachment($id) {

        if (!$id) {
            return null;
        }

        $data = \Phpcmf\Service::L('cache')->get_file('attach-info-'.$id, 'attach');
        if ($data) {
            return $data;
        }

        $id = (int)$id;
        $data = \Phpcmf\Service::M()->db->table('attachment')->where('id', $id)->get()->getRowArray();
        if (!$data) {
            return null;
        } elseif ($data['related']) {
            $info = \Phpcmf\Service::M()->db->table('attachment_data')->where('id', $id)->get()->getRowArray();
        } else {
            $info = \Phpcmf\Service::M()->db->table('attachment_unused')->where('id', $id)->get()->getRowArray();
        }

        if (!$info) {
            if ($data['related']) {
                $info = \Phpcmf\Service::M()->db->table('attachment_unused')->where('id', $id)->get()->getRowArray();
            }
            if (!$info) {
                return null;
            }
        }

        // 合并变量
        $info = $data + $info;

        $info['file'] = SYS_UPLOAD_PATH.$info['attachment'];
        // 文件真实地址
        if ($info['remote']) {
            $remote = $this->get_cache('attachment', $info['remote']);
            if (!$remote) {
                // 远程地址无效
                $info['url'] = $info['file'] = '自定义附件（'.$info['remote'].'）的配置已经不存在';
                return $info;
            } else {
                $info['file'] = $remote['value']['path'].$info['attachment'];
            }
        }

        // 附件属性信息
        $info['attachinfo'] = dr_string2array($info['attachinfo']);

        $info['url'] = dr_get_file_url($info);

        \Phpcmf\Service::L('cache')->set_file('attach-info-'.$id, $info, 'attach');

        return $info;
    }

    /**
     * 引用404页面
     */
    public function goto_404_page($msg) {

        if (IS_API_HTTP) {
            exit($this->_json(0, $msg));
        }

        // 调试模式下不进行404状态码
        if (!CI_DEBUG) {
            http_response_code(404);
        }

        \Phpcmf\Service::V()->assign([
            'msg' => $msg,
            'meta_title' => dr_lang('你访问的页面不存在')
        ]);
        \Phpcmf\Service::V()->display('404.html');
        !defined('SC_HTML_FILE') && exit();
    }

    /**
     * 生成静态时的跳转提示
     */
    protected function _html_msg($code, $msg, $url = '', $note = '') {
        \Phpcmf\Service::V()->assign([
            'msg' => $msg,
            'url' => $url,
            'note' => $note,
            'mark' => $code
        ]);
        \Phpcmf\Service::V()->display('html_msg.html', 'admin');exit;
    }

    /**
     * 后台登录判断
     */
    protected function _is_admin_login() {
        return \Phpcmf\Service::M('auth')->_is_admin_login();
    }

    /**
     * 后台登录判断
     */
    public function _member_option($call = 1)
    {
        // 有用户组来获取最终的强制权限
        $this->member_cache['config']['complete'] = $this->member_cache['config']['mobile'] = $this->member_cache['config']['avatar'] = 0;
        // 强制完善资料
        if (!$this->member_cache['config']['complete']) {
            $this->member_cache['config']['complete'] = (int)\Phpcmf\Service::M('member_auth')->member_auth('complete', $this->member);
        }
        // 强制手机认证
        if (!$this->member_cache['config']['mobile']) {
            $this->member_cache['config']['mobile'] = (int)\Phpcmf\Service::M('member_auth')->member_auth('mobile', $this->member);
        }
        // 强制email认证
        if (!$this->member_cache['config']['email']) {
            $this->member_cache['config']['email'] = (int)\Phpcmf\Service::M('member_auth')->member_auth('email', $this->member);
        }
        // 强制头像上传
        if (!$this->member_cache['config']['avatar']) {
            $this->member_cache['config']['avatar'] = (int)\Phpcmf\Service::M('member_auth')->member_auth('avatar', $this->member);
        }

        if (!$this->member['is_verify']) {
            // 审核提醒
            $this->_msg(0, dr_lang('账号还没有通过审核'), dr_member_url('api/verify'));
        } elseif ($this->member_cache['config']['complete']
            && !$this->member['is_complete']
            &&\Phpcmf\Service::L('Router')->class != 'account') {
            // 强制完善资料
            $this->_msg(0, dr_lang('账号必须完善资料'), dr_member_url('account/index'));
        } elseif ($this->member_cache['config']['mobile']
            && !$this->member['is_mobile']
            &&\Phpcmf\Service::L('Router')->class != 'account') {
            // 强制手机认证
            $this->_msg(0, dr_lang('账号必须手机认证'), dr_member_url('account/mobile'));
        } elseif ($this->member_cache['config']['email']
            && !$this->member['is_email']
            &&\Phpcmf\Service::L('Router')->class != 'account') {
            // 强制邮箱认证
            $this->_msg(0, dr_lang('账号必须邮箱认证'), dr_member_url('account/email'));
        } elseif ($this->member_cache['config']['avatar']
            && !$this->member['is_avatar']
            &&\Phpcmf\Service::L('Router')->class != 'account') {
            // 强制头像上传
            $this->_msg(0, dr_lang('账号必须上传头像'), dr_member_url('account/avatar'));
        }
    }

    /**
     * 判断模块栏目是否具有用户操作权限
     */
    public function _get_module_member_category($module, $name) {

        if (!$module || !$module['category']) {
            return [];
        }

        $category = $module['category'];
        foreach ($category as $id => $t) {
            // 筛选可发布的栏目权限
            if (!$t['child']) {
                if ($t['mid'] != $module['dirname']) {
                    // 模块不符合 排除
                    unset($category[$id]);
                } elseif (!\Phpcmf\Service::M('member_auth')->category_auth($module, $id, $name, $this->member)) {
                    // 用户的的权限判断
                    unset($category[$id]);
                }
            }
        }

        return $category;
    }

    /**
     * 判断后台uri是否具有操作权限
     */
    public function _is_admin_auth($uri = '') {
        return \Phpcmf\Service::M('auth')->_is_admin_auth($uri);
    }

    /**
     * 是否移动端访问访问
     */
    public function _is_mobile() {
        return dr_is_mobile();
    }

    /**
     * 插件的clink值
     */
    protected function _app_clink($type = '')
    {
        $data = [];
        if (!$type) {
            // 表示模块部分
            $endfix = '';
        } else {
            $endfix = '_'.$type;
        }

        // 加载模块自身的
        if (is_file(APPPATH.'Config/Clink'.$endfix.'.php')) {
            $_clink = require APPPATH.'Config/Clink'.$endfix.'.php';
            if ($_clink) {
                if (is_file(APPPATH.'Models/Auth'.$endfix.'.php')) {
                    $obj = \Phpcmf\Service::M('auth'.$endfix.'', APP_DIR);
                    foreach ($_clink as $k => $v) {
                        // 动态名称
                        if (strpos($v['name'], '_') === 0 && method_exists($obj, substr($v['name'], 1))) {
                            $_clink[$k]['name'] = call_user_func(array($obj, substr($v['name'], 1)), APP_DIR);
                        }
                        // 对象存储
                        $_clink[$k]['model'] = $obj;
                    }
                    // 权限验证
                    if (method_exists($obj, 'is_link_auth') && $obj->is_link_auth(APP_DIR)) {
                        $data = $_clink;
                    }
                } else {
                    $data = $_clink;
                }
            }
        }

        // 加载全部插件的
        $local = \Phpcmf\Service::Apps();
        foreach ($local as $dir => $path) {
            // 排除模块自身
            if (strtolower($dir) == APP_DIR) {
                continue;
            }
            // 判断插件目录
            if (is_file($path.'install.lock') && is_file($path.'Config/Clink'.$endfix.'.php') && is_file($path.'Config/App.php')) {
                $cfg = require $path.'Config/App.php';
                if ($cfg['type'] == 'app' && !$cfg['ftype']) {
                    // 表示插件非模块
                    $_clink = require $path.'Config/Clink'.$endfix.'.php';
                    if ($_clink) {
                        if (is_file($path.'Models/Auth'.$endfix.'.php')) {
                            $obj = \Phpcmf\Service::M('auth'.$endfix, $dir);
                            foreach ($_clink as $k => $v) {
                                // 动态名称
                                if (strpos($v['name'], '_') === 0 && method_exists($obj, substr($v['name'], 1))) {
                                    $_clink[$k]['name'] = call_user_func(array($obj, substr($v['name'], 1)), APP_DIR);
                                }
                                // 对象存储
                                $_clink[$k]['model'] = $obj;
                            }
                            // 权限验证
                            if (method_exists($obj, 'is_link_auth') && $obj->is_link_auth(APP_DIR)) {
                                $data = array_merge($data , $_clink) ;
                            }
                        } else {
                            $data = array_merge($data , $_clink) ;
                            CI_DEBUG && log_message('error', '配置文件（'.$path.'Config/Clink'.$endfix.'.php'.'）没有定义权限验证类（'.$path.'Models/Auth'.$endfix.'.php'.'）');
                        }
                    }
                }
            }
        }

        if ($data) {
            foreach ($data as $i => $t) {
                if (IS_ADMIN) {
                    if (!$t['url']) {
                        unset($data[$i]); // 没有url
                        CI_DEBUG && !$t['murl'] && log_message('error', 'Clink（'.$t['name'].'）没有设置url参数');
                        continue;
                    } elseif ($t['uri'] && !$this->_is_admin_auth($t['uri'])) {
                        unset($data[$i]); // 无权限的不要
                        continue;
                    }
                    $data[$i]['url'] = urldecode($data[$i]['url']);
                } else {
                    if (!$t['murl']) {
                        unset($data[$i]); // 非后台必须验证murl
                        CI_DEBUG && !$t['url'] && log_message('error', 'Clink（'.$t['name'].'）没有设置murl参数');
                        continue;
                    }
                    $data[$i]['url'] = urldecode($data[$i]['murl']);
                }
            }
        }

        return $data;
    }

    /**
     * 插件的cbottom值
     */
    protected function _app_cbottom($type = '')
    {

        $data = [];
        if (!$type) {
            // 表示模块部分
            $data[] = [
                'icon' => 'fa fa-flag',
                'name' => dr_lang('推送到推荐位'),
                'uri' => APP_DIR.'/home/edit',
                'url' => 'javascript:;" onclick="dr_module_send(\''.dr_lang("推荐位").'\', \''.dr_url(APP_DIR.'/home/tui_edit').'&page=0\')',
            ];
            if ($this->module['setting']['sync_category']) {
                $data[] = [
                    'icon' => 'fa fa-refresh',
                    'name' => dr_lang('发布到其他栏目'),
                    'uri' => APP_DIR.'/home/edit',
                    'url' => 'javascript:;" onclick="dr_module_send(\''.dr_lang("发布到其他栏目").'\', \''.dr_url(APP_DIR.'/home/tui_edit').'&page=1\')',
                ];
            }
            $data[] = [
                'icon' => 'fa fa-clock-o',
                'name' => dr_lang('更新时间'),
                'uri' => APP_DIR.'/home/edit',
                'url' => 'javascript:;" onclick="dr_module_send_ajax(\''.dr_url(APP_DIR.'/home/tui_edit').'&page=4\')',
            ];
            $endfix = '';
        } else {
            $endfix = '_'.$type;
        }


        // 加载模块自身的
        if (APP_DIR && is_file(APPPATH.'Config/Cbottom'.$endfix.'.php')) {
            $_clink = require APPPATH.'Config/Cbottom'.$endfix.'.php';
            if ($_clink) {
                if (is_file(APPPATH.'Models/Auth'.$endfix.'.php')) {
                    $obj = \Phpcmf\Service::M('auth'.$endfix, APP_DIR);
                    if (method_exists($obj, 'is_bottom_auth') && $obj->is_bottom_auth(APP_DIR)) {
                        $data = array_merge($data , $_clink);
                    }
                } else {
                    $data = array_merge($data , $_clink);
                }
            }
        }

        // 加载全部插件的
        $local = \Phpcmf\Service::Apps();
        foreach ($local as $dir => $path) {
            // 排除模块自身
            if (strtolower($dir) == APP_DIR) {
                continue;
            }
            // 判断插件目录
            if (is_file($path.'install.lock') && is_file($path.'Config/Cbottom'.$endfix.'.php') && is_file($path.'Config/App.php')) {
                $cfg = require $path.'Config/App.php';
                if ($cfg['type'] == 'app' && !$cfg['ftype']) {
                    // 表示插件非模块
                    $_clink = require $path.'Config/Cbottom'.$endfix.'.php';
                    if ($_clink) {
                        if (is_file($path.'Models/Auth'.$endfix.'.php')) {
                            $obj = \Phpcmf\Service::M('auth'.$endfix, $dir);
                            if (method_exists($obj, 'is_bottom_auth') && $obj->is_bottom_auth(APP_DIR)) {
                                $data = array_merge($data , $_clink);
                            }
                        } else {
                            $data = array_merge($data , $_clink);
                        }
                    }
                }
            }
        }

        if ($data) {
            foreach ($data as $i => $t) {
                if (IS_ADMIN) {
                    if (!$t['url']) {
                        unset($data[$i]); // 没有url
                        CI_DEBUG && log_message('error', 'Cbottom（'.$t['name'].'）没有设置url参数');
                        continue;
                    } elseif ($t['uri'] && !$this->_is_admin_auth($t['uri'])) {
                        unset($data[$i]); // 无权限的不要
                        continue;
                    }
                    $data[$i]['url'] = urldecode($data[$i]['url']);
                } else {
                    if (!$t['murl']) {
                        unset($data[$i]); // 非后台必须验证murl
                        CI_DEBUG && log_message('error', 'Cbottom（'.$t['name'].'）没有设置murl参数');
                        continue;
                    }
                    $data[$i]['url'] = urldecode($data[$i]['murl']);
                }
            }
        }

        return $data;
    }

    /**
     * 获取可用后table区域
     */
    protected function _main_table()
    {
        // 默认的
        $data = [
            'couts' => '数据统计',
            'notice' => '通知提醒',
            'mylink' => '快捷链接',
            'share_category' => '共享栏目',
        ];

        if (is_file(MYPATH.'/Config/Main.php')) {
            $_data = require MYPATH.'/Config/Main.php';
            $_data && $data = dr_array22array($data, $_data);
        }

        // 执行插件自己的缓存程序
        $local = \Phpcmf\Service::Apps();
        foreach ($local as $dir => $path) {
            if (is_file($path.'install.lock')
                && is_file($path.'Config/Main.php')) {
                $_data = require $path.'Config/Main.php';
                if ($_data) {
                    foreach ($_data as $key => $name) {
                        $data[strtolower($dir).'-'.$key] = $name;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 官方短信接口查询
     */
    protected function _api_sms_info() {

        $uid = (int)\Phpcmf\Service::L('input')->get('uid');
        $key = dr_safe_replace(\Phpcmf\Service::L('input')->get('key'));
        if (!$uid || !$key) {
            $this->_json(0, dr_lang('uid或者key不能为空'));
        }

        $url = "https://www.xunruicms.com/index.php?s=vip&c=check&uid={$uid}&key={$key}";
        $data = dr_catcher_data($url);

        $this->_json(1, $data);
    }

    // 版本检查
    protected function _api_version_cmf() {
        $json = dr_catcher_data('https://www.xunruicms.com/version.php?action=new&id=1&time='.strtotime($this->cmf_version['downtime']).'&v='.$this->cmf_version['version']);
        exit($json);
    }

    // 版本检查
    protected function _api_version_cms() {
        $this->_api_version_cmf();
    }

    // 搜索帮助
    protected function _api_search_help() {

        $kw = dr_safe_replace(\Phpcmf\Service::L('input')->get('kw'));
        $url = 'https://www.xunruicms.com/index.php?s=doc&c=search&keyword='.$kw.'&is_phpcmf=cms';
        \Phpcmf\Service::V()->assign([
            'url' => $url,
        ]);
        \Phpcmf\Service::V()->display('cloud_online.html');
    }

    /**
     * (废弃)
     */
    public function _member_auth_value($authid, $name) {
        return 0;
    }
    /**
     * (废弃)
     */
    public function _member_value($authid, $value)
    {
        return 0;
    }
    /**
     * (废弃)
     */
    public function _module_member_value($catid, $dir, $auth, $authid = 0) {
        return 0;
    }
    /**
     * (废弃)
     */
    public function _module_member_category($category, $dir, $auth) {

        if (!$category) {
            return [];
        }

        foreach ($category as $id => $t) {
            // 筛选可发布的栏目权限
            if (!$t['child']) {
                if ($t['mid'] != $dir) {
                    // 模块不符合 排除
                    unset($category[$id]);
                }
            }
        }

        return $category;
    }

    /**
     * Get the CI singleton
     */
    public static function &get_instance()
    {
        return self::$instance;
    }

}