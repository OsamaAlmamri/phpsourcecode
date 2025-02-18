<?php
namespace app\index\controller;

//use think\Controller;
use app\common\controller\BaseHome;
use app\common\model\Arctype;
use app\common\model\Archive;

class Category extends BaseHome {
    public function initialize(){
        parent::initialize();
    }

    public function index($dirs)
    {
        $arctypeModel = new Arctype();
        $arctype = $arctypeModel->field(true)->where(['dirs'=>$dirs, 'status'=>1])->order('id DESC')->find();
        if (!$arctype){
            //跳转404
            exit("404");
        }
        $arctype->arctypeMod;
        if ($arctype->arctypeMod->mod == 'addonpage'){
            return $this->tpl_page($arctype);
        }else{
            return $this->tpl_list($arctype);
        }
    }

    private function tpl_list($arctype) {	//文章模型
        $typeid_arr = cache('ARCTYPE_ARR_'.$arctype['id']);
        if(!$typeid_arr){
            $arctypeModel = new Arctype();
            $typeid_arr = $arctypeModel->allChildArctype($arctype['id']);
            cache('ARCTYPE_ARR_'.$arctype['id'], $typeid_arr);
        }
		$where[] = ['typeid','in',$typeid_arr];
        if (input('get.search')){
            $where[] = ['title','like', '%'.input('get.search').'%'];
        }
        $archiveModel = new Archive();
        $dataList = $archiveModel->where($where)->where(['status' => '1'])->order('id DESC')
        ->paginate($arctype['pagesize'], false, ['query'=> ['search' => input('get.search')]]);

        $arctypeModel = new Arctype();
        if($arctype['pid'] == '0'){
            $parent = $arctype;
        }else{
            $parent = $arctypeModel->topArctypeData($arctype['pid']);
        }
		$typelist = $arctypeModel->where(['pid'=>$parent['id'],'status'=>1])->select();
        $this->assign('typelist', $typelist);   //当前栏目顶级的子栏目信息
        $this->assign('parent', $parent);   //当前栏目顶级栏目信息
        $this->assign('arctype', $arctype);   //当前栏目信息
        $this->assign('dataList', $dataList);   //列表数据【包含无限子类数据】
        return $this->fetch($arctype['templist']);   //栏目模板
    }

    private function tpl_page($arctype) {	//单页模型
        $arctypeModel = new Arctype();
        if($arctype['pid'] == '0'){
            $parent = $arctype;
        }else{
            $parent = $arctypeModel->topArctypeData($arctype['pid']);
        }
		$typelist = $arctypeModel->where(['pid'=>$parent['id']])->select();
        $this->assign('typelist', $typelist);   //当前栏目顶级的子栏目信息
        $this->assign('parent', $parent);   //当前栏目顶级栏目信息
        $this->assign('arctype', $arctype);   //当前栏目信息
        return $this->fetch($arctype['templist']);   //栏目模板
    }




}
