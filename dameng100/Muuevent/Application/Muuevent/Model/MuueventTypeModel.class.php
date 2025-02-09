<?php
namespace Muuevent\Model;
use Think\Model;
use Think\Page;

class MuueventTypeModel extends Model{
    
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_UPDATE),
        array('status', '1', self::MODEL_INSERT),
    );


    /**
     * 获取活动分类树
     * @param int $id
     * @param bool $field
     * @return array
     * autor:陈一枭
     */
    public function getTree($id = 0, $field = true){
        /* 获取当前分类信息 */
        if($id){
            $info = $this->info($id);
            $id   = $info['id'];
        }

        /* 获取所有分类 */
        $map  = array('status' => array('gt', -1));
        $list = $this->field($field)->where($map)->order('sort')->select();
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);


        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }

        return $info;
    }

    /**
     * 获取分类详细信息
     * @param  milit   $id 分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     分类信息
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function info($id, $field = true){
        /* 获取分类信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }

    public function editData($data)
    {
        $data=$this->create();
        if($data['id']){
            $res=$this->save($data);
        }else{
            $res=$this->add($data);
        }
        return $res;
    }

    public function getType($type_id)
    {
        $type = $this->where('id=' . $type_id)->find();
        return $type;
    }

    public function getTypeListByPid($pid){
        if(!$pid) $pid=0;
        $map['pid'] = $pid;
        $list=$this->where($map)->order('sort asc')->select();
        return $list;
    }

    public function getTypeList($map,$type=0)
    {
        $list=$this->where($map)->order('sort asc')->select();
        if(!$type){
            return $list;
        }
        $father_list=$child_list=array();
        foreach($list as $val){
            if($val['pid']==0){
                $father_list[]=$val;
            }else{
                $val['title']='[子分类]'.$val['title'];
                $child_list[]=$val;
            }
        }
        $cateList=array();
        foreach($father_list as $val){
            $cateList[]=$val;
            foreach($child_list as $tt){
                if($tt['pid']==$val['id']){
                    $cateList[]=$tt;
                }
            }
        }
        return $cateList;
    }
}
