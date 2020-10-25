<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/11/30
 * Time: 11:32
 * �Զ����ҳ��
 */
namespace Common\Util;
class MyPage{
    private static $page;//��ǰҳ
    private static $size;//ÿҳ����
    private static $count;//�ܹ�����
    private static $showpage;//��ʾ���������ݣ�������Ҫ��ʾ�ľ���...����

    /**
     * ���ݴ���������������ʽ����ҳ����
     * @param $page ��ǰҳ
     * @param $count �ܹ�����
     * @param $size ÿҳ����
     * @param int $showpage ÿ����ʾ���ٸ�ҳ  Ĭ����10
     * @return array ��ʽ�õ�ҳ
     */
    public static function pager($page,$count,$size,$showpage=0){
        $p=array();
        MyPage::$count=$count;
        MyPage::$size=$size;
        MyPage::$showpage=$showpage>0?$showpage:10;
        MyPage::$page=$page;
        $pagers=ceil( MyPage::$count/ MyPage::$size);
        $p['count']=MyPage::$count;//������
        $p['size']=MyPage::$size;//ÿҳ��ʾ����
        $p['pagers']=$pagers;//��ҳ��
        $p['current']=MyPage::$page;//��ǰҳ
        $p['pre']=(MyPage::$page-1)>0? '?page='.(MyPage::$page-1):0;//��ʾǰһҳ
        $p['next']=(MyPage::$page+1)<=$pagers? '?page='.(MyPage::$page+1):0;//��ʾ��һҳ
        $p=MyPage::to_page($p);
        return $p;
    }

    /**
     *
     * @param $pager  �����ҳ����
     * @return mixed ��ʽ����ҳ����
     */
    private static function to_page($pager){
        $pager["pprev"]=0;
        $pager["pnext"]=0;
       if($pager['pagers']>MyPage::$showpage){//��ҳ������һ����ʾ���������������ʾ
           if(MyPage::$page>5){//��ǰҳ����5��ʱ��Ĵ���
                for($i=(MyPage::$page-5);$i<(MyPage::$page+1);$i++){//��ʾ��ǰҳ��ǰ5ҳ
                    $pager['num'][$i]= '?page='.($i);
                }
               if((MyPage::$page+4)<=$pager['pagers']){//��ҳ���ȵ�ǰҳ�ĺ�4ҳ��Ҫ����ֻ��ʾ��4��
                   for($i=MyPage::$page;$i<(MyPage::$page+4);$i++){
                       $pager['num'][$i]= '?page='.($i);
                   }
               }else{//��ҳ�����ȵ�ǰҳ�ĺ�4ҳ���ȫ����ʾ
                   for($i=MyPage::$page;$i<=$pager['pagers'];$i++){
                       $pager['num'][$i]= '?page='.($i);
                   }
               }
           }else{//��ǰҳ������5��ʱ��Ĵ���ȫ����ʾ����
               for($i=1;$i<=MyPage::$showpage;$i++){
                   $pager['num'][$i]= '?page='.($i);
               }
           }
           if(MyPage::$page<MyPage::$showpage-1){
               $pager["pprev"]=0;
               $pager["pnext"]=1;
           }else{
               $pager["pprev"]=1;
               if((MyPage::$page+4)<$pager['pagers']){
                   $pager["pnext"]=1;
               }else{
                   $pager["pnext"]=0;
               }
           }
       }else{//��ҳ��С��һ����ʾ��������ȫ����ʾ
           for($i=0;$i<$pager['pagers'];$i++){
               $pager['num'][$i+1]= '?page='.($i+1);
           }
       }
       return $pager;
    }

    /**
     * ����ÿ����ʾ��ҳ����Ĭ��Ϊ10
     * @param mixed $showpage
     */
    public static function setShowpage($showpage)
    {
        self::$showpage = $showpage;
    }

}