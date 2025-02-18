<?php
class flinkAction extends backendAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('flink');
    }

    protected function _search() {
        $map = array();
        ($cate_id = $this->_request('cate_id', 'trim')) && $map['cate_id'] = array('eq', $cate_id);
        ($keyword = $this->_request('keyword', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
            'cate_id' => $cate_id,
        ));
        return $map;
    }

    public function _before_index() {
        $big_menu = array(
            'title' => L('add_flink'),
            'iframe' => U('flink/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '260'
        );
        $this->assign('big_menu', $big_menu);
        $this->list_relation = true;
        $this->_before_add();

        $this->assign('img_dir',$this->_get_imgdir());

        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
    }

    public function _before_add() {
        $cate_list = D('flink_cate')->where(array('status'=>1))->select();
        $this->assign('cate_list',$cate_list);
    }


    /**
     * 入库数据整理
     */
    public function _before_insert() {
         if (strstr($this->_request("url", 'trim'), 'http://') == false) {
             $this->ajaxReturn(0, '请输入正确网址！');
         }
    }

    public function _before_update() {
         if (strstr($this->_request("url", 'trim'), 'http://') == false) {
             $this->ajaxReturn(0, '请输入正确网址！');
         }
    }

 

    public function ajax_check_name()
    {
        $name = $this->_get('name', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(0, '链接名称已经存在');
        } else {
            $this->ajaxReturn();
        }
    }

    /**
     * 友情链接图片上传目录
     *
     * @staticvar null $dir
     * @return string
     */
    private function _get_imgdir() {
        static $dir = null;
        if ($dir === null) {
            $dir = './data/upload/flink/';
        }
        return $dir;
    }
}