<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\builder\AdminConfigBuilder;
use app\admin\builder\AdminListBuilder;
use app\admin\builder\AdminSortBuilder;
use think\Db;

class Schedule extends Admin
{
    /**
     * scheduleList  计划任务列表
     * @author:59262424@qq.com（大蒙）
     */
    public function scheduleList()
    {
        $list = model('common/Schedule')->getScheduleList();

        foreach ($list as &$v) {
            list($type, $value) = $this->getTypeAndValue($v['type'], $v['type_value']);
            $v['type_text'] = $type;
            $v['type_value_text'] = $value;
            $v['next_run'] = model('common/Schedule')->calculateNextTime($v);
            $v['last_run'] = model('common/Schedule')->getLastUpdate($v['id']);
        }
        unset($v);

        
        //显示页面
        $btn_attr = model('common/Schedule')->checkIsRunning() ? array('style' => 'font-weight:700') : array('style' => 'font-weight:700');
        $btn_attr['class'] = 'ajax-post btn-info';
        $btn_attr[' hide-data'] = 'true';
        $btn_attr['href'] = Url('Schedule/run');
        //控制运行按钮文字
        if(model('common/Schedule')->checkIsRunning()){
            $btn_info = 'Running（点击停止）';
        }else{
            $btn_info = 'Stop（点击运行）';
        }
        $builder = new AdminListBuilder();

        $builder->title('计划任务')
            ->tips('Tips：执行时间较长的计划任务会影响到其他计划任务时间的计算；')
            ->button($btn_info, $btn_attr)
            ->setStatusUrl(Url('setScheduleStatus'));

            $btn_attr['style'] = 'font-weight:700';
            $btn_attr['href'] = Url('Schedule/reRun');
            $btn_attr['class'] = 'btn-warning ajax-post re_run';
            $btn_attr['onclick'] = 'javascript:$(this).text("重启中，请不要做其他操作...")';
        $builder
            ->button('重启计划任务', $btn_attr);
        $builder
            ->buttonNew(Url('Schedule/editSchedule'))
            ->buttonDelete()
            ->keyId()
            ->keyText('method', '执行方法')
            ->keyText('args', '参数')
            ->keyText('type_text', '类型')
            ->keyText('type_value_text', '设定时间')
            ->keyTime('start_time', '开始时间')
            ->keyTime('end_time', '结束时间')
            ->keyTime('last_run', '上次执行时间')
            ->keyTime('next_run', '下次执行时间')
            //->keyCreateTime()
            ->keyStatus()
            ->keyDoActionEdit('editSchedule?id=###')
            ->keyDoActionModalPopup('showLog?id=###', '查看日志', '日志', ['data-title' => '日志'])
            ->data($list)
            ->display();
    }

