<?php
/**
 * JsonDB ��json �ļ��������ݿ�
 * @version 2.0
 * @author xiaogg <xiaogg@sina.cn>
 */
class JsonDB{
    private $dbpath='./data/';
    private $dat_path;
    public $data_type =0;//�Զ��ֱ�����
    public $data_length =1;//���ݷֱ���
    public $max_limit=100;//����ȡ������
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
     * @param $id�ֱ��ʶ�ֱ�ʱ����
     */
    public function open($dbname='',$id=''){
        if(empty($dbname))return false;
        $dbname=$this->data_type?$this->getTable($dbname,$id):$dbname;
        $this->dat_path = $this->dbpath.$dbname.'.json';return true;        
    }
    /**
     * ������� ��ʼ��ʱ�������
     * @param $param Ҫ��ӵ�����
     * @param $is_multi �Ƿ�Ϊ׷�� 0 ׷�� 1����
     */
    public function add($param,$is_multi=0){
        if(empty($param))return false;
        $this->writecontent($param,$is_multi);
        return count($param);
    }
    /**
     * ��ѯ������¼
     * @param $param ���ʽ ����
     * @param $limit ��ȡ����0����
     */
    public function select($param='',$limit=0){        
        $file = $this->dat_path;if(empty($file) || !file_exists($file))return false;
        if(empty($limit) || $limit>$this->max_limit)$limit=$this->max_limit;
        $handle = fopen($file, "rb");
        $i=0;$result=array();
        while(!feof($handle)){
            if($limit>0 && $limit<=$i)break;
            $res= json_decode(fgets($handle),true);//��ȡһ��
            $check=$res?$this->loop_check($param,$res):'';
            if($check){$result[]=$res;$i++;}
            unset($res);
        }fclose($handle);
        return $result;
    }
    //ѭ����֤����
    private function loop_check($param,$data=''){
        if(empty($param['_logic']))$param['_logic']='and';$param['_logic']=strtolower($param['_logic']);
        $return=$param['_logic']=='and'?true:false;
        foreach($param as $key=> $val){
            if($key=='_logic')continue;
            if(!is_array($val)){$val=array('eq',$val);}
            if(!$this->checkdata($data[$key],$val)){
                if($param['_logic']=='and')$return=false;
            }else{
                if($param['_logic']!='and')$return=true;
            }
        }
        return $return;  
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
     * д�����ݿ�ȫ��
     * @param array $data ��Ҫд�������
     * @param $type 0׷�� 1����
     * @param $dbname ���ݿ�����
     */
    private function writecontent($data=array(),$type=0,$dbname=''){
        if(empty($data)){return false;}
        $path = empty($dbname)?$this->dat_path:$this->dbpath.$dbname.'.json';if(empty($path))return false;
        $write=$type?'wb':'ab';
        $fp = fopen($path, $write);
        foreach($data as $k=>$v){fwrite($fp,json_encode($v)."\n");}
        fclose($fp);
        return true;
    }
    /**
     * �������ݱ���֧�ֱַ�
     * @param $table ��������
     * @param $id ���ڷֱ�Ļ�������
     */
    public function getTable($table='',$id=""){
        if(empty($table) || empty($id))return $table;
        $str='';
        switch($this->data_type){
            case 1:$str=md5($id);break;//md5
            case 2:$str=sha1($id);break;//sha1
            default:$str=$id;break;//ԭ�ַ�
        }
        return $table.'_'.substr($str,0,$this->data_length);        
    }
    private function str_exists($haystack, $needle){
    	return !(strpos(''.$haystack, ''.$needle) === FALSE);
    }
}
?>