<?php
function GenerateContent($user,$content){
  $time=date("Y-m-d H:i:s",time());
  return $user . MESSAGE_DELIMITER . $time . MESSAGE_DELIMITER .$content;
}
function GenerateMessageID() {
  $Counter = new SaeCounter();
  return $Counter->incr(COUNTER_NAME);
}
function AddMessageToKVDB($mid,$content){
  $KVDB = new SaeKV();
  if(!$KVDB->init()){exit('KVDB Error! Please check whether the configuration of KVDB is correct.');}
  $key = MESSAGE_PREFIX . $mid;
  return $KVDB->set($key,$content);
}
function RenRenStatusUpdate($content,$id){
  $KVDB = new SaeKV();
  if(!$KVDB->init()){exit('KVDB Error! Please check whether the configuration of KVDB is correct.');}
  $PostData['access_token'] = $KVDB->get('RENREN-AccessToken');
  if($PostData['access_token']===false){sae_debug('Failed to publish to RenRen, please run the RenRen admin tool first!');return false;}
  if(RENREN_PAGE_ID != ''){$PostData['page_id']=RENREN_PAGE_ID;}
  $PostData['v']='1.0';
  $PostData['method']='status.set';
  $PostData['status']=$content;
  if(!(RENREN_TOPIC === false)){
    $PostData['status'] = '#' . RENREN_TOPIC . $id . '#' .$PostData['status'];
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,'https://api.renren.com/restserver.do');
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_HEADER,0);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($PostData));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  $result=curl_exec($ch);
  return true;
}
function removeXSS($val){
  // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
  // this prevents some character re-spacing such as <java\0script>
  // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
  $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

  // straight replacements, the user should never need these since they're normal characters
  // this prevents like <IMG SRC=@avascript:alert('XSS')>
  $search = 'abcdefghijklmnopqrstuvwxyz';
  $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $search .= '1234567890!@#$%^&*()';
  $search .= '~`";:?+/={}[]-_|\'\\';
  for ($i = 0; $i < strlen($search); $i++) {
    // ;? matches the ;, which is optional
    // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

    // @ @ search for the hex values
    $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val);
    // with a ;
    // @ @ 0{0,7} matches '0' zero to seven times
    $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val);
    // with a ;
  }

  // now the only remaining whitespace attacks are \t, \n, and \r
  $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
  $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
  $ra = array_merge($ra1, $ra2);

  $found = true;
  // keep replacing as long as the previous round replaced something
  while ($found == true) {
    $val_before = $val;
    for ($i = 0; $i < sizeof($ra); $i++) {
      $pattern = '/';
      for ($j = 0; $j < strlen($ra[$i]); $j++) {
        if ($j > 0) {
          $pattern .= '(';
          $pattern .= '(&#[xX]0{0,8}([9ab]);)';
          $pattern .= '|';
          $pattern .= '|(&#0{0,8}([9|10|13]);)';
          $pattern .= ')*';
        }
        $pattern .= $ra[$i][$j];
      }
      $pattern .= '/i';
      $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2);
      // add in <> to nerf the tag
      $val = preg_replace($pattern, $replacement, $val);
      // filter out the hex tags
      if ($val_before == $val) {
        // no replacements were made, so exit the loop
        $found = false;
      }
    }
  }
  return $val;
}
function DeleteMessageFromKVDB($MessageID){
  if(empty($MessageID)||!is_numeric($MessageID)){return false;}
  $KVDB = new SaeKV();
  if(!$KVDB->init()){return false;}
  if($KVDB->delete(MESSAGE_PREFIX . $MessageID)){return true;}else{return false;}
}
function PushMessage($content){
  $Channel = new SaeChannel();
  $Channel -> sendMessage(CHANNEL_NAME,$content,true);
}
