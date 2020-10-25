$.fn.serializeObject = function() {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
      if (o[this.name] !== undefined) {
          if (!o[this.name].push) {
              o[this.name] = [o[this.name]];
          }
          o[this.name].push(this.value || '');
      } else {
          o[this.name] = this.value || '';
      }
  });
  return o;
}; 
/*
* type              请求的方式  默认为post
* url               发送请求的地址
* param             发送请求的参数
* dataType          返回JSON数据  默认为JSON格式数据
* callBack          请求的回调函数
*/
(function(){
    function AjaxRequest(opts){
        this._base_param  = {};
        this._baseUrl     = "";
        this.headers      = opts.headers || {};
        this.type         = opts.type || "post";
        this.url          = this._baseUrl+opts.url;
        this.param        = opts.param || {};
        this.headers      = opts.headers || {};
        this.async        = opts.async || false;
        this.dataType     = opts.dataType || "json";
        this.callBack     = opts.callBack;
        this.is_original_data = opts.is_original_data || false;
        this.initParam();
        this.init();
    }

    AjaxRequest.prototype = {
        //参数初始化
        initParam:function(){
            Object.assign(this.param,this.param,this._base_param);
        },
        //初始化
        init: function(){
            this.sendRequest();
        },
        //渲染loader
        showLoader: function(){
            layer.open({type:3}); 
        },
        //隐藏loader
        hideLoader: function(){
            layer.closeAll('loading');
        },
        //提示消息
        toast:function(type=1,title="",content=""){
            layer.open({
                type:type,
                title:title,
                content:content
            });
        },
        //发送请求
        sendRequest: function(){
            var self = this;
            $.ajax({
                type: this.type,
                url: this.url,
                data: this.param,
                async:this.async,
                headers:this.headers,
                dataType: this.dataType,
                beforeSend: this.showLoader(),
                success: function(res){
                    self.hideLoader();
                    if (typeof res == "object" && res.code==0) {
                        if(self.callBack){
                            if (Object.prototype.toString.call(self.callBack) === "[object Function]") {   //Object.prototype.toString.call方法--精确判断对象的类型
                                self.is_original_data?self.callBack(res):self.callBack(res.data);
                            }else{
                                console.log("callBack is not a function");
                            }
                        }
                    }else{
                        self.toast(0,"出错了",res.msg);
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown){
                    self.hideLoader();
                    self.toast(0,"请求出错");
                }
            });
        }
    };
    window.AjaxRequest = AjaxRequest;
})();

;(function () {
    //全局ajax处理
    $.ajaxSetup({
        complete: function (jqXHR) {},
        data: {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //请求失败处理
        }
    });

    if ($.browser && $.browser.msie) {
        //ie 都不缓存
        $.ajaxSetup({
            cache: false
        });
    }

    //不支持placeholder浏览器下对placeholder进行处理
    if (document.createElement('input').placeholder !== '') {
        $('[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur().parents('form').submit(function () {
            $(this).find('[placeholder]').each(function () {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
        });
    }

    // 所有加了dialog类名的a链接，自动弹出它的href
	if ($('a.js-dialog').length) {
		Wind.use('artDialog', 'iframeTools', function() {
			$('.js-dialog').on('click', function(e) {
				e.preventDefault();
				var $this = $(this);
				art.dialog.open($(this).prop('href'), {
					close : function() {
						$this.focus(); // 关闭时让触发弹窗的元素获取焦点
						return true;
					},
					title : $this.prop('title')
				});
			}).attr('role', 'button');

		});
	}

    // 所有的ajax form提交,由于大多业务逻辑都是一样的，故统一处理
    var ajaxForm_list = $('form.js-ajax-form');
    if (ajaxForm_list.length) {
        Wind.use('ajaxForm', 'artDialog','validate', function () {
            
            var $btn;

            $('button.js-ajax-submit').on('click', function (e) {
                var btn = $(this),form = btn.parents('form.js-ajax-form');
                $btn=btn;
                
                if(btn.data("loading")){
            		return;
            	}

                //批量操作 判断选项
                if (btn.data('subcheck')) {
                    btn.parent().find('span').remove();
                    if (form.find('input.js-check:checked').length) {
                        var msg = btn.data('msg');
                        if (msg) {
                            art.dialog({
                                id: 'warning',
                                icon: 'warning',
                                content: btn.data('msg'),
                                cancelVal: '关闭',
                                cancel: function () {
                                    //btn.data('subcheck', false);
                                    //btn.click();
                                },
                                ok: function () {
                                	 btn.data('subcheck', false);
                                	 btn.click();
                                }
                            });
                        } else {
                            btn.data('subcheck', false);
                            btn.click();
                        }

                    } else {
                        $('<span class="tips_error">请至少选择一项</span>').appendTo(btn.parent()).fadeIn('fast');
                    }
                    return false;
                }

                //ie处理placeholder提交问题
                if ($.browser && $.browser.msie) {
                    form.find('[placeholder]').each(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });
                }
            });
            
            ajaxForm_list.each(function(){
            	$(this).validate({
                	//是否在获取焦点时验证
    				//onfocusout : false,
    				//是否在敲击键盘时验证
    				onkeyup : function( element, event ) {
    					return;

    					// Avoid revalidate the field when pressing one of the following keys
    					// Shift       => 16
    					// Ctrl        => 17
    					// Alt         => 18
    					// Caps lock   => 20
    					// End         => 35
    					// Home        => 36
    					// Left arrow  => 37
    					// Up arrow    => 38
    					// Right arrow => 39
    					// Down arrow  => 40
    					// Insert      => 45
    					// Num lock    => 144
    					// AltGr key   => 225
    					var excludedKeys = [
    						16, 17, 18, 20, 35, 36, 37,
    						38, 39, 40, 45, 144, 225
    					];

    					if ( event.which === 9 && this.elementValue( element ) === "" || $.inArray( event.keyCode, excludedKeys ) !== -1 ) {
    						return;
    					} else if ( element.name in this.submitted || element.name in this.invalid ) {
    						this.element( element );
    					}
    				},
    				//当鼠标掉级时验证
    				onclick : false,
    				//给未通过验证的元素加效果,闪烁等
    				//highlight : false,
                	showErrors:function(errorMap, errorArr){
                		try {
    						$(errorArr[0].element).focus();
    						//alert(errorArr[0].message);
    					} catch (err) {
    					}
                	},
                	submitHandler:function(form){
                		var $form=$(form);
                		$form.ajaxSubmit({
                            url: $btn.data('action') ? $btn.data('action') : $form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                            dataType: 'json',
                            type:"post",
                            beforeSubmit: function (arr, $form, options) {
                            	
                            	$btn.data("loading",true);
                                var text = $btn.text();

                                //按钮文案、状态修改
                                $btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                            },
                            success: function (data, statusText, xhr, $form) {
                                var text = $btn.text();
                                //按钮文案、状态修改
                                $btn.removeClass('disabled').prop('disabled', false).text(text.replace('中...', '')).parent().find('span').remove();
                                if (data.code === 0) {
                                    $('<span class="tips_success">' + data.msg + '</span>').appendTo($btn.parent()).fadeIn('slow').delay(1000).fadeOut(function() {
                                        if(window.parent.frames.length == 1){
                                            parent.layer!==undefined && parent.layer.closeAll('iframe');
                                            parent.location.reload();
                                        }else{
                                            if(data.url){
                                                location.href = data.url;
                                            }else{
                                                reloadPage(window);
                                            }
                                        }
                                    });
                                    
                                } else{
                                	var $verify_img=$form.find(".verify_img");
                                	if($verify_img.length){
                                		$verify_img.attr("src",$verify_img.attr("src")+"&refresh="+Math.random()); 
                                	}
                                	
                                	var $verify_input=$form.find("[name='verify']");
                                	$verify_input.val("");
                                	
                                    $('<span class="tips_error">' + data.msg + '</span>').appendTo($btn.parent()).fadeIn('fast');
                                    $btn.removeProp('disabled').removeClass('disabled');
                                }
                                
                            },
                            error:function(xhr,e,statusText){
                            	alert(statusText);
                            	//刷新当前页
                                //reloadPage(window);
                            },
                            complete: function(){
                            	$btn.data("loading",false);
                            }
                        });
                	}
                });
            });

        });
    }
    // 直接提交数据，不通过validate验证数据，在有图片上传时会有问题
    $(".js-ajax-button").click(function() {
        var $btn = $(".js-ajax-button"),$form = $(".js-ajax-form");
        var formObject=$form.serializeObject();
        // 对于ke-editor编辑器
        if($form.find("textarea[class='ke-edit-textarea']").length &&　$form.find("textarea[class!='ke-edit-textarea']").length>0){
            $.each($form.find("textarea[class!='ke-edit-textarea']"),function(k,v){
                var key=$(v).attr("name");
                formObject[key]=editors[k].html();
            });
        }
        $.ajax({
            url: $btn.data('action') ? $btn.data('action') : $form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
            dataType: 'json',
            data: formObject,//$form.serialize(),
            type: "post",
            beforeSend: function(arr, $form, options) {

                $btn.data("loading", true);
                var text = $btn.text();
                //按钮文案、状态修改
                $btn.text(text + '中...').prop('disabled', true).addClass('disabled');
            },
            success: function(data, statusText, xhr) {
                var text = $btn.text();
                //按钮文案、状态修改
                $btn.removeClass('disabled').prop('disabled', false).text(text.replace('中...', '')).parent().find('span').remove();
                if (data.code === 0) {
                    $('<span class="tips_success">' + data.msg + '</span>').appendTo($btn.parent()).fadeIn('slow').delay(1000).fadeOut(function() {
                        if(window.parent.frames.length == 1){
                            parent.layer!==undefined && parent.layer.closeAll('iframe');
                            parent.location.reload();
                        }else{
                            location.href = data.url;
                        }
                        
                    });
                } else{
                    var $verify_img = $form.find("#captcha");
                    if ($verify_img.length) {
                        $verify_img.attr("src", "/captcha.html?t=" + Math.random());
                    }
                    $form.find("[name='verify']").val("");
                    $('<span class="tips_error">' + data.msg + '</span>').appendTo($btn.parent()).fadeIn('fast').fadeOut(1000);
                    $btn.removeProp('disabled').removeClass('disabled');
                }


            },
            error: function(xhr, e, statusText) {
                alert(statusText);
                //刷新当前页
                //reloadPage(window);
            },
            complete: function() {
                $btn.data("loading", false);
            }
        });
   });

    // 文本框统一ajax处理
    $(".ajax-text").each(function(){
        var newval;
        $(this).change(function(){
            newval=$(this).val();
            var data=$(this).attr("data"),action=$(this).attr("action");
            var dataArr=data.split("|"),table=dataArr[0],colum=dataArr[1],key=dataArr[2],keyval=dataArr[3];
            $.ajax({
                type:"post",
                dataType:"json",
                data:{"table":table,"colum":colum,"columval":newval,"key":key,"keyval":keyval},
                url:action,
                success:function(data){
                    if(data.code==0){
                        alert(data.msg);
                    }
                }
            });
        });
    });
    // 统一状态ajax处理
    $(".ajax-status").click(function(){
        $this=$(this);
        var data=$(this).attr("data"),src=$(this).attr("src"),action=$(this).attr("action");
        var dataArr=data.split("|"),table=dataArr[0],colum=dataArr[1],key=dataArr[2],keyval=dataArr[3];
        var newval=src.indexOf("btn_enable.png")>0?0:1;
        $.ajax({
            type:"post",
            dataType:"json",
            url:action,
            data:{"table":table,"colum":colum,"columval":newval,"key":key,"keyval":keyval},
            beforeSend:function(obj){
                $this.attr("src","/data/assets/images/btn_loading.gif");
            },
            success:function(data){
                if(data.code==1){
                    if(src.indexOf("btn_enable.png")>0){
                        $this.attr("src","/data/assets/images/btn_disable.gif");
                    }else{
                        $this.attr("src","/data/assets/images/btn_enable.png");
                    }
                }else{
                    alert(data.msg);
                }
                
            }
        });
    });
    //dialog弹窗内的关闭方法
    $('#js-dialog-close').on('click', function (e) {
        e.preventDefault();
        try{
            art.dialog.close();
        }catch(err){
            Wind.use('artDialog','iframeTools',function(){
                art.dialog.close();
            });
        };
    });

    //所有的删除操作，删除数据后刷新页面
    if ($('a.js-ajax-delete').length) {
        Wind.use('artDialog', function () {
            $('.js-ajax-delete').on('click', function (e) {
                e.preventDefault();
                var $_this = this,
                    $this = $($_this),
                    href = $this.data('href'),
                    msg = $this.data('msg');
                href = href?href:$this.attr('href');
                art.dialog({
                    title: false,
                    icon: 'question',
                    content:msg?msg: '确定要删除吗？',
                    follow: $_this,
                    close: function () {
                        $_this.focus();; //关闭时让触发弹窗的元素获取焦点
                        return true;
                    },
                    okVal:"确定",
                    ok: function () {
                    	$.post(href,function(data){
                            if (data.code == 0) {
                                if (data.url) {
                                    location.href = data.url;
                                } else {
                                    reloadPage(window);
                                }
                            } else{
                				art.dialog({   
                					content: data.msg,
                					icon: 'warning',  
                					ok: function () {   
                						this.title("操作失败");   
                						return true;   
                					}
                				}); 
                            }
                        },"json");
                    },
                    cancelVal: '关闭',
                    cancel: true
                });
            });

        });
    }
    
    
    if ($('a.js-ajax-dialog-btn').length) {
        Wind.use('artDialog', function () {
            $('.js-ajax-dialog-btn').on('click', function (e) {
                e.preventDefault();
                var $_this = this,
                    $this = $($_this),
                    href = $this.data('href'),
                    msg = $this.data('msg');
                href = href?href:$this.attr('href');
                if(!msg){
                	msg="您确定要进行此操作吗？";
                }
                art.dialog({
                    title: false,
                    icon: 'question',
                    content: msg,
                    follow: $_this,
                    close: function () {
                        $_this.focus();; //关闭时让触发弹窗的元素获取焦点
                        return true;
                    },
                    ok: function () {
                        $.post(href,function(data){
                            if (data.code == 0) {
                                if (data.url) {
                                    location.href = data.url;
                                } else {
                                	art.dialog({   
    									content: data.msg,
    									icon: 'succeed',
    									ok: function () {   
    										reloadPage(window);
    										return true;
    									},
    									
    								}); 
                                }
                            } else if (data.code == -1) {
                                //art.dialog.alert(data.info);
								art.dialog({   
									content: data.msg,
									icon: 'warning',
									ok: function () {   
										this.title(data.msg);   
										return true;   
									}
								}); 
                            }else{
                               art.dialog({   
                                    content: data.msg,
                                    icon: 'warning',
                                    ok: function () {   
                                        this.title(data.msg);   
                                        return true;   
                                    }
                                });  
                            }
                        },"json");
                    },
                    cancelVal: '关闭',
                    cancel: true
                });
            });

        });
    }

    if ($('.js-post-ajax-dialog-btn').length) {
        Wind.use('artDialog', function () {
            $('.js-post-ajax-dialog-btn').on('click', function (e) {
                e.preventDefault();
                var $_this = this,
                    $this = $($_this),
                    href = $this.data('href'),
                    msg = $this.data('msg');
                href = href?href:$this.attr('href');
                if(!msg){
                    msg="您确定要进行此操作吗？";
                }
                art.dialog({
                    title: false,
                    icon: 'question',
                    content: msg,
                    follow: $_this,
                    close: function () {
                        $_this.focus();; //关闭时让触发弹窗的元素获取焦点
                        return true;
                    },
                    ok: function () {
                        $.post(href,function(data){
                            if (data.code == 0) {
                                if (data.url) {
                                    location.href = data.url;
                                } else {
                                    art.dialog({   
                                        content: data.msg,
                                        icon: 'succeed',
                                        ok: function () {   
                                            reloadPage(window);
                                            return true;
                                        },
                                        
                                    }); 
                                }
                            } else if (data.code == -1) {
                                //art.dialog.alert(data.info);
                                art.dialog({   
                                    content: data.msg,
                                    icon: 'warning',
                                    ok: function () {   
                                        this.title(data.msg);   
                                        return true;   
                                    }
                                }); 
                            }else{
                               art.dialog({   
                                    content: data.msg,
                                    icon: 'warning',
                                    ok: function () {   
                                        this.title(data.msg);   
                                        return true;   
                                    }
                                });  
                            }
                        },"json");
                    },
                    cancelVal: '关闭',
                    cancel: true
                });
            });

        });
    }

    if ($('.ajax-handle').length) {
        $('.ajax-handle').on('click', function (e) {
            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                href = href?href:$this.attr('href');
            $.getJSON(href).done(function (data) {
                if (data.code == 0) {
                    if (data.url) {
                        location.href = data.url;
                    }else if (data.data) {
                        location.href = data.data;
                    } else {
                        art.dialog({   
                            content: data.msg,
                            icon: 'succeed',
                            ok: function () {   
                                reloadPage(window);
                                return true;
                            },
                            
                        }); 
                    }
                }else{
                   art.dialog({   
                        content: data.msg,
                        icon: 'warning',
                        ok: function () {   
                            this.title(data.msg);   
                            return true;   
                        }
                    });  
                }
            });
        });
    }

    /*复选框全选(支持多个，纵横双控全选)。
     *实例：版块编辑-权限相关（双控），验证机制-验证策略（单控）
     *说明：
     *	"js-check"的"data-xid"对应其左侧"js-check-all"的"data-checklist"；
     *	"js-check"的"data-yid"对应其上方"js-check-all"的"data-checklist"；
     *	全选框的"data-direction"代表其控制的全选方向(x或y)；
     *	"js-check-wrap"同一块全选操作区域的父标签class，多个调用考虑
     */

    if ($('.js-check-wrap').length) {
        var total_check_all = $('input.js-check-all');

        //遍历所有全选框
        $.each(total_check_all, function () {
            var check_all = $(this),
                check_items;

            //分组各纵横项
            var check_all_direction = check_all.data('direction');
            check_items = $('input.js-check[data-' + check_all_direction + 'id="' + check_all.data('checklist') + '"]');
            
            //点击全选框
            check_all.change(function (e) {
                var check_wrap = check_all.parents('.js-check-wrap'); //当前操作区域所有复选框的父标签（重用考虑）

                if ($(this).attr('checked')) {
                    //全选状态
                    check_items.attr('checked', true);

                    //所有项都被选中
                    if (check_wrap.find('input.js-check').length === check_wrap.find('input.js-check:checked').length) {
                        check_wrap.find(total_check_all).attr('checked', true);
                    }

                } else {
                    //非全选状态
                    check_items.removeAttr('checked');
                    
                    check_wrap.find(total_check_all).removeAttr('checked');

                    //另一方向的全选框取消全选状态
                    var direction_invert = check_all_direction === 'x' ? 'y' : 'x';
                    check_wrap.find($('input.js-check-all[data-direction="' + direction_invert + '"]')).removeAttr('checked');
                }

            });

            //点击非全选时判断是否全部勾选
            check_items.change(function () {

                if ($(this).attr('checked')) {

                    if (check_items.filter(':checked').length === check_items.length) {
                        //已选择和未选择的复选框数相等
                        check_all.attr('checked', true);
                    }

                } else {
                    check_all.removeAttr('checked');
                }

            });


        });

    }

    //日期选择器
    var dateInput = $("input.js-date")
    if (dateInput.length) {
        Wind.use('datePicker', function () {
            dateInput.datePicker();
        });
    }

    //日期+时间选择器
    var dateTimeInput = $("input.js-datetime");
    if (dateTimeInput.length) {
        Wind.use('datePicker', function () {
            dateTimeInput.datePicker({
                time: true
            });
        });
    }
    var yearInput = $("input.js-year");
    if (yearInput.length) {
        Wind.use('datePicker', function () {
            yearInput.datePicker({
                startView: 'decade',
                minView: 'decade',
                format: 'yyyy',
                autoclose: true
            });
        });
    }

    //tab
    var tabs_nav = $('ul.js-tabs-nav');
    if (tabs_nav.length) {
        Wind.use('tabs', function () {
            tabs_nav.tabs('.js-tabs-content > div');
        });
    }
    $(".layer-edit").click(function(){
        var url =$(this).data("url"),title=$(this).data("title")==undefined?"内容修改":$(this).data("title");
        layer.open({
          type: 2,
          title: title,
          shadeClose: true,
          shade: 0.8,
          area: ['100%', '100%'],
          content: url
        });
    });
})();
//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

/**
 * 页面跳转
 * @param url
 */
function redirect(url) {
    location.href = url;
}

/**
 * 读取cookie
 * @param name
 * @returns
 */
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1, c.length);
		}
		if (c.indexOf(nameEQ) == 0) {
			return c.substring(nameEQ.length, c.length);
		}
	}
	

	return null;
}

