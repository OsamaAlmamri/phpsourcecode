<?php

// +----------------------------------------------------------------------
// | Diygw
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 DIY官网 [ http://www.diygw.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.diygw.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/html580/diygw
// +----------------------------------------------------------------------

namespace controller;

use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Controller;
use think\Db;
use think\db\Query;
use think\Exception;
use think\helper\Str;
use service\FileService;
use Alchemy\BinaryDriver\Listeners\DebugListener;
use FFMpeg\Exception\ExecutableNotFoundException;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate;
use FFMpeg\FFProbe;

/**
 * 后台权限基础控制器
 * Class BasicAdmin
 * @package controller
 */
class BasicData extends Controller
{
    //是否登录
    public $isLogin = false;
    //是否微信登录
    public $isWechaLogin = false;

    protected function initialize()
    {
        $this->assign("title",$this->title);
        $flag = $this->init();
        if(!$flag){
            exit();
        }
    }

    private function init()
    {

        $login = $this->request->request('login');
        if(!empty($login)&&$login=="1"){
            $this->isLogin =true;
        }
        $isxcx = $this->request->request('__isxcx__');
        $token =  $this->request->header('Authorization');

        if($this->isLogin){//是否登录
            if( $isxcx=='true'||$isxcx=='1'){
                if(!empty($token)){
                    $this->uid = cache($token)['uid'];
                    if(empty($this->uid)){
                        echo json_encode(['status'=>401,'message'=>'登录超时，请重新登录']);
                        return false;
                    }
                }else{
                    echo json_encode(['status'=>401,'message'=>'登录超时，请重新登录']);
                    return false;
                }
            }else{
                $this->uid=session("uid".$this->mpid);
                if(empty($this->uid)){
                    $url = loginCheck("",true);
                    if(!empty($url)){
                        $this->redirect($url);
                    }
                }
            }
        }
        return true;
    }

    public function getUid(){
        if(empty($this->uid)){
            $isxcx = $this->request->request('__isxcx__');
            $token =  $this->request->header('Authorization');
            if(($isxcx=='true'||$isxcx=='1')&&!empty($token)){
                $this->uid = cache($token)['uid'];
            }else{
                $this->uid=  session("uid".session("mpid"));
            }
            $module =$this->request->module();
            $action = $this->request->action();
            if($module=='diygw'&&$action=='save'){
                $this->uid= session('user.id');
            }
        }
        return $this->uid;
    }

    public function getPageRow(){
        $psize 	= 10;
        $r=$this->request->request('r');
        if(isset($r)){
            $psize = intval($r);
        }else{
            $r=$this->request->request('row');
            if( isset($r)){
                $psize = intval($r);
            }
        }
        return $psize;
    }

    public function getPageNum(){
        $pagenum=1;
        $page = $this->request->request('page');
        if(isset($page)){
            $pagenum = intval($page);
        }else{
            $offset =$this->request->request('offset');
            if(isset($offset)){
                $pagenum = $offset/$this->getPageRow()+1;
            }
        }

        return $pagenum;
    }

    protected  function lists ($tablename,$where=array(),$order='',$field=true){
        $result = $this->getUserDb()->query("SHOW INDEX FROM " . $tablename);
        $pk="id";
        foreach($result as $value) {
            if($value['Key_name'] == 'PRIMARY'){
                $pk=$value['Column_name'];
            }
        }

        $orderArray=[];
        if($order===null||($order==='' && !empty($pk))){
            $orderArray[]= $pk.' desc';
        }elseif($order){
            $orderArray[]=$order;
        }
        $dbid = $this->request->request("dbid");
        if(empty($dbid)){
            if(empty($where['mpid'])){
                if(!empty(session("mpid"))){
                    $where['mpid']=session("mpid");
                }
            }
            if(empty($where['mpid'])){
                if(!empty($this->request->request("mpid"))){
                    $where['mpid']=$this->request->request("mpid");
                }
            }
        }

        $model = $this->getUserDb($dbid,true)->table($tablename)->where($where);
        $serachfield = $this->request->request("serachfield");
        $seachvalue = $this->request->request("seachvalue");
        if(!empty($serachfield)){
            $serachfields = explode(",",$serachfield);
            foreach ($serachfields as $field){
                $model->whereLike($field,$seachvalue);
            }
        }

        $db = $model->order($orderArray);

        $page = $db->paginate($this->getPageRow(), false, ['query' => $where,'page'=>$this->getPageNum()]);

        /*
        $rows=[];
        foreach ($plist as $item){
            foreach($item as $key => $value){
                $item[$key]=htmlspecialchars_decode($value);
            }
            $rows[]=$item;
        }*/
        $list['rows']=$page->all();
        $list['total']=$page->total();
        $list['totalPage']=$page->lastPage();
        return $list;

    }



    public function data()
    { 
        try {
            /*$Authorization = $_SERVER['HTTP_AUTHORIZATION'];

            if(cache($Authorization)==null){
                $list['Authorization']=$Authorization;
                $list['code']='-1';
                $list['message']='请求鉴权 API 失败，网络异常或鉴权服务器错误';
                return json_encode($list);
            }*/


            $type = $this->request->request("type");
            $columns =$this->request->request("columns");
            if(empty($columns)){
                //去掉不必要的属性值，否则查询数据库会出错
                $table = $this->getTable();//获取不带前缀的表名
                if(empty($table)){
                    $info = ['status'=>'error', 'message'=>'无权限获取表格数据或表不存在'];
                    echo json_encode($info);
                    return;
                }
                $map = $this->request->except(['formid','dashboardid','isinfinite','order']);
                $map = $this->removeMap($table,$map);
                $order = $this->request->request("order");

                $list = $this->lists ($table,$map,$order);

                $link_table = $this->request->request("link_table");
                if($list['total'] >0 && !empty($link_table)){

                    $datas = $list['rows'];
                    $attr = array();
                    foreach ($datas as $item) {
                        $attr[]=$item['link_id'];
                    }
                    $link_table= $this->getTbName($link_table);
                    if($link_table){
                        $linkDatas = $this->getUserDb()->table($link_table)->where(["id"=>$attr])->select();
                        $gdata = array();
                        foreach ($linkDatas as $ritem) {//遍历实际表数据
                            foreach ($datas as $item) {
                                if($ritem['id']==$item['link_id']){//遍历主表数据
                                    $tmp=array_merge($ritem,$item);
                                    $gdata[]=$tmp;
                                }
                            }
                        }
                        $list['rows']=$gdata;
                    }
                }
                $list['status']='success';
                echo json_encode($list);
            }else{
                $columns = json_decode(htmlspecialchars_decode($columns),true);
                $count_json = count($columns);
                $tables = array();
                $dashboardid = $this->request->request("dashboardid");
                for ($i = 0; $i < $count_json; $i++){
                    $column = $columns[$i];
                    $dashboardid = $column['dashboard_id'];
                    $columnList = null;
                    if(!empty($tables[$column['form_id']])){
                        $columnList =$tables[$column['form_id']];
                        $columnList[] = $column;
                        $tables[$column['form_id']] = $columnList;
                    }else{
                        $columnList = array();
                        $columnList[] = $column;
                        $tables[$column['form_id']] = $columnList;
                    }
                }

                $sql = "select ";
                $selectTables = array();
                $wheres = array();
                $wherelikes = array();

                $dbid=$this->request->request("dbid");
                $aliasindex = 0;

                $params =  request()->except(['order','offset','columns','limit','links','action','formid','dashboardid','isinfinite']);

                foreach ($tables as $formid => $columns) {
                    $map=[];
                    if(empty($dbid)){
                        $map["name"]=$dashboardid."_".$formid;
                        $tableName = $this->getTbName($map["name"]);
                        if(!$tableName){
                            $tableName = $this->getTbName($formid);
                        }
                    }else{
                        $tableName = $this->getTbName($formid,false);
                    }


                    if(!$tableName){
                        $message=[
                            'status'       => "error",
                            'message'       => "获取数据失败",
                            'total'        =>0,
                            'rows'         =>[]
                        ];
                        echo json_encode($message);
                        return;
                    }
                    $askey=0;
                    if($aliasindex>0){
                        $askey = $aliasindex;
                    }
                    $aliasindex++;
                    $tableAlias["name"]=$tableName;
                    $alias = "t".$askey;

                    $tableAlias["alias"]=$alias;
                    $selectTables[] = $tableAlias;

                    $idExist=false;//判断ID值是否存在，如果存在则不加ID值
                    $fields = array();

                    $existcolumns = $this->getUserDb()->query('SHOW FULL COLUMNS FROM ' . $tableName);
                    $existfields = array();
                    foreach ($existcolumns as $key => $value) {
                        $existfields[] = $value["Field"];
                    }


                    foreach ($params as $key => $value) {
                        if(strpos($key,'zdcs')!==false){
                            $field = substr($key,4);
                            if (in_array($field, $existfields)) {
                                $wherelikes[$alias.".".$field] = $value;
                            }
                        }
                    }


                    foreach ($columns as $column) {//遍历配置字段拼接SQL
                        $field = $column['field'];
                        if(!in_array($field,$existfields)){
                            continue;
                        }
                        if(in_array($field, $fields)){//判断查询字段是否已经存在，如果存在，跳过
                            continue;
                        }
                        $fields[]=$field;
                        if($field=="id"){
                            if(empty($dbid)) {
                                $sql = $sql . " " . $alias . ".id" . " id_" . $formid . "_1 , ";
                            }else{
                                $sql = $sql . " " . $alias . ".id," ;
                            }
                            $idExist= true;
                        }else if($field=="user_id"){
                            $sql=$sql." ".$alias.".create_time"." id_".$formid."_2 , ";
                            $idExist= true;
                        }else if($field=="create_time"){
                            $sql=$sql." ".$alias.".create_time"." id_".$formid."_3 , ";
                            $idExist= true;
                        }else{
                            $sql=$sql." ".$alias.".".$field.", ";
                        }
                    }
                    if(!$idExist && in_array('id',$existfields)){
                        $sql=$sql." ".$alias.".id"." id_".$formid."_1 , ";
                    }
                }
                $sql=substr($sql,0,strripos($sql,","));
                $sql.=" from ";

                $tableset = array();;
                foreach ($selectTables as $i=>$alias) {
                    $sql=$sql." ".$alias["name"]." as ".$alias["alias"].", ";
                    $tableset[$alias["name"]]=$alias["alias"];
                }
                $sql=substr($sql,0,strripos($sql,","));

                $links = $this->request->request("links");
                $links = json_decode($links);

                $sql = $sql." where 1=1 ";
                foreach ($wherelikes as $key=>$value) {
                    $sql=$sql." and ".$key." like '%".$value."%' ";
                }

                foreach ($links as $i=>$link) {
                    $fromOperator = $link->fromOperator;
                    $fromConnector = $link->fromConnector;
                    $fromConnector =substr($fromConnector,7);

                    $toOperator = $link->toOperator;
                    $toConnector = $link->toConnector;
                    $toConnector =substr($toConnector,6);

                    if(empty($dbid)) {
                        foreach ($selectTables as $i=>$alias) {
                            if(strpos($alias["name"],$fromOperator)){
                                $fromOperator=$alias["alias"];
                            }
                            if(strpos($alias["name"],$toOperator)){
                                $toOperator=$alias["alias"];
                            }
                        }
                        $sql .= " and $fromOperator.$fromConnector=$toOperator.$toConnector";
                    }else{
                        $sql .= " and $tableset[$fromOperator].$fromConnector=$tableset[$toOperator].$toConnector";
                    }

                }



                if(empty($dbid)) {
                    $sql .= " order by ";
                    foreach ($selectTables as $i => $alias) {
                        $sql = $sql . " " . $alias["alias"] . ".create_time, ";
                    }
                    $sql = substr($sql, 0, strripos($sql, ","));
                    $sql .= " desc ";
                }

                $psize 	= 10;
                $r=$this->request->request('r');
                if(isset($r)){
                    $psize = intval($r);
                }else{
                    $r=$this->request->request('row');
                    if( isset($r)){
                        $psize = intval($r);
                    }
                }


                $offset = intval($this->request->request('offset'));

                $countSql = " select count(1) total from ($sql) t";

                $totalResult = $this->getUserDb()->query($countSql);

                $sql.=" limit $offset , $psize ";
                $resultSet = $this->getUserDb()->query($sql);
                $results = array();
                foreach ($resultSet as $key => $result) {
                    $results[] = $result;
                }
                $total = $totalResult[0]['total'];
                echo json_encode([
                    'status'       => "success",
                    'message'       => "获取数据成功",
                    'total'        => $total,
                    'rows'         =>$results
                ]);
                return;
            }
        } catch (\Exception $e) {
            $info = ['status'=>'error', 'message'=>'获取数据失败'.$e];
            echo json_encode($info);
        }
    }

    public function save()
    {
        try {
            $table = $this->getTable();
            if(!$table){
                echo json_encode(['status'=>'error', 'message'=>'表不存在']);
                return;
            }
            $map = $this->request->except(['table','page','row','limit']);
            $map = $this->removeMap($table,$map);

            if(empty($map["id"])){
                //$map['user_id'] = $this->getUid();
                $dbid = $this->request->request("dbid");
                if(empty($dbid)){
                    $map["id"]=create_guid();
                    $map["create_time"] =  date("Y-m-d H:i:s", time());
                    $map["update_time"] =  date("Y-m-d H:i:s", time());
                    if(empty($map["status"])){
                        $map["status"] = "1";
                    }
                }


                try{
                    $this->getUserDb()->table($table)->insert($map);
                    $info = ['id'=>$map["id"],'status'=>'success', 'message'=>'保存数据成功'];
                    echo json_encode($info);
                } catch (Exception $e){
                    $info = ['status'=>'error', 'message'=>'保存数据失败'.$e];
                    echo json_encode($info);
                }
            }else{
                $map["update_time"] = time();
                try{

                    $this->getUserDb()->table($table)->where('id',$map["id"])->update($map);
                    $info = ['id'=>$map["id"],'status'=>'success', 'message'=>'更新数据成功'];
                    echo json_encode($info);
                } catch (Exception $e){
                    $info = ['status'=>'error', 'message'=>'更新数据失败'];
                    echo json_encode($info);
                }
            }
        } catch (Exception $e) {
            $info = ['status'=>'error', 'message'=>'更新数据失败'];
            echo json_encode($info);
            return;
        }
    }

    /**
     * 删除表格非自己的属性
     * @param $table
     * @param $map
     */
    public function removeMap($tableFullName,&$map)
    {
        //获取表所有字段
        $columns = $this->getUserDb()->query('SHOW FULL COLUMNS FROM ' . $tableFullName);
        $fields = array();
        foreach ($columns as $key => $value) {
            $fields[] = $value["Field"];
        }

        $isUser=false;
        if(empty($map['mpid'])){
            if(!empty(session("mpid"))){
                $map['mpid']=session("mpid");
            }
        }
        if(empty($map['mpid'])){
            if(!empty($this->request->request("mpid"))){
                $map['mpid']=$this->request->request("mpid");
            }
        }
        //去掉不必要的属性值，否则查询数据库会出错
        foreach ($map as $key => $value) {
            if($key=='user' && $value!="0"){
                $key='user_id';
                $isUser=true;
            }
            if(strpos($key,'zdcs')!==false){
                $field = substr($key,4);
                if(in_array($field, $fields)){
                    $map[$field]=$value;
                }else{
                    $field = substr($key,4,strrpos($key,'_')-4);
                    $condition = substr($key,strrpos($key,'_')+1);
                    if(!empty($value)){
                        if($condition=='eq'){
                            $map[$field]=$value;
                        }if($condition=='like'){
                            $map[$field]=array('like',$value);
                        }
                    }
                }

            }
            if (!in_array($key, $fields)) {
                if($key=='user_id'){
                    unset($map['user']);
                    $isUser = false;
                }
                unset($map[$key]);
            }

        }

        $module =$this->request->module();
        $action = $this->request->action();
        if($module=='diygw'&&$action=='save'){
            if(in_array('user_id', $fields)){
                $map['user_id'] = $this->getUid();
            }
        }
        if($isUser){
            unset($map['user']);
            $map['user_id'] = $this->getUid();
            if(empty($map['user_id'])){
                $isajax = $this->request->request('__isajax__');
                $isxcx = $this->request->request('__isxcx__');
                if($isajax=='true'||$isxcx=='true'||$isxcx=='1'){
                    return json_encode(['status'=>401,'message'=>'登录超时，请重新登录']);
                }else{
                    $this->assign('title','温馨提示');
                    $this->assign('message',"登录超时，请重新登录");
                    return  $this->fetch("login/perror");
                }
            }
        }
        return $map;
    }


    public function getTbName($table_name,$flag=true){
        $table_name = $flag?getTableName($table_name):$table_name;
        $sql = "SHOW TABLES LIKE '$table_name'";
        $res = count($this->getUserDb()->query($sql));
        if($res){
            return $table_name;
        }else{
            return null;
        }
    }

    /**
     * 获取表名
     * @return string
     */
    public function getTable(){
        //实际环境中，大家要处理好这个表名的问题，要不然所有的数据都会被盗哟。本环境只是提供一个测试环境，所以简化了写法
        //大家可以做个表名影射，限制表的访问规则，哪些表不能访问。
        $formid = $this->request->request("formid");
        $dbid = $this->request->request("dbid");
        if(!empty($dbid)){
            $db = $this->getUserDb();
            if(empty($formid)){
                $formid = $this->request->request("tableName");
            }

            $table_name=$formid;
            $sql = "SHOW TABLES LIKE '$table_name'";
            $res = count($db->query($sql));
            if($res){
                return $table_name;
            }else{
                return null;
            }
        }

        $tablename = $this->getLocalTableName();
        if(!empty($tablename)){
            $forbidTable=['member','system','payment','db','addons','hooks'];
            foreach ($forbidTable as $value) {
               if(strpos(strtolower($tablename),strtolower(config('database.prefix').$value))!==false) {
                   return null;
               }
            }
        }
        return $tablename;

    }

    public function getLocalTableName(){
        $formid = $this->request->request("formid");
        $dashboardid = $this->request->request("dashboardid");
        if(empty($dashboardid)){
            $dashboardid = $this->request->request("dashboardId");
        }

        $tableName = $this->request->request("tableName");
        $result = $this->getTbName($tableName);
        if($result){
            return $result;
        }

        $tableName = $this->request->request("tableName");
        $result = $this->getTbName($dashboardid."_".$tableName);
        if($result){
            return $result;
        }


        if(empty($formid)){
            $formid = $this->request->request("formId");
        }

        $result = $this->getTbName($formid);
        if($result){
            return $result;
        }

        $result = $this->getTbName($dashboardid."_".$formid);

        if($result){
            return $result;
        }
        return null;
    }
    public function remove()
    {
        try {
            $table = $this->getTable();//获取不带前缀的表名
            $ids    =   $this->request->request('values/a');
            $field    =   $this->request->request('field');
            $db = $this->getUserDb();
            if(!empty($field)&&strpos($field,"_")){
                $db->table($table)->whereIn(substr($field,0,strpos($field,"_")),$ids);
            }else{
                if(empty($field)){
                    $field='id';
                }
                $db->table($table)->whereIn($field,$ids);
            }
            if($db->delete()){
                $info = ['status'=>'success', 'message'=>'删除数据成功'];
                echo json_encode($info);
            }else{
                $info = ['status'=>'error', 'message'=>'删除数据失败'];
                echo json_encode($info);
            }

        } catch (\Exception $e) {
            $info = ['status'=>'error', 'message'=>'删除数据失败'];
            echo json_encode($info);
        }
    }

    protected function getUserDb($dbid="",$flag=false){
        if(empty($dbid)){
            $dbid = $this->request->request("dbid");
        }
        if(!empty($dbid)){
            $db = Db::name("db")->where('id',$dbid)->find();
            $db['password']=trim(aesDecrypt($db['password']));
            if($flag){
                $db['params']=[
                    \PDO::ATTR_CASE         => \PDO::CASE_LOWER,
                ];
            }

            return Db::connect($db);
        }else{
            return db();
        }
    }


    /**
     * 文件上传
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upfile()
    {
        $uptype = $this->request->get('uptype');
        if (!in_array($uptype, ['local', 'qiniu', 'oss'])) {
            $uptype = sysconf('storage_type');
        }
        $mode = $this->request->get('mode', 'one');
        $types = $this->request->get('type', 'jpg,png');
        $this->assign('mimes', FileService::getFileMine($types));
        $this->assign('field', $this->request->get('field', 'file'));
        return $this->fetch('', ['mode' => $mode, 'types' => $types, 'uptype' => $uptype]);
    }

    /**
     * 通用文件上传
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @throws \OSS\Core\OssException
     */
    public function file()
    {
        $files = $this->request->file();
        $uid= $this->request->request("uid");
        if(empty($uid)){
            $uid='default';
        }
        foreach ($files as $file){
            $names = str_split($file->hash('md5'), 16);

            $ext = strtolower(pathinfo($file->getInfo('name'), 4));
            $ext = $ext ? $ext : 'tmp';
            $filename = "{$uid}/{$names[0]}/{$names[1]}.{$ext}";
            // 文件上传处理
            if (($info = $file->move("static/upload/{$uid}/{$names[0]}", "{$names[1]}.{$ext}", true))) {
                if (($site_url = FileService::getFileUrl($filename, 'local'))) {
                    return json(['url' => $site_url, 'code' => 'SUCCESS', 'msg' => '文件上传成功']);
                }
            }
            return json(['code' => 'ERROR', 'msg' => '文件上传失败']);
        }

    }


    public function uploadvideo()
    {
        $files = $this->request->file();
        $uid= $this->request->request("uid");
        if(empty($uid)){
            $uid='default';
        }
        foreach ($files as $file){
            $names = str_split($file->hash('md5'), 16);
            $ext = strtolower(pathinfo($file->getInfo('name'), 4));
            $ext = $ext ? $ext : 'tmp';
            $filename = "{$uid}/{$names[0]}/{$names[1]}.{$ext}";
            // 文件上传处理
            if (($info = $file->move("static/upload/{$uid}/{$names[0]}", "{$names[1]}.{$ext}", true))) {
                $ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries' => 'd:\ffmpeg\bin\ffmpeg.EXE',
                    'ffprobe.binaries' => 'd:\ffmpeg\bin\ffprobe.exe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ]);
                //打开资源
                $video = $ffmpeg->open(APP_PATH.'static/upload/'.$names[0].'/'.$names[1].'.'.$ext);
                //设置视频大小
                $video->filters()
                    ->resize(new Coordinate\Dimension(320,240))
                    ->synchronize();
                //截取视频图片 2s 时候截取
                $pic_url='static/upload/'.$names[0].'/'.$names[1].'.jpg';
                $video->frame(Coordinate\TimeCode::fromSeconds(2))->save(APP_PATH.$pic_url);

                if (($site_url = FileService::getFileUrl($filename, 'local'))) {
                    $pic_url = "{$names[0]}/{$names[1]}.jpg";
                    $pic_url = FileService::getFileUrl($pic_url, 'local');
                    return json(['video' => $site_url,'url'=>$pic_url, 'code' => 'SUCCESS', 'msg' => '文件上传成功']);
                }
            }
            return json(['code' => 'ERROR', 'msg' => '文件上传失败']);
        }

    }


    public function saveformid(){
        try {
            $formid = $this->request->request("formid");
            $openid  = $this->request->request("openid");
            $dashboarid =$this->request->request("dashboardid");
            $mpid =$this->request->request("mpid");
            $data['dashboard_id']=$dashboarid;
            $data['mpid']=$mpid;
            $data['formid']=$formid;
            $data['openid']=$openid;
            $data['status']=1;
            $data['user_id'] = $this->getUid();
            $data["create_time"] =  date("Y-m-d H:i:s", time());
            $data["update_time"] =  date("Y-m-d H:i:s", time());
            Db::name('AppForm')->insert($data);
            $info = ['status'=>'success', 'message'=>'保存成功'];
            echo json_encode($info);

        } catch (\Exception $e) {
            $info = ['status'=>'error', 'message'=>'保存失败'.$e];
            echo json_encode($info);
        }
    }
}
