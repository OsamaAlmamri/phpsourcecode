<?php

/**
 * 分类管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\order\admin;

class CouponClassAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'OrderCouponClass';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '优惠券分类',
                'description' => '管理优惠券分类',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'name'
        ];
    }

    public function _indexOrder() {
        return 'sort ASC,class_id ASC';
    }

    public function _indexPage() {
        return 100;
    }

}