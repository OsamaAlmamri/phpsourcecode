<?php namespace Phpcmf\Field;

/**
 * http://www.xunruicms.com
 * 本文件是框架系统文件，二次开发时不可以修改本文件，可以通过继承类方法来重写此文件
 **/

class Members extends \Phpcmf\Library\A_Field {
	
	/**
     * 构造函数
     */
    public function __construct(...$params) {
        parent::__construct(...$params);
		$this->fieldtype = ['TEXT' => '']; // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'TEXT'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {

	    $group = '<div class="mt-checkbox-inline">';
	    foreach (\Phpcmf\Service::C()->member_cache['group'] as $t) {
            $group.= '<label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" value="'.$t['id'].'" name="data[setting][option][group][]" '.(in_array($t['id'], $option['group']) ? 'checked' : '').' /> '.dr_lang($t['name']).' <span></span></label>';
        }
	    $group.= '</div>';

		return ['
				<div class="form-group">
                    <label class="col-md-2 control-label">'.dr_lang('最大选择数').'</label>
                    <div class="col-md-9">
                    <label><input type="text" class="form-control" size="10" name="data[setting][option][limit]" value="'.$option['limit'].'"></label>
					<span class="help-block">'.dr_lang('最大能选择的数量限制').'</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">'.dr_lang('指定用户组').'</label>
                    <div class="col-md-9">
                    '.$group.'
					<span class="help-block">'.dr_lang('列出指定用户组的用户列表，如果不选择时将显示全部用户').'</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">'.dr_lang('友情提示').'</label>
                    <div class="col-md-9">
                        <div class="form-control-static">'.dr_lang('此字段不能参与搜索条件筛选').'</div>
                    </div>
                </div>'];
	}
	
	/**
	 * 字段输出
	 */
	public function output($value) {
		return $value;
	}
	
	/**
	 * 字段入库值
	 */
	public function insert_value($field) {
		
		$data = \Phpcmf\Service::L('Field')->post[$field['fieldname']];
		$value = !$data ? '' : implode(',', $data);
		
		\Phpcmf\Service::L('Field')->data[$field['ismain']][$field['fieldname']] = $value;
	}
	
	/**
	 * 字段表单输入
	 *
	 * @param	string	$cname	字段别名
	 * @param	string	$name	字段名称
	 * @param	array	$cfg	字段配置
	 * @param	string	$value	值
	 * @return  string
	 */
    public function input($field, $value = '') {

        // 字段禁止修改时就返回显示字符串
        if ($this->_not_edit($field, $value)) {
            return $this->show($field, $value);
        }

        // 字段存储名称
        $name = $field['fieldname'];
		// 字段提示信息
		$tips = isset($field['setting']['validate']['tips']) && $field['setting']['validate']['tips'] ? '<span class="help-block" id="dr_'.$name.'_tips">'.$field['setting']['validate']['tips'].'</span>' : '';

		// 区域大小
        $area = \Phpcmf\Service::C()->_is_mobile() ? '["95%", "90%"]' : '["50%", "45%"]';

		$tpl = '<tr id="dr_items_'.$name.'_{id}"><td>{id}</td><td>{value}<input type="hidden" name="data['.$name.'][]" value="{id}"></td><td width="45"><a class="btn btn-xs red" href="javascript:;" onclick="$(\\\'#dr_items_'.$name.'_{id}\\\').remove()"><i class="fa fa-trash"></i></a></td></tr>';

        // 选择数量限制
        $limit = intval($field['setting']['option']['limit']);
        !$limit && $limit = 99999;
        // 字段显示名称
        $text = ($field['setting']['validate']['required'] ? '<span class="required" aria-required="true"> * </span>' : '').$field['name'];

        $str = '';
        $str.= '
			<div class="scroller_'.$name.'_files">
                <div class="scroller" data-inited="0" data-initialized="1" data-always-visible="1" data-rail-visible="1">
        <table class="table table-striped table-bordered fc-sku-table table-hover">
        <thead>
        <tr>
            <th width="90" style="border-left-width: 1px!important;">Uid </th>
            <th>'.dr_lang('用户').' </th>
            <th width="50"> </th>
        </tr>
        </thead>
        <tbody id="rmember_'.$name.'-sort-items" class="scroller_body">';

        $value = @trim($value, ',');
        if ($value && is_string($value)) {
			$db = \Phpcmf\Service::M()->db->query('select * from '.\Phpcmf\Service::M()->dbprefix('member').' where id IN ('.$value.') order by instr("'.$value.'", id)');
            $query = $db ? $db->getResultArray() : [];
            if ($query) {
                foreach ($query as $t) {
                    $id = $t['id'];
                    $value = '<img class="img-circle" src="'.dr_avatar($t['id']).'" style="width:30px;height:30px;margin-right:10px;"> '.$t['username'];
                    $str.= str_replace(array('{avatar}', '{id}', '{value}', '\\'), array(dr_avatar($t['id']), $id, $value, ''), $tpl);
                }
            }
		}	
		$str.= '</tbody>';
		$str.= '</table></div></div>';
		$str.= '<p>';
		$str.= '<button type="button" class="btn blue btn-sm" onClick="dr_add_rmember_'.$name.'()"> <i class="fa fa-plus"></i> '.dr_lang('关联用户').'</button>';
        $str.= '</p>';
        $str.= $tips;
        $js = \Phpcmf\Service::L('js_packer');
        $str.= $js->pack('
		<script type="text/javascript">
        $("#rmember_'.$name.'-sort-items").sortable();
        dr_slimScroll_init(".scroller_'.$name.'_files", 300);
		function dr_add_rmember_'.$name.'() {
		    var len = $(\'#rmember_'.$name.'-sort-items tr\').length;
		    if (len >= '.$limit.') {
		        dr_tips(0, "'.dr_lang('关联数量超限').'");
		        return;
		    }
		    var url = "/index.php?s=api&c=api&m=members&group='.(implode(',', $field['setting']['option']['group'])).'";
            layer.open({
                type: 2,
                title: \'<i class="fa fa-user"></i> '.dr_lang('关联用户').'\',
                fix:true,
                shadeClose: true,
                shade: 0,
                area: '.$area.',
                btn: ["'.dr_lang('关联').'"],
                success: function (json) {
                    if (json.code == 0) {
                        layer.close();
                        dr_tips(json.code, json.msg);
                    }
                },
                yes: function(index, layero){
                    var body = layer.getChildFrame(\'body\', index);
                     // 延迟加载
                    var loading = layer.load(2, {
                        time: 10000
                    });
                    $.ajax({type: "POST",dataType:"json", url: url, data: $(body).find(\'#myform\').serialize(),
                        success: function(json) {
                            layer.close(loading);
                            if (json.code == 1) {
                                layer.close(index);
                                var temp = \''.$tpl.'\';
                                for(var i in json.data.result){
                                    var v = json.data.result[i];
                                    if (typeof v.id != "undefined") {
                                        if($("#dr_items_'.$name.'_"+v.id).length>0) {
                                          dr_tips(0, "'.dr_lang('已经存在').'");
                                          return;
                                        }
                                        if ($(\'#rmember_'.$name.'-sort-items tr\').length >= '.$limit.') {
                                            dr_tips(0, "'.dr_lang('关联数量超限').'");
                                            return;
                                        }
                                        var tpl = temp;
                                        tpl = tpl.replace(/\{id\}/g, v.id);
                                        tpl = tpl.replace(/\{value\}/g, v.value);
                                        $(\'#rmember_'.$name.'-sort-items\').append(tpl);
                                    }
                                }
                                dr_slimScroll_init(".scroller_'.$name.'_files", 300);
                                dr_tips(1, json.msg);
                            } else {
                                dr_tips(0, json.msg);
        
                            }
                            return false;
                        }
                    });
                    
                    return false;
                },
                content: url
            });
		
			
		}
		</script>', 0);
		
		return $this->input_format($name, $text, $str);
	}

    /**
     * 字段表单显示
     *
     * @param	string	$field	字段数组
     * @param	array	$value	值
     * @return  string
     */
    public function show($field, $value = null) {

        $str = '';
        $str.= '
        <table class="table table-striped table-bordered table-advance">
        <thead>
        <tr>
            <th width="90" style="border-left-width: 2px!important;"> Id </th>
            <th>'.dr_lang('主题').' </th>
        </tr>
        </thead>
        <tbody>';

        $value = @trim($value, ',');
        if ($value && is_string($value)) {
            $db = \Phpcmf\Service::M()->db->query('select * from '.\Phpcmf\Service::M()->dbprefix('member').' where id IN ('.$value.') order by instr("'.$value.'", id)');
            $query = $db ? $db->getResultArray() : [];
            if ($query) {
                foreach ($query as $t) {
                    $str .= '<tr><td>' . $t['id'] . '</td><td><a href="' . $t['url'] . '" target="_blank">' . $t['title'] . '</a></td></tr>';;
                }
            }
        }
        $str.= '</tbody>';
        $str.= '</table>';

        return $this->input_format($field['fieldname'], $field['name'], $str);
    }
}