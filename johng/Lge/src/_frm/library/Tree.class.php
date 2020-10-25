<?php
namespace Lge;

if (!defined('LGE')) {
    exit('Include Permission Denied!');
}

/**
 * 树型结构管理类。
 *
 * @package Lge
 */
class Lib_Tree
{
    /**
     * 生成树型结构所需要的2维数组
     *
     * @var array
     */
    public $arr = array();

    /**
     * 生成树型结构的字段
     */
    public $colum = array(
        "id"        => "role_id",
        "parent_id" => "parent_id",
        "name"      => "role_value"
    );
    /**
     * 生成树型结构所需修饰符号，可以换成图片
     *
     * @var array
     */
    public $icon = array(' │', ' ├', ' └');

    /**
     * @access private
     */
    public $ret = '';

    /**
     * 构造函数，初始化类
     *
     * @param array 2维数组，例如：
     * array(
     *      1 => array('sort_role_id'=>'1','parent_id'=>0,'sort_role_value'=>'一级栏目一'),
     *      2 => array('role_id'=>'2','parent_id'=>0,'role_value'=>'一级栏目二'),
     *      3 => array('role_id'=>'3','parent_id'=>1,'role_value'=>'二级栏目一'),
     *      4 => array('role_id'=>'4','parent_id'=>1,'role_value'=>'二级栏目二'),
     *      5 => array('role_id'=>'5','parent_id'=>2,'role_value'=>'二级栏目三'),
     *      6 => array('role_id'=>'6','parent_id'=>3,'role_value'=>'三级栏目一'),
     *      7 => array('role_id'=>'7','parent_id'=>3,'role_value'=>'三级栏目二')
     * )
     */
    function __construct($arr = array(), array $colum = null)
    {
        $this->arr = $arr;
        $this->ret = "";
        if ($colum !== null) {
            $this->colum = $colum;
        }
        return is_array($arr);
    }

    /**
     * 得到父级数组
     *
     * @param int
     *
     * @return array
     */
    function getParent($myid)
    {
        $newarr = array();
        if (!isset($this->arr[$myid])) {
            return false;
        }
        $pid = $this->arr[$myid][$this->colum['parent_id']];
        $pid = $this->arr[$pid][$this->colum['parent_id']];
        if (is_array($this->arr)) {
            foreach ($this->arr as $roleId => $a) {
                if ($a[$this->colum['parent_id']] == $pid) {
                    $newarr[$roleId] = $a;
                }
            }
        }
        return $newarr;
    }

    /**
     * 得到子级数组
     *
     * @param int
     *
     * @return array
     */
    function getChild($myid)
    {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $roleId => $a) {
                if ($a[$this->colum['parent_id']] == $myid) {
                    $newarr[$roleId] = $a;
                }
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 得到当前位置数组
     *
     * @param int
     *
     * @return array
     */
    function getPos($myid, &$newarr)
    {
        $a = array();
        if (!isset($this->arr[$myid])) {
            return false;
        }
        $newarr[] = $this->arr[$myid];
        $pid      = $this->arr[$myid][$this->colum['parent_id']];
        if (isset($this->arr[$pid])) {
            $this->getPos($pid, $newarr);
        }
        if (is_array($newarr)) {
            krsort($newarr);
            foreach ($newarr as $v) {
                $a[$v[$this->colum['id']]] = $v;
            }
        }
        return $a;
    }

    /**
     * 得到树型结构
     *
     * @param integer $myid，表示获得这个role_id下的所有子级
     * @param string  $str生成树型结构的基本代码，例如："<option value=\$roleId \$selected>\$spacer\$role_value</option>"
     * @param integer $sid被选中的role_id，比如在做树型下拉框的时候需要用到
     *
     * @return string
     */
    function getTree($myid, $str = '', $sid = 0, $adds = '')
    {
        $number = 1;
        $child = $this->getChild($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $roleId => $a) {
                $a['old_name'] = $a[$this->colum['name']];
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                $selected = $roleId == $sid ? "selected" : '';
                @extract($a);
                eval("\$nstr = \"$str\";");
                $a[$this->colum['name']] = $nstr;
                $a['spacer'] = $spacer;
                $this->ret[] = $a;
                $this->getTree($a[$this->colum['id']], $str, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        if (!is_array($this->ret)) {
            $this->ret = array();
        }
        return $this->ret;
    }

    function setArrayColum($a)
    {
        $this->colum = $a;
    }
}