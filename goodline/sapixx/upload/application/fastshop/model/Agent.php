<?php
/**
 * @copyright   Copyright (c) 2017 https://www.sapixx.com All rights reserved.
 * @license     Licensed (http://www.apache.org/licenses/LICENSE-2.0).
 * @author      pillar<ltmn@qq.com>
 * 代理管理
 */
namespace app\fastshop\model;
use think\Model;

class Agent extends Model{
    
    protected $pk     = 'id';
    protected $table  = 'ai_fastshop_agent';
  
     /**
      * 代理列表
     */
    public function lists($condition){
        return self::view('fastshop_agent','user_id,rebate,id as agent_id')
        ->view('system_user','*','fastshop_agent.user_id = system_user.id')->order('fastshop_agent.id desc')
        ->where($condition)
        ->paginate(20);
    }

    /**
      * 选择代理用户类别
     */
    public function selects($condition){
        $user = self::where(['member_miniapp_id' => $condition['member_miniapp_id']])->field('user_id')->select()->toArray();
        $user_id = [];
        if(!empty($user)){
            $user_id = array_column($user,'user_id');
        } 
        return model('SystemUser')->where($condition)->whereNotIn('id',$user_id)->order('id desc')->paginate(10,false);
    }

    /**
      * 读取代理用户ID(API使用)
     */
    public function agentUid(array $condition,$miniapp_id){
        $level = model('SystemUserLevel')->where($condition)->field('parent_id,level,user_id')->select()->toArray();
        if(empty($level)){
            return;
        }
        $uid = array_column($level,'parent_id');
        $agent = model('Agent')->where(['member_miniapp_id' => $miniapp_id])->whereIn('user_id',$uid)->select()->toArray();
        if(empty($agent)){
            return;
        }
        $agent_uid = [];
        foreach ($agent as $key => $value) {
            $agent_uid[$key]['user_id'] = $value['user_id'];
            $agent_uid[$key]['rebate']  = $value['rebate'];
        }
        rsort($agent_uid);
        return empty($agent_uid[0]) ? false : $agent_uid[0];
    }

    /**
     * 添加编辑
     * @param  array $param 数组
     */
    public function add(int $miniapp_id,array $ids){
        $data = [];
        $user = self::where(['member_miniapp_id' => $miniapp_id,'user_id' => $ids])->field('user_id')->select()->toArray();
        $user_id = [];
        if(!empty($user)){
            $user_id = array_column($user,'user_id');
        }
        foreach ($ids as $key => $value) {
            if(!in_array($value,$user_id)){
                $data[$key]['member_miniapp_id'] = $miniapp_id;
                $data[$key]['user_id']           = $value;
                $data[$key]['rebate']            = 0;
            }
        }
        if(empty($data)){
            return;
        }
        return self::insertAll($data);
    }
}