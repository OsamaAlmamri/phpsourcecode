function tips(c){ $.dialog({content: '<font style="font-size:14px;">'+c+'</font>',fixed: true, width:300, time:2000});}
function succ(c){ $.dialog({icon: 'succeed',content: '<font  style="font-size:14px;">'+c+'</font>' , time:2000});}
function error(c){$.dialog({icon: 'error',content: '<font  style="font-size:14px;">'+c+'</font>' , time:2000});}


//插入到编辑器
function insertEdit(val)
{
	if(editor){ editor.pasteText(val);}else{ editors.pasteText(val);}
}


/*视频弹出或隐藏播放*/
function showVideo(id,url)
{
	 if($('#play_'+id).is(":hidden")){
		  $('#swf_'+id).html('<object width="500" height="420" id="swf_'+id+'"><param name="allowscriptaccess" value="always"></param><param name="wmode" value="window"></param><param name="movie" value="'+url+'"></param><embed src="'+url+'" width="500" height="420" allowscriptaccess="always" wmode="window" type="application/x-shockwave-flash"></embed></object>')
		  $('#play_'+id).show();
	 }else{
		$('#swf_'+id).find('object').remove();
		$('#play_'+id).hide();
	 }
	$('#img_'+id).toggle();
}

function showMp3(id,url)
{
	$('#mp3swf_'+id).toggle();
	$('#mp3img_'+id).toggle();
}



//显示和隐藏
	function viewcontent(){
		$("#displaycontent").toggle();
		$("#displaytitle").hide();
	}
	//显示和隐藏
	function closecontent(){
		$("#displaycontent").hide();
		$("#displaytitle").toggle();
	}

/*显示标签界面*/
function showTagFrom(){ $('#tagFrom').find('input[name=tags]').val('');$('#tagFrom').toggle('fast');}
/*提交标签*/
function savaTag(tid)
{
	var tag = $('#tags').val();
		if(tag ==''){ tips('请输入标签哟^_^');$('#tagFrom').show('fast');}else{
			var url = siteUrl+'index.php?app=home&c=tag&a=add_ajax';
			$.post(url,{objname:'topic',idname:'topicid',tags:tag,objid:tid},function(res){ window.location.reload(); })
		}
	
}

//加入小组Ajax
function joinGroup(gid){
	var url = siteUrl+'index.php?app=group&a=do&ik=joingroup';
		$.post(url,{groupid:gid},function(rs){
			  if(rs == 0){
				  $.dialog.open(siteUrl+'index.php?app=user&a=ajax&ik=login', {title: '登录'});
			  }else if(rs == 1){
				  error('你已经加入该小组，请不要再次加入^_^');
			  }else if(rs == 2){
				  succ('加入小组成功^_^');
				  window.location.reload(); 
			  }
		})
}

//退出小组Ajax
function exitGroup(gid){
	art.dialog.confirm('你确认退出小组吗？', function(){								 
		var url = siteUrl+'index.php?app=group&a=do&ik=exitgroup';
		$.post(url,{groupid:gid},function(rs){
			  if(rs == 0){
				  error('组长责任重于泰山^_^');
			  }else if(rs == 1){
				  succ('退出小组成功^_^');
				  window.location.reload(); 
			  }
		})
	});
}

//删除帖子
function topic_del(gid,tid){
	art.dialog.confirm('确定删除吗？', function(){							  
		var url = siteUrl+'index.php?app=group&a=do&ik=topic_del';
		$.post(url,{groupid:gid,topicid:tid},function(rs){
					if(rs == 0){
						succ('删除成功^_^');
						window.location = siteUrl+'index.php?app=group&a=show&id='+gid;
					}
		})
	});
}

//收藏帖子
/*
function topic_collect(tid){
	
	var url = siteUrl+'index.php?app=group&a=do&ik=topic_collect';
	$.post(url,{topicid:tid},function(rs){
			if(rs == 0){
				$.dialog.open(siteUrl+'index.php?app=user&a=ajax&ik=login', {title: '登录'});
			}else if(rs == 1){
				tips('自己不能收藏自己的帖子哦^_^');
			}else if(rs == 2){
				tips('你已经收藏过本帖啦，请不要再次收藏^_^');
			}else{
				succ('恭喜您，帖子收藏成功^_^');
				topic_collect_user(tid);
			}					
	});
}
*/

