<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/* v3.1.0  */
	
class Form_model extends CI_Model {

	public $link;
	public $prefix;
	
	/**
	 * 内容扩展模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->prefix = $this->db->dbprefix(SITE_ID.'_form');
    }
	
	/**
	 * 添加扩展模型
	 * 
	 * @param	array	$data
	 * @return	string|TRUE
	 */
	public function add($data) {
	
		if (!$data['name'] || !$data['table']) {
            return fc_lang('名称或者表名称不能为空');
        } elseif (!preg_match('/^[a-z0-9]+$/i', $data['table'])) {
            return fc_lang('表名称格式不正确');
        } elseif ($this->db->where('table', $data['table'])->count_all_results($this->prefix)) {
			return fc_lang('表名称已经存在');
		}
		
		$data['setting'] = dr_array2string($data['setting']);
		
		if ($this->db->insert($this->prefix, $data)) {
			
			$id = $this->db->insert_id();
			
			$name = 'Form_'.$data['table'];
			$file = FCPATH.'dayrui/controllers/admin/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function add() {'.PHP_EOL
			.'		$this->_addc();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function edit() {'.PHP_EOL
			.'		$this->_editc();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function index() {'.PHP_EOL
			.'		$this->_listc();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'}')) {
				$this->db->where('id', $id)->delete($this->prefix);
				return fc_lang('目录(%s)没有写入权限', '/dayrui/controllers/admin/');
			}
			
