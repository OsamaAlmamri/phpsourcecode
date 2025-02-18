<?php
namespace Lain\Phpcms;

class content_tag {
	private $db;
	public function __construct() {
		$this->db = D('content');
		$this->position = M('position_data');
	}
	/**
	 * 初始化模型
	 * @param $catid
	 */
	public function set_modelid($catid) {
		if(empty($catid))
			return false;
		$this->category = F('category_content');
		if(!isset($this->category[$catid]) || $this->category[$catid]['type'] != 0) return false;
		$this->modelid = $this->category[$catid]['modelid'];
		$this->db->set_model($this->modelid);
		$this->tablename = $this->db->table_name;
		if(empty($this->category)) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * 分页统计
	 * @param $data
	 */
	public function count($data) {
		if($data['action'] == 'lists') {
			$catid = intval($data['catid']);
			if(!$this->set_modelid($catid)) return false;
			if(isset($data['where'])) {
				$sql = $data['where'];
			} else {
				if($this->category[$catid]['child']) {
					$catids_str = $this->category[$catid]['arrchildid'];
					$pos = strpos($catids_str,',')+1;
					$catids_str = substr($catids_str, $pos);
					$sql = "status=99 AND catid IN ($catids_str)";
				} else {
					$sql = "status=99 AND catid='$catid'";
				}
			}
			return $this->db->count($sql);
		}
	}
	
	/**
	 * 列表页标签
	 * @param $data
	 */
	public function lists($data) {
		$page = max(1, intval($data['page']));
		$data['num'] = isset($data['num']) ? $data['num'] : 10;
		$catid = intval($data['catid']);
		if(!$this->set_modelid($catid)) return false;

		$map = [];
		if(isset($data['where'])) {
			$map = $data['where'];
		} else {
			if(intval($data['thumb'])){
				$map['thumb'] = ['neq', ''];
			}
			if($this->category[$catid]['child']) {
				$catids_str = $this->category[$catid]['arrchildid'];
				$pos = strpos($catids_str,',')+1;
				$catids_str = substr($catids_str, $pos);
				$map['status'] = 99;
				$map['catid'] = ['in', $catids_str];
				// $sql = "status=99 AND catid IN ($catids_str)".$thumb;
			} else {
				$map['status'] = 99;
				$map['catid'] = $catid;
				// $sql = "status=99 AND catid='$catid'".$thumb;
			}
		}
		$order = $data['order'];
		$return = $this->db->where($map)->order($order)->index('id')->page($page, $data['num'])->select();
		// $return = $this->db->select($sql, '*', $data['limit'], $order, '', 'id');
						
		//调用副表的数据
		if (isset($data['moreinfo']) && intval($data['moreinfo']) == 1) {
			$ids = array();
			foreach ($return as $v) {
				if (isset($v['id']) && !empty($v['id'])) {
					$ids[] = $v['id'];
				} else {
					continue;
				}
			}
			if (!empty($ids)) {
				$this->db->table_name = $this->db->table_name.'_data';
				$ids = implode('\',\'', $ids);
				$r = $this->db->select("`id` IN ('$ids')", '*', '', '', '', 'id');
				if (!empty($r)) {
					foreach ($r as $k=>$v) {
						if (isset($return[$k])) $return[$k] = array_merge($v, $return[$k]);
					}
				}
			}
		}
		return $return;
	}
	

	public function pages($data) {
		$catid = intval($data['catid']);
		if(!$this->set_modelid($catid)) return false;

		$map = [];
		if(isset($data['where'])) {
			$map = $data['where'];
		} else {
			if(intval($data['thumb'])){
				$map['thumb'] = ['neq', ''];
			}
			if($this->category[$catid]['child']) {
				$catids_str = $this->category[$catid]['arrchildid'];
				$pos = strpos($catids_str,',')+1;
				$catids_str = substr($catids_str, $pos);
				$map['status'] = 99;
				$map['catid'] = ['in', $catids_str];
				// $sql = "status=99 AND catid IN ($catids_str)".$thumb;
			} else {
				$map['status'] = 99;
				$map['catid'] = $catid;
				// $sql = "status=99 AND catid='$catid'".$thumb;
			}
		}
		$pagesize = $data['num'];
		$count = $this->db->where($map)->count();
		// $return = $this->db->select($sql, '*', $data['limit'], $order, '', 'id');
		$Page = new \Think\Page($count, $pagesize);
		return $Page->show();
	}
	/**
	 * 相关文章标签
	 * @param $data
	 */
	public function relation($data) {
		$catid = intval($data['catid']);
		$modelid = intval($data['modelid']);
		if(!$this->set_modelid($catid) && $modelid) {
			$this->db->set_model($modelid);
			$this->tablename = $this->db->table_name;
		} elseif(!$this->set_modelid($catid)) {
			return false;
		}
		$order = $data['order'];
		$sql = "`status`=99";
		$limit = $data['id'] ? $data['limit']+1 : $data['limit'];
		if($data['relation']) {
			$relations = explode('|',trim($data['relation'],'|'));
			$relations = array_diff($relations, array(null));
			$relations = implode(',',$relations);
			$sql = " `id` IN ($relations)";
			$key_array = $this->db->select($sql, '*', $limit, $order,'','id');
		} elseif($data['keywords']) {
			$keywords = str_replace(array('%',"'"), '',$data['keywords']);
			$keywords_arr = explode(' ',$keywords);
			$key_array = array();
			$number = 0;
			$i =1;
			$sql .= " AND catid='$catid'";
			foreach ($keywords_arr as $_k) {
				$sql2 = $sql." AND `keywords` LIKE '%$_k%'".(isset($data['id']) && intval($data['id']) ? " AND `id` != '".abs(intval($data['id']))."'" : '');
				$r = $this->db->select($sql2, '*', $limit, '','','id');
				$number += count($r);
				foreach ($r as $id=>$v) {
					if($i<= $data['limit'] && !in_array($id, $key_array)) $key_array[$id] = $v;
					$i++;
				}
				if($data['limit']<$number) break;
			}
		}
		if($data['id']) unset($key_array[$data['id']]);
		return $key_array;
	}
	
	/**
	 * 排行榜标签
	 * @param $data
	 */
	public function hits($data) {
		$catid = intval($data['catid']);
		if(!$this->set_modelid($catid)) return false;

		$this->hits_db = pc_base::load_model('hits_model');
		$sql = $desc = $ids = '';
		$array = $ids_array = array();
		$order = $data['order'];
		$hitsid = 'c-'.$this->modelid.'-%';
		$sql = "hitsid LIKE '$hitsid'";
		if(isset($data['day'])) {
			$updatetime = SYS_TIME-intval($data['day'])*86400;
			$sql .= " AND updatetime>'$updatetime'";
		}
		if($this->category[$catid]['child']) {
			$catids_str = $this->category[$catid]['arrchildid'];
			$pos = strpos($catids_str,',')+1;
			$catids_str = substr($catids_str, $pos);
			$sql .= " AND catid IN ($catids_str)";
		} else {
			$sql .= " AND catid='$catid'";
		}
		$hits = array();
		$result = $this->hits_db->select($sql, '*', $data['limit'], $order);
		foreach ($result as $r) {
			$pos = strpos($r['hitsid'],'-',2) + 1;
			$ids_array[] = $id = substr($r['hitsid'],$pos);
			$hits[$id] = $r;
		}
		$ids = implode(',', $ids_array);
		if($ids) {
			$sql = "status=99 AND id IN ($ids)";
		} else {
			$sql = '';
		}
		$this->db->table_name = $this->tablename;
		$result = $this->db->select($sql, '*', $data['limit'],'','','id');
		foreach ($ids_array as $id) {
			if($result[$id]['title']!='') {
				$array[$id] = $result[$id];
				$array[$id] = array_merge($array[$id], $hits[$id]);
			}
		}
		return $array;
	}
	/**
	 * 栏目标签
	 * @param $data
	 */
	public function category($data) {
		$data['limit'] = isset($data['limit']) ? $data['limit'] : 20;
		$data['catid'] = intval($data['catid']);
	    $categorys = F('category_content');
	    // $menu = list_to_tree($categorys,'catid','parentid','_child');
		// var_dump($categorys);exit;
	    $array = [];
	    $i = 1;
	    foreach ($categorys as $catid => $cat) {
	        /*if($value['catid'] == $data['catid']){
	            $category_product_list = $value['_child'];
	            break;
	        }*/
	        if($i > $data['limit']) break;
	        if($cat['parentid'] == $data['catid']) {
				$array[$catid] = $cat;
				$i++;
			}
	    }
	    return $array;

		// $data['catid'] = intval($data['catid']);
		// $array = array();
		// $siteid = 1;
		// $categorys = getcache('category_content_'.$siteid,'commons');
		// $site = siteinfo($siteid);
		// $i = 1;
		// foreach ($categorys as $catid=>$cat) {
		// 	if($i>$data['limit']) break;
		// 	if((!$cat['ismenu']) || $siteid && $cat['siteid']!=$siteid) continue;
		// 	if (strpos($cat['url'], '://') === false) {
		// 		$cat['url'] = substr($site['domain'],0,-1).$cat['url'];
		// 	}
		// 	if($cat['parentid']==$data['catid']) {
		// 		$array[$catid] = $cat;
		// 		$i++;
		// 	}
		// }
		// return $array;
	}
	
	/**
	 * 推荐位
	 * @param $data
	 */
	public function position($data) {
		$sql = '';
		$array = array();
		$posid = intval($data['posid']);
		$order = $data['order'];
		$thumb = (empty($data['thumb']) || intval($data['thumb']) == 0) ? 0 : 1;
		$catid = (empty($data['catid']) || $data['catid'] == 0) ? '' : intval($data['catid']);

		$map = [];
		if($catid) {
			$siteids = getcache('category_content','commons');
			if(!$siteids[$catid]) return false;
			$siteid = $siteids[$catid];
			$this->category = getcache('category_content_'.$siteid,'commons');
		}

		if($catid && $this->category[$catid]['child']) {
			$catids_str = $this->category[$catid]['arrchildid'];
			$pos = strpos($catids_str,',')+1;
			$catids_str = substr($catids_str, $pos);
			$sql = "`catid` IN ($catids_str) AND ";
		}  elseif($catid && !$this->category[$catid]['child']) {
				$sql = "`catid` = '$catid' AND ";
		}
		if($thumb) 
			$map['thumb'] = 1;
		if(isset($data['where'])) 
			$map = array_merge($map, $data['where']);
		// if(isset($data['expiration']) && $data['expiration']==1) $sql .= '(`expiration` >= \''.SYS_TIME.'\' OR `expiration` = \'0\' ) AND ';
		$map['posid'] = $posid;
		// $sql .= "`posid` = '$posid' AND `siteid` = '".$siteid."'";
		$pos_arr = $this->position->where($map)->limit($data['num'])->order($order)->select();
		// $pos_arr = $this->position->select($sql, '*', $data['limit'],$order);
		if(!empty($pos_arr)) {
			foreach ($pos_arr as $info) {
				$key = $info['catid'].'-'.$info['id'];
				$array[$key] = string2array($info['data']);
				$array[$key]['url'] = getUrl($info['catid'],$info['id']);
				$array[$key]['id'] = $info['id'];
				$array[$key]['catid'] = $info['catid'];
				$array[$key]['listorder'] = $info['listorder'];
			}
		}
		return $array;
	}

	public function poster($data){
		$poster_project = D('Poster')->getDetailById($data['id']);
        $project_list = $poster_project['setting'];
        return $project_list;
	}
	/**
	 * 可视化标签
	 */
	public function pc_tag() {
		$positionlist = getcache('position','commons');
		$sites = pc_base::load_app_class('sites','admin');
		$sitelist = $sites->pc_tag_list();
		
		foreach ($positionlist as $_v) if($_v['siteid'] == get_siteid() || $_v['siteid'] == 0) $poslist[$_v['posid']] = $_v['name'];
		return array(
			'action'=>array('lists'=>L('list','', 'content'),'position'=>L('position','', 'content'), 'category'=>L('subcat', '', 'content'), 'relation'=>L('related_articles', '', 'content'), 'hits'=>L('top', '', 'content')),
			'lists'=>array(
				'catid'=>array('name'=>L('catid', '', 'content'),'htmltype'=>'input_select_category','data'=>array('type'=>0),'validator'=>array('min'=>1)),
				'order'=>array('name'=>L('sort', '', 'content'), 'htmltype'=>'select','data'=>array('id DESC'=>L('id_desc', '', 'content'), 'updatetime DESC'=>L('updatetime_desc', '', 'content'), 'listorder ASC'=>L('listorder_asc', '', 'content'))),
				'thumb'=>array('name'=>L('thumb', '', 'content'), 'htmltype'=>'radio','data'=>array('0'=>L('all_list', '', 'content'), '1'=>L('thumb_list', '', 'content'))),
				'moreinfo'=>array('name'=>L('moreinfo', '', 'content'), 'htmltype'=>'radio', 'data'=>array('1'=>L('yes'), '0'=>L('no')))
			),
			'position'=>array(
				'posid'=>array('name'=>L('posid', '', 'content'),'htmltype'=>'input_select','data'=>$poslist,'validator'=>array('min'=>1)),
				'catid'=>array('name'=>L('catid', '', 'content'),'htmltype'=>'input_select_category','data'=>array('type'=>0),'validator'=>array('min'=>0)),
				'thumb'=>array('name'=>L('thumb', '', 'content'), 'htmltype'=>'radio','data'=>array('0'=>L('all_list', '', 'content'), '1'=>L('thumb_list', '', 'content'))),			
				'order'=>array('name'=>L('sort', '', 'content'), 'htmltype'=>'select','data'=>array('listorder DESC'=>L('listorder_desc', '', 'content'),'listorder ASC'=>L('listorder_asc', '', 'content'),'id DESC'=>L('id_desc', '', 'content'))),
			),
			'category'=>array(
				'siteid'=>array('name'=>L('siteid'), 'htmltype'=>'input_select', 'data'=>$sitelist),
				'catid'=>array('name'=>L('catid', '', 'content'), 'htmltype'=>'input_select_category', 'data'=>array('type'=>0))
			),
			'relation'=>array(
				'catid'=>array('name'=>L('catid', '', 'content'), 'htmltype'=>'input_select_category', 'data'=>array('type'=>0), 'validator'=>array('min'=>1)),
				'order'=>array('name'=>L('sort', '', 'content'), 'htmltype'=>'select','data'=>array('id DESC'=>L('id_desc', '', 'content'), 'updatetime DESC'=>L('updatetime_desc', '', 'content'), 'listorder ASC'=>L('listorder_asc', '', 'content'))),
				'relation'=>array('name'=>L('relevant_articles_id', '', 'content'), 'htmltype'=>'input'),
				'keywords'=>array('name'=>L('key_word', '', 'content'), 'htmltype'=>'input')
			),
			'hits'=>array(
				'catid'=>array('name'=>L('catid', '', 'content'), 'htmltype'=>'input_select_category', 'data'=>array('type'=>0), 'validator'=>array('min'=>1)),
				'day'=>array('name'=>L('day_select', '', 'content'), 'htmltype'=>'input', 'data'=>array('type'=>0)),
			),
				
		);
	}
}