// 设置cookie
function setCookie(name, value, days) {
    var argc = setCookie.arguments.length;
    var argv = setCookie.arguments;
    var secure = (argc > 5) ? argv[5] : false;
    var expire = new Date();
    if(days==null || days==0) days=1;
    expire.setTime(expire.getTime() + 3600*1000*24*days);
    document.cookie = name + "=" + escape(value) + ("; path=/") + ((secure == true) ? "; secure" : "") + ";expires="+expire.toGMTString();
}

/**
 * 打开iframe式的窗口对话框
 * @param url
 * @param title
 * @param options
 */
function open_iframe_dialog(url, title, options) {
	var params = {
		title : title,
		lock : true,
		opacity : 0,
		width : "95%",
		height:'90%'
	};
	params = options ? $.extend(params, options) : params;
	Wind.use('artDialog', 'iframeTools', function() {
		art.dialog.open(url, params);
	});
}

/**
 * 打开地图对话框
 * 
 * @param url
 * @param title
 * @param options
 * @param callback
 */
function open_map_dialog(url, title, options, callback) {

	var params = {
		title : title,
		lock : true,
		opacity : 0,
		width : "95%",
		height : 400,
		ok : function() {
			if (callback) {
				var d = this.iframe.contentWindow;
				var lng = $("#lng_input", d.document).val();
				var lat = $("#lat_input", d.document).val();
				var address = {};
				address.address = $("#address_input", d.document).val();
				address.province = $("#province_input", d.document).val();
				address.city = $("#city_input", d.document).val();
				address.district = $("#district_input", d.document).val();
				callback.apply(this, [ lng, lat, address ]);
			}
		}
	};
	params = options ? $.extend(params, options) : params;
	Wind.use('artDialog', 'iframeTools', function() {
		art.dialog.open(url, params);
	});
}

