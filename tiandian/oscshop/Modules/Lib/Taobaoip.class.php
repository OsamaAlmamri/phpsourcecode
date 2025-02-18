<?php
/**
 * oscshop 电子商务系统
 *
 * ==========================================================================
 * @link      http://www.oscshop.cn/
 * @copyright Copyright (c) 2015 oscshop.cn. 
 * @license   http://www.oscshop.cn/license.html License
 * ==========================================================================
 *
 * @author    李梓钿
 *
 */
namespace Lib;
class Taobaoip {

/**
 * 淘宝IP地址库 Reset API
 * @author Chunice <hrb@usa.com>
 * @param  [string] $ip [IP地址]
 * @return [type]     [只返回获取成功的ip数据]
 */
    public function getLocation($ip) {
        if (empty($ip)) $ip = get_client_ip();
        $taobaoUrl = "http://ip.taobao.com/service/getIpInfo.php?ip=";
        $url       = $taobaoUrl . $ip;
        $data      = self::httpRequest($url);
        $data      = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data);
        $data      = json_decode($data, true);
        return $data[data];
    }

    Static Private function httpRequest($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output === FALSE) {
            return "cURL Error: " . curl_error($ch);
        }
        return $output;
    }


}
?>