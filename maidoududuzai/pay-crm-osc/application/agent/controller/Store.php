<?php

namespace app\agent\controller;

use \think\Db;

class Store
{

	public $agent;

	public function __construct()
	{
		$this->agent = model('Agent')->checkLoginAgent();
	}
	
	public function index()
	{
		$where = [];
		$where['s.agent_id'] = ['=', $this->agent['agent_id']];
		if(input('param.wd')) {
			$where['s.store_name'] = ['LIKE', '%'.input('param.wd').'%'];
		}
		if(input('param.store_id')) {
			$where['s.store_id'] = ['=', input('param.store_id')];
		} else {
			if(input('param.store_name')) {
				$where['s.store_name'] = ['LIKE', '%'.input('param.store_name').'%'];
			}
		}
		if(input('param.merchant_id')) {
			$where['m.merchant_id'] = ['=', input('param.merchant_id')];
		} else {
			if(input('param.merchant_name')) {
				$where['m.merchant_name'] = ['LIKE', '%'.input('param.merchant_name').'%'];
			}
		}
		$object = Db::name('store')
			->alias('s')
			->join('merchant m', 'm.merchant_id = s.merchant_id', 'LEFT')
			->where($where)
			->order('store_id', 'DESC')
			->field('s.*, m.merchant_name')
			->paginate(20, false, ['query' => request()->param()]);
		$array = $object->toArray();
		$total = $array['total'];
		$list = $array['data'];
		$per_page = $array['per_page'];
		$current_page = $array['current_page'];
		$last_page = $array['last_page'];
		$pagenav = $object->render();
		include \befen\view();
	}

}