//谁收藏了这篇帖子
function topic_collect_user(topicid){
	var url = siteUrl+'index.php?app=group&a=topic_collect_user&ik=ajax&topicid='+topicid;
	$.post(url,function(rs){ $('#collects').html(rs); });
}


function newTopicFrom(that)
{
	var title = $(that).find('input[name=title]').val();
	//var typeid = $(that).find('select[name=typeid]').val();
	var content = $(that).find('textarea[name=content]').val();
	if(title == '' || content == ''){tips('请填写标题和内容'); return false;}
	//if(typeid == 0){tips('请选择分类'); return false;}
	$(that).find('input[type=submit]').val('正在提交^_^').attr('disabled',true);
}
//新建小组
function createGroup(that)
{
	var groupname = $(that).find('input[name=groupname]').val();
	var groupdesc = $(that).find('textarea[name=groupdesc]').val();
	var oneid = $(that).find('select[name=oneid]').val();
	var tag = $(that).find('input[name=tag]').val();
	var grp_agreement = $(that).find('input[name=grp_agreement]').val();
	if(groupname == '' || groupname==0){tips('小组标题必须填写；且必须在2-30个字以内'); return false;}
	if(groupdesc == '' || groupdesc==0){tips('小组描述必须填写；且必须大于10个字'); return false;}
	if(groupname.length<2){ tips('小组标题必须大于2个字'); return false; }
	if(groupdesc.length<10){ tips('小组描述必须大于10个字'); return false; }
	if(oneid == 0){tips('小组分类必须选择一个'); $(that).find('select[name=oneid]').focus();return false;}
	if(tag =='')
	{
		tips('小组标签不为空');
		$(that).find('input[name=tag]').focus();
		return false;
	}else if(!checkTag('#tag')){
		return false;
	}
}

function checkTag(obj)
{
	var _self = $(obj), _val = _self.val(), tempval, arr;
	tempval = _val.replace(/\s+/g, ' ');
	arr = tempval.split(' ');
	for(var i=0; i< arr.length; i++)
	{
		if(arr[i].length > 8)
		{
			_self.parent().find('.tip').html('<span class="red">每个标签最长8个汉字</span>')
			return false;
		}
		
	}
	if(arr.length>5)
	{
		_self.parent().find('.tip').html('<span class="red">最多 5 个标签</span>');
		return false;
	}else{
		_self.parent().find('.tip').html('<span>最多 5 个标签</span>')
	}
	_self.val(tempval);
	
	return true;
}

//新建小组 tag-items
function tags(obj)
{
	var text = $(obj).text(), input = $('#tag');
	var vals = $('#tag').val();
	//设置input
	if($(obj).hasClass('selected'))
	{ 
		$(obj).removeAttr('class');
		//删除
		var value = vals.replace(' '+text, '').replace(text, '').replace(/\s+/, ' ');
		input.val($.trim(value));

	}else{
		if(vals.split(' ').length < 5)
		{
			$(obj).attr('class','selected');
			input.val(vals ? vals + " " + text : text);
		}
	}
}

//Ctrl+Enter 回复评论

function keyRecomment(rid,tid,event)
{
     if(event.ctrlKey == true)
	 {
		if(event.keyCode == 13)
		recomment(rid,tid);
		return false;
	}
}

//回复评论
function recomment(rid,tid){

	c = $('#recontent_'+rid).val();
	if(c==''){tips('回复内容不能为空');return false;}
	var url = siteUrl+'index.php?app=group&c=topic&a=recomment';
	$('#recomm_btn_'+rid).hide();
	$.post(url,{referid:rid,topicid:tid,content:c} ,function(rs){
		if(rs.status == 0)
		{
			succ('回复成功');
			window.location.reload();
		}else if( rs.status == 1){
			
			tips(rs.msg)
			$('#recomm_btn_'+rid).show();
		}
	})	
}
