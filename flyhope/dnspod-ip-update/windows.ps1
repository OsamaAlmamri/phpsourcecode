# ʹ�ô˳�������Ƿ�Server����ϵͳ��������Administrator���ִ��set-executionpolicy remotesigned��ſ���

$netadapter_all=Get-Netadapter | ? {$_.Status -eq "Up"}
$adapter_names="NONE","��̫��","LAN","WLAN"

$dns_updated=0
:label_adapter_names foreach($adapter_name in $adapter_names) {
    foreach($adapter in $netadapter_all) {
        if($adapter.Name -eq $adapter_name) {
            $ip=Get-Netadapter -Name $adapter_name |Get-NetIPAddress | ? {$_.AddressFamily -eq "IPv4"}| Select-Object -First 1|  Select-Object -ExpandProperty IPAddress
            $dns_updated=1
            break label_adapter_names;
        }
    }
}

if(!$ip) {
    $ip=Get-Netadapter | ? {$_.Status -eq "Up"} |Get-NetIPAddress | ? {$_.AddressFamily -eq "IPv4"}| Select-Object -First 1|  Select-Object -ExpandProperty IPAddress
}

if($ip) {
    echo "Find ip : $ip"
    $ip | php load.php
}
