<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: wapai 
// +----------------------------------------------------------------------
namespace app\common\taglib;
use think\template\TagLib;
/**
 * 文档模型标签库
 */
class Article extends TagLib{
    /**
     * 定义标签列表
     * @var array
     */
    protected $tags   =  array(
        'partlist' => array('attr' => 'id,field,page,name', 'close' => 1), //段落列表
        'partpage' => array('attr' => 'id,listrow', 'close' => 0), //段落分页
    	'page'     => array('attr' => 'cate,listrow', 'close' => 0), //列表分页
        'prev'     => array('attr' => 'name,info', 'close' => 1), //获取上一篇文章信息
        'next'     => array('attr' => 'name,info', 'close' => 1), //获取下一篇文章信息 
        'position' => array('attr' => 'pos,cate,limit,filed,name', 'close' => 1), //获取推荐位列表
        'list'     => array('attr' => 'name,category,child,page,row,field', 'close' => 1), //获取指定分类列表
    );

    public function taglist($tag, $content){
        $name   = $tag['name'];
        $cate   = $tag['category'];
        $child  = empty($tag['child']) ? 'false' : $tag['child'];
        $row    = empty($tag['row'])   ? '10' : $tag['row'];
        $field  = empty($tag['field']) ? 'true' : $tag['field'];
 
        $parse  = '<?php ';
        $parse .= '$__CATE__ = model(\'Category\')->getChildrenId('.$cate.');'; 
        $parse .= '$__WHERE__ = model(\'Document\')->listMap($__CATE__);';
//         $parse .= '$__LIST__ = \think\Db::name(\'Document\')->where($__WHERE__)->field($field)->order(\'`level` DESC,`id` DESC\')->page(!empty($_GET["p"])?$_GET["p"]:1,'.$row.')->select();';
        $parse .= '$__LIST__ = \think\Db::name(\'Document\')->where($__WHERE__)->field($field)->order(\'`level` DESC,`id` DESC\')->paginate('.$row.');';
//         $parse .= '$__PAGE__=$__LIST__->render(); ';
        $parse .=  'if($__LIST__){ $__LIST__=$__LIST__->toArray(); $__LIST__=$__LIST__[\'data\'];}';
        $parse .= ' ?>'; 
        $parse .= '{volist name="__LIST__" id="'. $name .'"}';
        $parse .= $content;
        $parse .= '{/volist}';
//         $parse .= '<div>{$__PAGE__}</div>';
        return $parse;
    }

    /* 推荐位列表 */
    public function tagposition($tag, $content){
        $pos    = $tag['pos'];
        $cate   = $tag['cate'];
        $limit  = empty($tag['limit']) ? 'null' : $tag['limit'];
        $field  = empty($tag['field']) ? 'true' : $tag['field'];
        $name   = $tag['name'];
        $parse  = '<?php ';
        $parse .= '$__POSLIST__ = \think\Db::name(\'Document\')->position(';
        $parse .= $pos . ',';
        $parse .= $cate . ',';
        $parse .= $limit . ',';
        $parse .= $field . ');';
        $parse .= ' ?>';
        $parse .= '{volist name="__POSLIST__" id="'. $name .'"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }
    /* 列表数据分页 */
    public function tagpage($tag){
    	$cate    = $tag['cate'];
    	$listrow = $tag['listrow'];
    	$parse   = '<?php ';
    	$parse  .= '$__PAGE__ = \think\Db::name(\'Document\')->paginate(' . $listrow . ',get_list_count(' . $cate . '));';
    	$parse  .= 'echo $__PAGE__->render();';
    	$parse  .= ' ?>';
    	return $parse;
    }
   

    /* 获取下一篇文章信息 */
    public function tagnext($tag, $content){
        $name   = $tag['name'];
        $info   = $tag['info'];
        $parse  = '<?php ';
        $parse .= '$' . $name . ' = model(\'Document\')->next($' . $info . ');';
        $parse .= ' ?>';
        $parse .= '{notempty name="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/notempty}';
        return $parse;
    }

    /* 获取上一篇文章信息 */
    public function tagprev($tag, $content){ 
        $name   = $tag['name'];
        $info   = $tag['info'];
        $parse  = '<?php ';
        $parse .= '$' . $name . ' = model(\'Document\')->prev($' . $info . ');';
        $parse .= ' ?>';
        $parse .= '{notempty name="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/notempty}';
        return $parse;
    }

    /* 段落数据分页 */
    public function tagpartpage($tag){
        $id      = $tag['id'];
        if ( isset($tag['listrow']) ) {
            $listrow = $tag['listrow'];
        }else{
            $listrow = 10;
        }
        $parse   = '<?php ';
        $parse  .= '$__PAGE__ = new \think\Page(get_part_count(' . $id . '), ' . $listrow . ');';
        $parse  .= 'echo $__PAGE__->show();';
        $parse  .= ' ?>';
        return $parse;
    }

    /* 段落列表 */
    public function tagpartlist($tag, $content){
        $id     = $tag['id'];
        $field  = $tag['field'];
        $name   = $tag['name'];
        if ( isset($tag['listrow']) ) {
            $listrow = $tag['listrow'];
        }else{
            $listrow = 10;
        }
        $parse  = '<?php ';
        $parse .= '$__PARTLIST__ = D(\'Document\')->part(' . $id . ',  !empty($_GET["p"])?$_GET["p"]:1, \'' . $field . '\','. $listrow .');';
        $parse .= ' ?>';
        $parse .= '<?php $page=(!empty($_GET["p"])?$_GET["p"]:1)-1; ?>';
        $parse .= '{volist name="__PARTLIST__" id="'. $name .'"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }
}