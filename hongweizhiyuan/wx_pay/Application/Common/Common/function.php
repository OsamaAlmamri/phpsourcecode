<?php
/**
 * ʹ��curl��ȡԶ������
 * @param  string $url url����
 * @return string      ��ȡ��������
 */
function curl_get_contents($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //���÷��ʵ�url��ַ
    // curl_setopt($ch,CURLOPT_HEADER,1);               //�Ƿ���ʾͷ����Ϣ
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //���ó�ʱ
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //�û����ʴ��� User-Agent
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);        //���� referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //����301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //���ؽ��
    $r=curl_exec($ch);
    curl_close($ch);
    return $r;
}