    /**
     * setScheduleStatus  禁用/启用/删除计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function setScheduleStatus(){
        $ids = input('ids');
        $status = input('get.status', 0, 'intval');

        cache('schedule_list',null);

        $builder = new AdminListBuilder();
        $builder->doSetStatus('Schedule', $ids, $status);
    }

    /**
     * showLog  显示日志
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function showLog()
    {
        $aId = input('get.id', 0, 'intval');
        $model = model('common/Schedule');
        $log = $model->getLog($aId);
        if ($log) {
            $log = explode("\n", $log);
        }
        $this->assign('log', $log);
        $this->assign('id', $aId);
        return $this->fetch();
    }

    /**
     * clearLog  清空日志
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function clearLog()
    {
        $aId = input('post.id', 0, 'intval');
        $model = model('common/Schedule');
        $rs = $model->clearLog($aId);
        $this->success('清空成功', 'refresh');
    }

    /**
     * editSchedule  新增/编辑计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function editSchedule()
    {
        $aId = input('id', 0, 'intval');
        if (request()->isPost()) {
            $data['id'] = $aId;
            $aMethod = $data['method'] = input('post.method', '', 'text');
            $aArgs = $data['args'] = input('post.args', '', 'text');
            $aType = $data['type'] = input('post.type_key', 0, 'intval');
            $aTypeValue = $data['type_value'] = input('post.type_value', '', 'text');
            $aStartTime = $data['start_time'] = input('post.start_time', 0, 'intval');
            $aEndTime = $data['end_time'] = input('post.end_time', 0, 'intval');
            $aIntro = $data['intro'] = input('post.intro', '', 'text');
            $aLever = $data['lever'] = input('post.lever', '', 'text');

            if (empty($aMethod)) {
                $this->error('请填写执行方法');
            }
            if (empty($aType)) {
                $this->error('请选择类型');
            }
            if (empty($aTypeValue)) {
                $this->error('请填写设置值');
            }
            if ($aType != 1) {
                if (empty($aStartTime)) {
                    $this->error('请填写开始时间');
                }
                if (empty($aEndTime)) {
                    $this->error('请填写结束时间');
                }
            }

            if (empty($aIntro)) {
                $this->error('请填写介绍');
            }

            if ($aType == 1) {
                $data['type_value'] = strtotime($data['type_value']);
            }

            $res = model('Schedule')->editSchedule($data);

            if ($res) {
                $this->success(($aId == 0 ? '添加' : '编辑') . '成功', Url('scheduleList'));
            } else {
                $this->error(($aId == 0 ? '添加' : '编辑') . '失败');
            }

        } else {
            $builder = new AdminConfigBuilder();

            if ($aId != 0) {
                $tip = '编辑';
                $schedule = Db::name('Schedule')->find($aId);
                $schedule['type_key'] = $schedule['type']; //当name为type时select有点错误。不知道为什么，用其他变量替换

            } else {
                $tip = '新增';
                $schedule = [];
            }
            $builder
                ->title($tip . '计划任务')
                ->keyId()
                ->keyText('method', "执行方法", "只能执行Model中的方法，如 <span style='color: red'>Home/Index->test</span> 则表示执行 model('Home/Index')->test();")
                ->keyText('args', "执行参数", "url的写法，如 <span style='color: red'>a=1&b=2</span> ")

                ->keySelect('type_key', '类型', '计划任务的类型', array(1 => '执行一次', 2 => '每隔一段时间执行', 3 => '每个时间点执行'))
                ->keyUserDefined('type_value', '设定时间', '', 'Admin@Schedule/edit', ['schedule' => $schedule])
                ->keyTime('start_time', '开始时间')
                ->keyTime('end_time', '结束时间')
                ->keyTextArea('intro', '介绍', '该介绍将会被写入日志')
                ->keyText('lever', '优先级')
                ->data($schedule)
                ->buttonSubmit(Url('Schedule/editSchedule'))
                ->buttonBack()
                ->display();
        }
    }

    /**
     * getTypeAndValue   获取计划任务类型和值
     * @param $type
     * @param $value
     * @return array
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function getTypeAndValue($type, $value)
    {
        switch ($type) {
            case 1:
                $type = '执行一次';
                $value = date('Y-m-d h:i', $value);
                break;
            case 2:
                $type = '每隔一段时间执行';
                break;
            case 3:
                $type = '每个时间点执行';
                break;
        }

        return array($type, $value);
    }

    /**
     * run  运行/停止计划任务
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function run()
    {
        $model = model('common/Schedule');

        if ($model->checkIsRunning()) {
            $model->setStop();
            $this->success('设置成功~已停止！');
        } else {
            $this->_run();
            $this->success('设置成功~运行中！');
        }
    }

    /**
     * reRun  重启计划任务
     * @author:大蒙 59262424@qq.com
     */
    public function reRun()
    {
        $model = model('common/Schedule');
        $model->setStop();
        //}
        $this->_run();
        $this->success('successfully');
    }

    /**
     * _run  运行计划任务
     * @author:大蒙
     */
    private function _run()
    {  
        $time = time();
        $url = Url('api/Schedule/runSchedule', ['time' => $time, 'token' => md5($time . config('database.auth_key'))]);
        $SSL = substr($url, 0, 8) == "https://" ? true : false;  
        $CA = true; //HTTPS时是否进行严格认证 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);  //设置过期时间为1秒，防止进程阻塞

        if ($SSL && $CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书  
            curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）  
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配  
        } else if ($SSL && !$CA) {  
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
        }  
        curl_setopt($ch, CURLOPT_USERAGENT, '');
        curl_setopt($ch, CURLOPT_REFERER, 'b');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);

        //var_dump($content);  //查看报错信息 
        curl_close($ch);
    }
}