/**
 * 打开文件上传对话框
 * @param dialog_title 对话框标题
 * @param callback 回调方法，参数有（当前dialog对象，选择的文件数组，你设置的extra_params）
 * @param extra_params 额外参数，object
 * @param multi 是否可以多选
 * @param filetype 文件类型，image,video,audio,file
 * @param app  应用名，对于 CMF 的应用名
 */
function open_upload_dialog(dialog_title,callback,extra_params,multi,filetype,app){
	multi = multi?1:0;
	filetype = filetype?filetype:'image';
	app = app?app:GV.APP;
    var params = '&multi='+multi+'&filetype='+filetype+'&app='+app ;
    Wind.use("artDialog","iframeTools",function(){
        art.dialog.open(GV.ROOT+'index.php?g=asset&m=asset&a=plupload'  + params, {
	        title: dialog_title,
	        id: new Date().getTime(),
	        width: '650px',
	        height: '420px',
	        lock: true,
	        fixed: true,
	        background:"#CCCCCC",
	        opacity:0,
	        ok: function() {
	            if (typeof callback =='function') {
	            	var iframewindow = this.iframe.contentWindow;
	            	var files=iframewindow.get_selected_files();
	            	if(files){
	            		callback.apply(this, [this, files,extra_params]);
	            	}else{
	            		return false;
	            	}
	            	
	            }
	        },
	        cancel: true
	    });
    });
}

