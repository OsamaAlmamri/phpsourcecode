<?php
// +----------------------------------------------------------------------
// | YunCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://www.yunalading.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jabber <2898117012@qq.com>
// +----------------------------------------------------------------------


namespace app\install\controller;


/**
 * Class Complete
 * @package app\install\controller
 */
class Complete extends InstallBaseController {
    public function __construct() {
        parent::__construct();
        $this->checkInstall();
    }

    /**
     * @return \think\response\View
     */
    public function index() {
        return view();
    }
}
