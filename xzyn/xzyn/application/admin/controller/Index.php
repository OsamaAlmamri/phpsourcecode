<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Cache;

class Index extends Common {

    public function index() {
        $userTotal = $this->userTotal();                    //用户总数
        $this->assign('userTotal', $userTotal);

        $archiveTotal = $this->archiveTotal();              //文章总数
        $this->assign('archiveTotal', $archiveTotal);

        $userNear = $this->userNear();                      //最近7天注册用户数
        $this->assign('userNear', $userNear);

        $guestbookTotal = $this->guestbookTotal();          //最近7天评论数
        $this->assign('guestbookTotal', $guestbookTotal);

        $loginLogList = $this->loginLogList();              //最新登录8条信息
        $this->assign('loginLogList', $loginLogList);

        $loginLogLineJson = $this->loginLogLineJson();      //最近30天登录统计json
        $this->assign('loginLogLineJson', $loginLogLineJson);

        $groupPieJson = $this->groupPieJson();              //用户组人数统计
        $this->assign('groupPieJson', $groupPieJson);

        $systemConfig = $this->systemConfig();              //服务器信息配置
        $this->assign('systemConfig', $systemConfig);

        return $this->fetch();
    }

    /**
     * @Title: userTotal
     * @Description: todo(用户总数)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function userTotal()
    {
        $userModel = new \app\common\model\User;
        return $userTotal = $userModel->count();
    }

    /**
     * @Title: userNear
     * @Description: todo(最近7天注册用户)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function userNear()
    {
        $userModel = new \app\common\model\User;
        $nowTime = strtotime(date('Ymd', time())) + 86400;
        $userTime = $nowTime - 604800;
        return $userNear = $userModel->where('create_time', 'egt', $userTime)->count();
    }

    /**
     * @Title: archiveTotal
     * @Description: todo(文章总数)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function archiveTotal()
    {
        $archiveModel = new \app\common\model\Archive;
        return $archiveTotal = $archiveModel->count();
    }

    /**
     * @Title: guestbookTotal
     * @Description: todo(最近7天评论数)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function guestbookTotal()
    {
        $guestbookModel = new \app\common\model\Guestbook;
        $nowTime = strtotime(date('Ymd', time())) + 86400;
        $guestbookTime = $nowTime - 604800;
        return $guestbookTotal = $guestbookModel->where('create_time', 'egt', $guestbookTime)->count();
    }

    /**
     * @Title: loginLogList
     * @Description: todo(最新登录8条信息)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function loginLogList()
    {
        $loginLogModel = new \app\common\model\LoginLog;
        return $loginLogList = $loginLogModel->limit(8)->order('id DESC')->select();
    }

    /**
     * @Title: loginLogLineJson
     * @Description: todo(最近30天登录统计json)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function loginLogLineJson()
    {
        $loginLogModel = new \app\common\model\LoginLog;
        $userModel = new \app\common\model\User;
        $nowTime = strtotime(date('Ymd', time())) + 86400;
        $loginLogLineTime = $nowTime - 2592000;
        $loginLogLine = $loginLogModel->where('create_time', 'between time', [$loginLogLineTime, $nowTime])->select();
        $loginLogLineArr = [];
		$loginLogLineArr['labels'] = [];
		$loginLogLineArr['datasets']['data'] = [];
        $loginLogLineArr['datasets'][0]['label'] = '登录统计';
        $loginLogLineArr['datasets'][0]['fill'] = false;
        $loginLogLineArr['datasets'][0]['borderColor'] = '#4bc0c0';
        foreach ($loginLogLine as $k=>$v){
        	$labels = date('Y-m-d', strtotime($v['create_time']));
            if (!in_array($labels, $loginLogLineArr['labels'])){
                $loginLogLineArr['labels'][] = date('Y-m-d', strtotime($v['create_time']));
				$labelsx = date('Y-m-d', strtotime($labels) + 86400 );
				$dangtian = $loginLogModel->where('create_time', 'between time', [$labels, $labelsx])->count();
				$loginLogLineArr['datasets']['data'][] .= $dangtian;
            }
        }
        $old = $loginLogLineArr['datasets']['data'];
        unset($loginLogLineArr['datasets']['data']);
        foreach ($old as $v){
            $loginLogLineArr['datasets'][0]['data'][] = $v;
        }
        return $loginLogLineJson = json_encode($loginLogLineArr);
    }

    /**
     * @Title: groupPieJson
     * @Description: todo(用户组人数统计)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function groupPieJson()
    {
        $agModel = new \app\common\model\AuthGroup;
        $userModel = new \app\common\model\User;
        $groupPieArr = [];
        $agData = $agModel->where('status', 1)->select();
        $noGroup = [
        	'id' => '0',
        	'title' =>'无角色用户',
        	'pic' => '#666',
        	'count'=> 0
        ];
		$array = [];
        $agData[count($array)-1] = $noGroup;
        foreach($agData as $k => $v){
            $v['count'] = 0;
            $groupPieArr['labels'][] = $v['title'];
        }
		$userData = $userModel->select();
        foreach ($userData as $k=>$v){
            $userGroup = $v->userGroup;
            if(!empty($userGroup)){
                foreach ($userGroup as $k2 => $v2){
                    foreach ($agData as $k3 =>$v3){
                        if ($v3['id'] == $v2['group_id']){
                            $agData[$k3]['count'] += 1;
                            break;
                        }
                    }
                }
            }else{
                $agData['count($array)-1']['count'] += 1;
            }
        }
        foreach($agData as $k=>$v){
            $groupPieArr['datasets'][0]['data'][] = $v['count'];
            $groupPieArr['datasets'][0]['backgroundColor'][] = $v['pic'];
        }
        return $groupPieJson = json_encode($groupPieArr);
    }

    /**
     * @Title: systemConfig
     * @Description: todo(服务器信息配置)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    private function systemConfig()
    {
        return $config = [
            '操作系统' => PHP_OS,
            '服务器时间' => date("Y-n-j H:i:s"),
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time').'秒',
        ];
    }


    /**
     * @Title: cleanCache
     * @Description: todo(清除缓存)
     * @author 戏中有你
     * @date 2018年1月16日
     * @throws
     */
    public function cleanCache(){
        Cache::clear();	//清除缓存

		//清除模版缓存 不删除 temp目录
		$runt = env('RUNTIME_PATH');
		array_map( 'unlink', glob( $runt.'temp/*.php' ) );

		$logarr = scandir( $runt.'log' );	//返回目录列表
		foreach ($logarr as $k => $v) {	// 删除日志
			if( $v != '.' && $v != '..' ){
				if(is_dir($runt.'log/'.$v)){
					$log = $runt.'log/'.$v;
					array_map( 'unlink',glob( $log.'/*.log' ) );
				}
			}
		}
		return $this->fetch();
    }

    public function icons()
    {
        return $this->fetch();
    }

    public function forms()
    {
        return $this->fetch();
    }

    public function box()
    {
        return $this->fetch();
    }

    public function tab()
    {
        return $this->fetch();
    }

    public function tables()
    {
        return $this->fetch();
    }

    public function question()
    {
        return $this->fetch();
    }
}
