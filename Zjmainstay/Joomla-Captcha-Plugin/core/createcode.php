<?php
/**
 * �û���֤�������ļ�
 * @PreVersionAuthor:wangsl
 * @Author:Zjmainstay
 * @version : 1.0
 * @creatdate: 2013-10-4
 */
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
if(!class_exists('Utilscaption')) require dirname(__FILE__) .'/utilscaption.class.php';
$rsi                   	= new Utilscaption();
$rsi->Length           	= 4;																//��֤���ַ�����
$rsi->TFontSize        	= array(15,17);														//�����С��Χ
$rsi->Width            	= isset($_SESSION['imgWidth']) ? (int)$_SESSION['imgWidth'] : 50;	//��֤����
$rsi->Height           	= isset($_SESSION['imgHeight']) ? (int)$_SESSION['imgHeight'] : 25;	//��֤��߶�
$rsi->Chars            	= '0123456789';														//��֤���ַ�
$rsi->TFontAngle       	= array(-20,20); 													//��ת�Ƕ�
$rsi->FontColors      	= array('#f36161','#6bc146','#5368bd');  							//������ɫ,������
$code                  	= $rsi->RandRSI();													//������֤���ַ�
$_SESSION["CHECKCODE"] 	= $code;															//�洢��֤��session
$rsi->Draw();																				//������֤��ͼ��
exit;
?>