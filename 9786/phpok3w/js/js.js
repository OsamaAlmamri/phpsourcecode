//window.onerror = function(){return true;}
String.prototype.trim = function(){ return this.replace(/(^\s*)|(\s*$)/g, "");}

function ImageZoom(Img,width,height)
{ 
	var image=new Image(); 
	image.src=Img.src;
	if(image.width>width||image.height>height)
	{
		w=image.width/width; 
		h=image.height/height; 
		if(w>h)
		{
			Img.width=width; 
			Img.height=image.height/w; 
		}
		else
		{
			Img.height=height; 
			Img.width=image.width/h; 
		} 
	}
}

function ImageOpen(Img)
{
	window.open(Img.src);
}

function Ok3w_Search(frm)
{
	if(frm.q.value.trim()=="")
	{
		alert("�������ѯ�ؼ��ʡ�");
		frm.q.focus()
		return false;
	}
	frm.action = "search.asp";
	frm.bntSub.disabled = true;
	frm.submit();
}

function SetCwinHeight(obj)
{
  var cwin=obj;
  if (document.getElementById)
  {
    if (cwin && !window.opera)
    {
      if (cwin.contentDocument && cwin.contentDocument.body.offsetHeight)
        cwin.height = cwin.contentDocument.body.offsetHeight; 
      else if(cwin.Document && cwin.Document.body.scrollHeight)
        cwin.height = cwin.Document.body.scrollHeight;
    }
  }
}

function oCopy(a,type,str){
	if(str=="" || str=="http://")
	{
		alert("������û����д�������");
		return false;
	}
	a.target="_blank";
	if(type==1)
		a.href = str;
	if(type==2)
		a.href = "mailto:" + str;
	if(type==3)
		a.href = "tencent://message/?uin="+ str + "&Site=im.qq.com&Menu=yes";
}

function Ok3w_insertFlash(base_url, focus_width, focus_height, swf_height, text_height, pics, links, texts)
{
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
	document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="'+base_url+'images/focus.swf"><param name="quality" value="high"><param name="bgcolor" value="#FFFFFF">');
	document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
	document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">');
	document.write('</object>');
}

function Ok3w_Marquee(RndID,StrGD,Width,Height,Speed)
{
	document.write('<div id="'+RndID+'" style="overflow:hidden;height:'+Height+'px;width:'+Width+'px;">');
	document.write('<table border="0" cellspacing="0" cellpadding="0">');
	document.write('  <tr>');
	document.write('    <td id="'+RndID+'1" height="'+Height+'" nowrap="nowrap">'+StrGD+'</td>');
	document.write('    <td id="'+RndID+'2" height="'+Height+'" nowrap="nowrap"></td>');
	document.write('  </tr>');
	document.write('</table>');
	document.write('</div>');
	
	if(Speed==0)
		return;
	var speed = Speed;
	var pro = document.getElementById(RndID);
	var pro1 = document.getElementById(RndID+"1");
	var pro2 = document.getElementById(RndID+"2");
	pro2.innerHTML=pro1.innerHTML;
	var MyMar=setInterval(Marquee,speed) 
	pro.onmouseover=function() {clearInterval(MyMar)} 
	pro.onmouseout=function() {MyMar=setInterval(Marquee,speed)} 
	function Marquee()
	{ 
		var mm_mo = pro.offsetWidth - pro1.offsetWidth;
		if(mm_mo<0) mm_mo=0;
		if(pro2.offsetWidth-pro.scrollLeft<=mm_mo) 
			pro.scrollLeft-=pro1.offsetWidth;
			else
				pro.scrollLeft+=5;
	} 
}

function Get_ValidCode(dns)
{
	var obj = document.getElementById("strValidCode");
	obj.src = dns+"c/validcode.asp?rnd="+Math.random();
}