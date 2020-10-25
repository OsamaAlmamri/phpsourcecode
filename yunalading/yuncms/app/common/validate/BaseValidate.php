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


namespace app\common\validate;

use think\Request;
use think\Validate;

/**
 * Class BaseValidate
 * @package app\common\validate
 */
abstract class BaseValidate extends Validate {
    /**
     * 重写check方法，自动从POST中添加token数据
     * @param array $data
     * @param array $rules
     * @param string $scene
     * @return bool
     */
    public function check($data, $rules = [], $scene = '') {
        $request = Request::instance();
        if ($request->isPost()) {
            if ($request->post('__token__')) {
                $data['__token__'] = $request->post('__token__');
            }
        }
        return parent::check($data, $rules, $scene);
    }
}
