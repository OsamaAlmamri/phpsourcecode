﻿<?php
echo SendPost('http://msg.yunhuatong.com/api/user/info/',array('user_id'=>$_GET['user_id']));


function _rand()
{
    $length=26;
    $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($chars) - 1;
    mt_srand((double)microtime() * 1000000);
    $string = '';
    for($i = 0; $i < $length; $i++) {

        $string .= $chars[mt_rand(0, $max)];

    }
    return $string;
}

function SendPost($Url,$Post=NULL,$TimeOut=30,$OnlyHead=false,$cookies=NULL,$Referer=NULL)
{
    return get($Url,$Referer,$TimeOut,$cookies,$Post,$OnlyHead);
}

function get($Url,$REFERER=NULL,$CURLOPT_TIMEOUT=30,$cookies=NULL,$Post=NULL,$OnlyHead=false)
{
    //set_time_limit(0);
    $HTTP_SESSION=_rand();
    $ch = curl_init();
    if($cookies)
    {
        //@unlink($cookies);
        //echo $_SERVER['DOCUMENT_ROOT'];
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT']."/".$cookies);
        curl_setopt($ch,CURLOPT_COOKIE,$cookies);
    }
    if($Post)
    {
        if(is_array($Post))
        {
            $o="";
            foreach ($Post as $k=>$v)
            {
                $o.= "$k=".urlencode($v)."&";
            }
            $Post=substr($o,0,-1);
        }
        curl_setopt($ch,CURLOPT_POSTFIELDS,$Post);
    }
    if($OnlyHead)
    {
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        //curl_setopt ($ch, CURLOPT_NOBODY, 1);
    }

    curl_setopt ($ch,CURLOPT_URL,$Url);//"http://".$HTTP_Server.$HTTP_URL
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    if($REFERER)
        curl_setopt($ch, CURLOPT_REFERER, $REFERER);
    curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
    //curl_setopt($ch,CURLOPT_COOKIE,$HTTP_SESSION);
    curl_setopt($ch,CURLOPT_TIMEOUT, $CURLOPT_TIMEOUT);
    $res = curl_exec($ch);
    curl_close ($ch);
    return $res;
}
?>