			$file = FCPATH.'dayrui/controllers/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require_once FCPATH.\'dayrui/core/D_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function index() {'.PHP_EOL
			.'		$this->_post();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'}')) {
				$this->db->where('id', $id)->delete($this->prefix);
				return fc_lang('目录(%s)没有写入权限', '/dayrui/controllers/');
			}
			
			$sql = "
			CREATE TABLE IF NOT EXISTS `".$this->prefix.'_'.$data['table']."` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL COMMENT '主题',
			  `uid` int(10) unsigned DEFAULT 0 COMMENT '录入者uid',
			  `author` varchar(100) DEFAULT NULL COMMENT '录入者账号',
			  `inputip` varchar(30) DEFAULT NULL COMMENT '录入者ip',
			  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
			  `displayorder` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序值',
	          `tableid` smallint(5) unsigned NOT NULL COMMENT '附表id',
			  PRIMARY KEY `id` (`id`),
			  KEY `uid` (`uid`),
			  KEY `inputtime` (`inputtime`),
			  KEY `displayorder` (`displayorder`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='".$data['name']."表单表';";
			$this->db->query($sql);

            $sql = "
			CREATE TABLE IF NOT EXISTS `".$this->prefix.'_'.$data['table']."_data_0` (
			  `id` int(10) unsigned NOT NULL,
			  `uid` int(10) unsigned DEFAULT 0 COMMENT '录入者uid',
			  UNIQUE KEY `id` (`id`),
			  KEY `uid` (`uid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='".$data['name']."表单附表';";
            $this->db->query($sql);
			
			$this->db->insert('field', array(
				'name' => '主题',
				'fieldname' => 'title',
				'fieldtype' => 'Text',
				'relatedid' => $id,
				'relatedname' => 'form-'.SITE_ID,
				'isedit' => 1,
				'ismain' => 1,
				'ismember' => 1,
				'issystem' => 1,
				'issearch' => 1,
				'disabled' => 0,
				'setting' => dr_array2string(array(
					'option' => array(
						'width' => 400, // 表单宽度
						'fieldtype' => 'VARCHAR', // 字段类型
						'fieldlength' => '255' // 字段长度
					),
					'validate' => array(
						'xss' => 1, // xss过滤
						'required' => 1, // 表示必填
					)
				)),
				'displayorder' => 0,
			));
		}
		
		return TRUE;
	}
	
	/**
	 * 修改模型
	 * 
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	void
	 */
	public function edit($id, $data) {
		$this->db->where('id', (int)$id)->update($this->prefix, array(
			'name' => $data['name'],
			'setting' => dr_array2string($data['setting']),
		));
	}
	
	/**
	 * 删除
	 * 
	 * @param	intval	$id
	 * @param	intval	$sid
	 */
	public function del($id, $sid = SITE_ID) {
		
		if (!$id) {
            return NULL;
        }
		
		$sid = $sid ? $sid : SITE_ID;
		$prefix = $this->db->dbprefix($sid.'_form');
		
		// 数据查询
		$data = $this->site[$sid]->where('id', (int)$id)->get($prefix)->row_array();
		if (!$data) {
            return NULL;
        }
		
		// 删除字段
		$this->db->where('relatedid', (int)$id)->where('relatedname', 'form-'.$sid)->delete('field');

        // 删除表
		$table = $prefix.'_'.$data['table'];
		$this->site[$sid]->query('DROP TABLE IF EXISTS `'.$table.'`');

        // 删除附表
        for ($i = 0; $i < 100; $i ++) {
            if (!$this->site[$sid]->query("SHOW TABLES LIKE '".$table.'_data_'.$i."'")->row_array()) {
                break;
            }
            $this->site[$sid]->query('DROP TABLE IF EXISTS '.$table.'_data_'.$i);
        }

        // 删除记录
		$this->site[$sid]->where('id', (int)$id)->delete($prefix);

        // 删除附件
		$this->load->model('attachment_model');
		$this->attachment_model->delete_for_table($table, TRUE);

        // 删除文件
		$name = 'Form_'.$data['table'];
		@unlink(FCPATH.'dayrui/controllers/'.$name.'.php');
		@unlink(FCPATH.'dayrui/controllers/admin/'.$name.'.php');
		@unlink(FCPATH.'dayrui/templates/admin/form_'.$id.'.html');
		
		return TRUE;
	}
	
	/**
	 * 生成缓存
	 * 
	 * @return	void
	 */
	public function cache($siteid = SITE_ID) {
		
		$siteid = $siteid ? $siteid : SITE_ID;
		$this->dcache->delete('form-'.$siteid);
		$this->dcache->delete('form-name-'.$siteid);

		$form = $this->db->get($siteid.'_form')->result_array();
		if (!$form) {
            return NULL;
        }
		
		$name = $cache = array();

		// 删除全部菜单
		$this->db->where('mark', 'form')->delete('admin_menu');

		foreach ($form as $t) {
			$data = $this->db
                         ->where('disabled', 0)
						 ->where('relatedid', (int)$t['id'])
						 ->where('relatedname', 'form-'.$siteid)
						 ->order_by('displayorder ASC,id ASC')
						 ->get('field')
						 ->result_array();
			if ($data) {
				foreach ($data as $field) {
					$field['setting'] = dr_string2array($field['setting']);
					$t['field'][$field['fieldname']] = $field;
				}
			}
			$t['setting'] = dr_string2array($t['setting']);
            $name[$t['table']] = $cache[$t['id']] = $t;
			// 增加到后台菜单上
			$leftid = 0;
			$left = $this->db->where('mark', 'form')->get('admin_menu')->row_array();
			if (!$left) {
				$top = $this->db->where('mark', 'top_site')->get('admin_menu')->row_array();
				if ($top) {
					$this->system_model->add_admin_menu(array(
						'uri' => '',
						'pid' => $top['id'],
						'mark' => 'form',
						'name' => fc_lang('表单内容'),
						'icon' => 'fa fa-table',
						'hidden' => 0,
						'displayorder' => 4,
					));
					$leftid = $this->db->insert_id();
				}
			} else {
				$leftid = intval($left['id']);
			}
			if ($leftid) {
				$this->system_model->add_admin_menu(array(
					'pid' => $leftid,
					'uri' => 'admin/form_'.$t['table'].'/index',
					'mark' => 'site-from',
					'name' => $t['name'],
					'icon' => $t['setting']['icon'] ? $t['setting']['icon'] : 'fa fa-table',
					'hidden' => 0,
					'displayorder' => 0,
				));
			}
		}

        $this->ci->clear_cache('form-'.$siteid);
        $this->ci->clear_cache('form-name-'.$siteid);
		$this->dcache->set('form-'.$siteid, $cache);
		$this->dcache->set('form-name-'.$siteid, $name);

		// 更新菜单缓存
		$this->load->model('menu_model');
		$this->menu_model->cache();

        return $cache;
	}



    /**
     * 条件查询
     *
     * @param	object	$select	查询对象
     * @param	array	$param	条件参数
     * @return	array
     */
    private function _where(&$select, $param) {

        if (isset($param['keyword']) && $param['keyword'] != '') {
            $field = $this->form['field'];
            $param['field'] = $param['field'] ? $param['field'] : 'title';
            if ($param['field'] == 'id' || $param['field'] == 'id') {
                // 按id查询
                $id = array();
                $ids = explode(',', $param['keyword']);
                foreach ($ids as $i) {
                    $id[] = (int) $i;
                }
                $select->where_in($param['field'], $id);
            } elseif ($field[$param['field']]['fieldtype'] == 'Linkage'
                && $field[$param['field']]['setting']['option']['linkage']) {
                // 联动菜单搜索
                if (is_numeric($param['keyword'])) {
                    // 联动菜单id查询
                    $link = dr_linkage($field[$param['field']]['setting']['option']['linkage'], (int)$param['keyword'], 0, 'childids');
                    if ($link) {
                        $select->where($param['field'].' IN ('.$link.')');
                    }
                } else {
                    // 联动菜单名称查询
                    $id = (int)$this->ci->get_cache('linkid-'.SITE_ID, $field[$param['field']]['setting']['option']['linkage']);
                    if ($id) {
                        $select->where($param['field'].' IN (select id from `'.$select->dbprefix('linkage_data_'.$id).'` where `name` like "%'.$param['keyword'].'%")');
                    }
                }
            } else {
                $select->like($param['field'], urldecode($param['keyword']));
            }
        }

        // 时间搜索
        if (isset($param['start']) && $param['start']) {
            $param['end'] = strtotime(date('Y-m-d 23:59:59', $param['end'] ? $param['end'] : SYS_TIME));
            $param['start'] = strtotime(date('Y-m-d 00:00:00', $param['start']));
            $select->where('inputtime BETWEEN ' . $param['start'] . ' AND ' . $param['end']);
        } elseif (isset($param['end']) && $param['end']) {
            $param['end'] = strtotime(date('Y-m-d 23:59:59', $param['end']));
            $param['start'] = 0;
            $select->where('inputtime BETWEEN ' . $param['start'] . ' AND ' . $param['end']);
        }

        return $param;
    }
	
	
	/**
	 * 数据分页显示
	 *
	 * @param	string	$table	表名称
	 * @param	array	$param	参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($table, $param, $page, $total) {

		if (!$total || $param['search']) {
			$select	= $this->db->select('count(*) as total');
            $param = $this->_where($select, $param);
			$data = $select->get($this->prefix.'_'.$table)->row_array();
			unset($select);
			$total = (int)$data['total'];
			unset($param['search']);
			if (!$total) {
                return array(array(), 0);
            }
		}
		
		$select	= $this->db->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
        $this->_where($select, $param);
        $_order = isset($_GET['order']) && strpos($_GET['order'], "undefined") !== 0 ? $this->input->get('order') : 'displayorder DESC,inputtime DESC';
        $data = $select->order_by($_order)->get($this->prefix.'_'.$table)->result_array();

        return array($data, $total);
	}
	
	/**
	 * 添加内容
	 *
	 * @return	id
	 */
	public function addc($table, $data) {
	
		if (!$data || !$table) {
            return NULL;
        }

		$this->db->insert($this->prefix.'_'.$table, $data);
		
		return $this->db->insert_id();
	}

	/**
	 * 添加内容(新的模式，支持附表)
	 *
	 * @return	id
	 */
	public function new_addc($table, $data) {

		if (!$data || !$table) {
            return NULL;
        }

        $table = $this->prefix.'_'.$table;

        $data[1]['tableid'] = 0;
		$this->db->insert($table, $data[1]);
		$id = $this->db->insert_id();

        $tableid = floor($id / 50000);
        $this->db->where('id', $id)->update($table, array('tableid' => $tableid));
        if (!$this->db->query("SHOW TABLES LIKE '".$table.'_data_'.$tableid."'")->row_array()) {
            // 附表不存在时创建附表
            $sql = $this->db->query("SHOW CREATE TABLE `".$table."_data_0`")->row_array();
            $this->db->query(str_replace(
                array($sql['Table'], 'CREATE TABLE '),
                array($table.'_data_'.$tableid, 'CREATE TABLE IF NOT EXISTS '),
                $sql['Create Table']
            ));
        }

        $data[0]['id'] = $id;
        $this->db->replace($table.'_data_'.$tableid, $data[0]);

        return $id;
	}
	
	/**
	 * 修改
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	intavl
	 */
	public function editc($id, $table, $data) {
		
		if (!$data || !$table || !$id) {
            return NULL;
        }
		
		$this->db->where('id', (int)$id)->update($this->prefix.'_'.$table, $data);
		
		return $id;
	}

	/**
	 * 修改
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	intavl
	 */
	public function new_editc($id, $table, $tableid, $data) {

		if (!$data || !$table || !$id) {
            return NULL;
        }

		$this->db->where('id', (int)$id)->update($this->prefix.'_'.$table, $data[1]);
        if ($data[0]) {
            $this->db->where('id', (int)$id)->update($this->prefix.'_'.$table.'_data_'.$tableid, $data[0]);
        } else {
            $data[0]['id'] = (int)$id;
            $this->db->replace($this->prefix.'_'.$table.'_data_'.$tableid, $data[0]);
        }

		return $id;
	}

    public function get_data($id, $table) {

        $data = $this->db->where('id', $id)->get($table)->row_array();
        if (!$data) {
            return NULL;
        }

        $data2 = $this->db->where('id', $id)->get($table.'_data_'.(int)$data['tableid'])->row_array();
        if ($data2) {
            return array_merge($data, $data2);
        }

        return $data;
    }
}