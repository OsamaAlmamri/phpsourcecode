<?php
class cleartitlePlugin extends PT_Plugin{
    //�����hook
    public $tag='template_compile_end';
    // Ĭ������
    public $config=array();
    // ִ�з���
    public function run(&$content)
    {
        //$config=$this->loadconfig();
        $content=preg_replace('{\s*-\s*Power\s*by\s*PTcms\s*</title>}isU','</title>',$content);
        return $content;
    }
}