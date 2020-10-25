<?php
/**
 * JsonDB ��json �ļ��������ݿ�
 * @version 1.0
 * @author xiaogg <xiaogg@sina.cn>
 */
class JsonDB{
    private $dbpath='./data/';
    private $is_zip =1;//�Ƿ�Ϊѹ���洢
    private $dat_path;
    /**
     * ��ʼ�������ݿ�
     * @param $dbname �����ļ��Ĵ��·��
     */
    public function __construct($dbname=''){
        return $this->open($dbname);
    }
    /**
     * ��ʼ�������ݿ�
     * @param $dbname �����ļ��Ĵ��·��
     */
    public function open($dbname=''){
        if(empty($dbname))return false;
        $this->dat_path = $this->dbpath.$dbname.'.json';return true;        
    }
    /**
     * ������� ��ʼ��ʱ�������
     * @param $param Ҫ��ӵ�����
     * @param $is_multi �Ƿ�Ϊ������� 0 ��������� 1����׷�� 2��������
     */
    public function add($param,$is_multi=0){
        if(empty($param))return false;
        $data=$is_multi==2?array():$this->readcontent();
        if($is_multi){
            $data=empty($data)?$param:$data+$param;
        }else{
            $data[]=$param;
        }
        $this->writecontent($data);
        return count($data);
    }
    /**
     * ��ѯ������¼
     * @param $param ���ʽ ����
     * @param $limit ��ȡ����0����
     */
    public function select($param='',$limit=0){
        $data=$this->readcontent();
        if(empty($param))return $data;
        if(!is_array($param))$param=array('id'=>$param);
        if(empty($param['_logic']))$param['_logic']='and';
        $param['_logic']=strtolower($param['_logic']);$i=0;
        $result=array();$limit=intval($limit);
        foreach($data as $k=> $v){
            if($limit>0 && $limit<=$i)break;
            $is_del=$param['_logic']=='and'?false:true;
            foreach($param as $key=> $val){
                if($key=='_logic')continue;
                if(!is_array($val)){$val=array('eq',$val);}
                if(!$this->checkdata($v[$key],$val)){
                    if($param['_logic']=='and')$is_del=true;
                }else{
                    if($param['_logic']!='and')$is_del=false;
                }
            }
            if($is_del)unset($data[$k]);else {$result[]=$v;$i++; }    
        }$this->close();
        return $result;
    }
    /**
     * ��ѯ����
     * @param $param ���ʽ ����
     * @param $field ��ѯ���ֶ�
     */
    public function find($param='',$field='*'){
        $data=$this->select($param,1);
        if(empty($data))return false;
        $info=$data[0];
        if($this->str_exists($field,','))$field=explode(',',$field);
        if($field!='*' && is_array($field)){
            foreach($info as $k=>$v){
                if(!in_array($k,$field))unset($info[$k]);
            }return $info;
        }
        return $field=='*'?$info:$info[$field];
    }
    /** �������ʽ*/
    public function checkdata($data,$exp){
        if(empty($exp))return false;
        $exp[0]=strtolower($exp[0]);
        $allow=array('eq','neq','like','in','notin','gt','lt','egt','elt','heq','nheq','between','notbetween');
        if(!in_array($exp[0],$allow))return false;
        switch($exp[0]){
            case "eq":return $data==$exp[1];break;
            case "neq":return $data!=$exp[1];break;
            case "heq":return $data===$exp[1];break;
            case "nheq":return $data!==$exp[1];break;
            case "like":return $this->str_exists($data,$exp[1]);break;
            case "in":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return in_array($data,$exp[1]);
            break;
            case "notin":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return !in_array($data,$exp[1]);
            break;
            case "gt":return $data>$exp[1];break;
            case "lt":return $data<$exp[1];break;
            case "egt":return $data>=$exp[1];break;
            case "elt":return $data<=$exp[1];break;
            case "between":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return $data>=$exp[1][0] && $data<=$exp[1][1];
            break;
            case "notbetween":
            if(!is_array($exp[1]))$exp[1]=explode(',',$exp[1]);
            return $data<$exp[1][0] && $data>$exp[1][1];
            break;
        }
        return false;
    }
    /**
     * ��ȡ���ݿ�ȫ��
     * @param $path ·��
     */
    private function readcontent($dbname=''){
        $path = empty($dbname)?$this->dat_path:$this->dbpath.$dbname.'.json';if(empty($path))return false;
        $globalname='g'.md5($path);
        GLOBAL $$globalname;if($$globalname)return $$globalname;
        $contents=file_get_contents($path);
        if($contents && $this->is_zip && function_exists('gzcompress')){$contents=gzuncompress($contents);}
        $cache=json_decode($contents,true);unset($contents);
        if($cache)$$globalname=$cache;
        return $cache;
    }
    /**
     * д�����ݿ�ȫ��
     * @param array $data ��Ҫд�������
     * @param $path ·��
     */
    private function writecontent($data=array(),$dbname=''){
        if(empty($data)){return false;}
        $path = empty($dbname)?$this->dat_path:$this->dbpath.$dbname.'.json';if(empty($path))return false;
        $content=is_array($data)?json_encode($data):$data;
        if($content && $this->is_zip && function_exists('gzcompress')){$content=gzcompress($content);}
        file_put_contents($path,$content, LOCK_EX);
        return $data;
    }
    private function str_exists($haystack, $needle){
    	return !(strpos($haystack, $needle) === FALSE);
    }
    /** �ر�����*/
    public function close(){}
}
?>