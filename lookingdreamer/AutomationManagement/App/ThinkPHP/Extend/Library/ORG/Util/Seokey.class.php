<?php
/**
*     �ؼ��� �滻��  �滻(���λ��) ��ָ�������Ĺؼ���
      �ȶԹؼ��� md5����(���ؼ���֮�������ֲ��֣�md5���ܣ��ɷ�ֹ�ظ��滻)���滻��־/&--&/
/*    �滻��/*md5*/
/*    ����� �ؼ��� �滻�� **��ʽ

$KeyArray=Array(
    0=>array("Key"=>"this","Href"=>"<B>this</B>","ReplaceNumber"=>1),
    1=>array("Key"=>"test","Href"=>"<a href='test'>test</a>","ReplaceNumber"=>1)
);
$str = "this is test content!";
$a = new Seokey($KeyArray,$str);
$a->KeyOrderBy();
$a->Replaces();
echo $a->HtmlString;

*/
class Seokey{
    public $KeyArray;  //�ؼ���
    public $HtmlString; //��������
    public $ArrayCount; //�ؼ��ֵĸ���
    public $Key;
    public $Href;

    /*
        ��ʼ����
        $keyArray �ؼ��� ����
        $String   ������������
    */
    function Seokey($KeyArray,$String,$Key='Key',$Href='Href'){
       $this->KeyArray=$KeyArray;
       $this->HtmlString=$String;
       $this->ArrayCount=count($KeyArray);
       $this->Key=$Key;
       $this->Href=$Href;
    }

    /*
        �ؼ��� ����������
    */
    function KeyOrderBy(){
        usort($this->KeyArray,'sortcmp');
    }

    function Replaces(){
    		
        for($i=0;$i<$this->ArrayCount;$i++){

            if((integer)$this->KeyArray[$i]['ReplaceNumber'] != 0 ){
                str_replace($this->KeyArray[$i][$this->Key],"/*".md5($this->KeyArray[$i][$this->Key])."*/",$this->HtmlString,$num);//$num��ѯ��������

                if((integer)$this->KeyArray[$i]['ReplaceNumber']>$num) {//���ؼ��� ��Ҫ�滻������ ���� ����������ʱ���滻ȫ��
                    $this->KeyArray[$i]['ReplaceNumber']=$num;
                    $this->HtmlString=str_replace($this->KeyArray[$i][$this->Key],"/*".md5($this->KeyArray[$i][$this->Key])."*/",$this->HtmlString);
                    continue;
                }
                //���ؼ��� ��Ҫ�滻������ ������ ����������ʱ��ʹ�� KeyStrpos($i);�����滻
                $ListNumber=array();
                $ListNumber=$this->KeyStrpos($i);//$i: ��ʾ��$i���ؼ���($i��0��ʼ)
                $RegArray=array();

                if(count($ListNumber)<1) continue;//������ �ؼ���

                $n=0;
                while($n<(integer)$this->KeyArray[$i]["ReplaceNumber"]){
                    $g=0;
                    $x=rand(0,count($ListNumber)-1);//�����
                    for($xcn=0;$xcn<=$n;$xcn++){
                        if($RegArray[$xcn]==$ListNumber[$x]){
                            $g=1;
                        }
                    }
                    if($g==0){
                        $RegArray[$n]=$ListNumber[$x];
                        $n++;
                    }
                }

                for($c=0;$c<count($RegArray)-1;$c++)
                {//�ؼ�������λ�� ��������
                    for($jx=$c+1;$jx<count($RegArray);$jx++){
                        if($RegArray[$c]>$RegArray[$jx]){
                            $TempArray=$RegArray[$c];
                            $RegArray[$c]=$RegArray[$jx];
                            $RegArray[$jx]=$TempArray;
                        }
                    }
                }

                for($c=0;$c<count($RegArray);$c++){
                    $this->StrposKey($this->KeyArray[$i][$this->Key],$RegArray[$c],$c);// ��λ(����λ) �滻��ȡ���Ĺؼ���
                }

               $this->HtmlString=str_replace("/&".md5($this->KeyArray[$i][$this->Key])."&/",$this->KeyArray[$i][$this->Key],$this->HtmlString);
            }else{
               $this->HtmlString=str_replace($this->KeyArray[$i][$this->Key],"/*".md5($this->KeyArray[$i][$this->Key])."*/",$this->HtmlString);
            }
       }

       for($i=0;$i<$this->ArrayCount;$i++){
           $this->HtmlString=str_replace("/*".md5($this->KeyArray[$i][$this->Key])."*/",$this->KeyArray[$i][$this->Href],$this->HtmlString);
       }

    }

    function StrposKey($Key,$StrNumber,$n){//���ַ����� ��ȡ�ؼ��� ���滻����$StrNumber���λ�ÿ�ʼ(����$StrNumber���λ��)�滻��$n(����$n���λ��)���λ��
       $this->HtmlString=substr_replace($this->HtmlString, "/*".md5($Key)."*/", $StrNumber, 36);
    }

    /* �ݹ� ���� �ؼ��� ���ڵ�λ�� ���������� */
    function KeyStrpos($KeyId){
        $StrListArray=array();
        $StrNumberss=strpos($this->HtmlString, $this->KeyArray[$KeyId][$this->Key]);
        $xf=0;
        while(!($StrNumberss===false)){
            $StrListArray[$xf]=$StrNumberss;
            $this->HtmlString=substr_replace($this->HtmlString,"/&".md5($this->KeyArray[$KeyId][$this->Key])."&/",$StrNumberss, strlen($this->KeyArray[$KeyId][$this->Key]));
            $StrNumberss=strpos($this->HtmlString, $this->KeyArray[$KeyId][$this->Key]);
            $xf++;
        }
        return $StrListArray;
    }
   
}
?>
