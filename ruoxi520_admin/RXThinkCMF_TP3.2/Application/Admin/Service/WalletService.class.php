<?php
// +----------------------------------------------------------------------
// | RXThink框架 [ RXThink ]
// +----------------------------------------------------------------------
// | 版权所有 2017~2019 南京RXThink工作室
// +----------------------------------------------------------------------
// | 官方网站: http://www.rxthink.cn
// +----------------------------------------------------------------------
// | Author: 牧羊人 <rxthink@gmail.com>
// +----------------------------------------------------------------------

/**
 * 钱包-服务类
 * 
 * @author 牧羊人
 * @date 2019-01-08
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
use Admin\Model\WalletModel;
use Admin\Model\UserModel;
class WalletService extends ServiceModel {
    function __construct() {
        parent::__construct();
        $this->mod = new WalletModel();
    }
    
    /**
     * 获取数据列表
     * 
     * @author 牧羊人
     * @date 2019-01-08
     * (non-PHPdoc)
     * @see \Admin\Model\ServiceModel::getList()
     */
    function getList() {
        $param = I("request.");
        
        //查询条件
        $map = [];
        
        //手机号
        $mobile = trim($param['mobile']);
        if($mobile) {
            $userMod = new UserModel();
            $userInfo = $userMod->getRowByAttr([
                'mobile'=>$mobile,
            ]);
            $map['user_id'] = $userInfo['id'];
        }
        
        return parent::getList($map);
    }
    
}