function upload_one(dialog_title,input_selector,filetype,extra_params,app){
	open_upload_dialog(dialog_title,function(dialog,files){
		$(input_selector).val(files[0].filepath);
	},extra_params,0,filetype,app);
}

function upload_one_image(dialog_title,input_selector,extra_params,app){
	open_upload_dialog(dialog_title,function(dialog,files){
		$(input_selector).val(files[0].filepath);
		$(input_selector+'-preview').attr('src',files[0].preview_url);
		$(input_selector+'-name').val(files[0].name);
	},extra_params,0,'image',app);
}

/**
 * 多图上传
 * @param dialog_title 上传对话框标题
 * @param container_selector 图片容器
 * @param item_tpl_wrapper_id 单个图片html模板容器id
 */
function upload_multi_image(dialog_title,container_selector,item_tpl_wrapper_id,extra_params,app){
	open_upload_dialog(dialog_title,function(dialog,files){
		var tpl=$('#'+item_tpl_wrapper_id).html();
		var html='';
		$.each(files,function(i,item){
			var itemtpl= tpl;
			itemtpl=itemtpl.replace(/\{id\}/g,item.id);
			itemtpl=itemtpl.replace(/\{url\}/g,item.url);
			itemtpl=itemtpl.replace(/\{preview_url\}/g,item.preview_url);
			itemtpl=itemtpl.replace(/\{filepath\}/g,item.filepath);
			itemtpl=itemtpl.replace(/\{name\}/g,item.name);
			html+=itemtpl;
		});
		$(container_selector).append(html);
		
	},extra_params,1,'image',app);
}

/**
 * 查看图片对话框
 * @param img 图片地址
 */
function image_preview_dialog(img) {
    Wind.use("artDialog",function(){
        art.dialog({
            title: '图片查看',
            fixed: true,
            width:"420px",
            height: '420px',
            id:"image_preview_"+img,
            lock: true,
            background:"#CCCCCC",
            opacity:0,
            content: '<img src="' + img + '" />'
        });
    });
}

function artdialog_alert(msg){
	Wind.use("artDialog", function() {
		art.dialog({
			id : new Date().getTime(),
			icon : "error",
			fixed : true,
			lock : true,
			background : "#CCCCCC",
			opacity : 0,
			content : msg,
			ok : function() {
				return true;
			}
		});
	});
	
}

function open_iframe_layer(url,title,options){

    var params = {
        type: 2,
        title: title,
        shadeClose: true,
        skin: 'layui-layer-nobg',
        shade: [0.5, '#000000'],
        area: ['90%', '90%'],
        content:url
    };
    params = options ? $.extend(params, options) : params;

    Wind.css('layer');

    Wind.use("layer", function() {
        layer.open(params);
    });

}