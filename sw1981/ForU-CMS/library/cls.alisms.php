<?php
/**
 * 签名助手 2017/11/19
 *
 * Class SignatureHelper
 */
class Alisms {

  /**
   * 生成签名并发起请求
   *
   * @param $accessKeyId string AccessKeyId (https://ak-console.aliyun.com/)
   * @param $accessKeySecret string AccessKeySecret
   * @param $domain string API接口所在域名
   * @param $params array API具体参数
   * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
   */
  public function send($phoneNumbers, $templateCode='', $signName='', $templateParam='') {

    $params = array ();

    $accessKeyId = ALISMS_KEY_ID;
    $accessKeySecret = ALISMS_KEY_SECRET;

    $params["PhoneNumbers"] = $phoneNumbers;

    $params["SignName"] = !empty($signName) ? $signName : ALISMS_SIGNNAME;

    $params["TemplateCode"] = !empty($templateCode) ? $templateCode : ALISMS_TEMPLATE_CODE;

    if (!empty($templateParam)) {
      $params['TemplateParam'] = $templateParam;
    }

    if (!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
      $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_NUMERIC_CHECK);
    }

    $content = $this->request(
      $accessKeyId,
      $accessKeySecret,
      "dysmsapi.aliyuncs.com",
      array_merge($params, array(
        "RegionId" => "cn-hangzhou",
        "Action" => "SendSms",
        "Version" => "2017-05-25",
      ))
    );
    return $content;
  }

  public function request($accessKeyId, $accessKeySecret, $domain, $params) {
    $apiParams = array_merge(array (
      "SignatureMethod" => "HMAC-SHA1",
      "SignatureNonce" => extension_loaded('openssl') ? uniqid(openssl_random_pseudo_bytes(0,0xffff), true) : uniqid(mt_rand(0,0xffff), true),
      "SignatureVersion" => "1.0",
      "AccessKeyId" => $accessKeyId,
      "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
      "Format" => "JSON",
    ), $params);
    ksort($apiParams);

    $sortedQueryStringTmp = "";
    foreach ($apiParams as $key => $value) {
      $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
    }

    $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));

    $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));

    $signature = $this->encode($sign);

    $url = "http://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";

    try {
      $content = $this->fetchContent($url);
      return json_decode($content, true);
    } catch( \Exception $e) {
      return false;
    }
  }

  private function encode($str) {
    $res = urlencode($str);
    $res = preg_replace("/\+/", "%20", $res);
    $res = preg_replace("/\*/", "%2A", $res);
    $res = preg_replace("/%7E/", "~", $res);
    return $res;
  }

  private function fetchContent($url) {
    if (function_exists("curl_init")) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "x-sdk-client" => "php/2.0.0"
      ));
      $rtn = curl_exec($ch);

      if ($rtn === false) {
        trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
      }
      curl_close($ch);

      return $rtn;
    }

    $context = stream_context_create(array(
      "http" => array(
        "method" => "GET",
        "header" => array("x-sdk-client: php/2.0.0"),
      )
    ));

    return file_get_contents($url, false, $context);
  }
}
