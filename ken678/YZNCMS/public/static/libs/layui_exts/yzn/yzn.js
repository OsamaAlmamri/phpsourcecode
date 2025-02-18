/**
 @ Name：简单封下基本操作
 */
layui.define(['layer','notice'], function(exports) {
    var layer = layui.layer,
        notice = layui.notice;

    var MOD_NAME = 'yzn';
    layer.config({
        skin: 'layui-layer-yzn'
    });

    yzn = {
        config: {
            shade: [0.02, '#000'],
        },
        open: function(title, url, width, height, isResize) {
            isResize = isResize === undefined ? true : isResize;
            var index = layer.open({
                title: title,
                type: 2,
                area: [width, height],
                content: url,
                maxmin: true,
                moveOut: true,
                success: function(layero, index) {
                    var body = layer.getChildFrame('body', index);
                    if (body.length > 0) {
                        $.each(body, function(i, v) {

                            // todo 优化弹出层背景色修改
                            $(v).before('<style>\n' +
                                'html, body {\n' +
                                '    background: #ffffff;\n' +
                                '}\n' +
                                '</style>');
                        });
                    }
                }
            });
            if (yzn.checkMobile() || width === undefined || height === undefined) {
                layer.full(index);
            }
            if (isResize) {
                $(window).on("resize", function() {
                    //layer.full(index);
                    layer.style(index, {
                        top: 0,
                        height: $(window).height()
                    })
                })
            }
        },
        checkMobile: function() {
            var userAgentInfo = navigator.userAgent;
            var mobileAgents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
            var mobile_flag = false;
            //根据userAgent判断是否是手机
            for (var v = 0; v < mobileAgents.length; v++) {
                if (userAgentInfo.indexOf(mobileAgents[v]) > 0) {
                    mobile_flag = true;
                    break;
                }
            }
            var screen_width = window.screen.width;
            var screen_height = window.screen.height;
            //根据屏幕分辨率判断是否是手机
            if (screen_width < 600 && screen_height < 800) {
                mobile_flag = true;
            }
            return mobile_flag;
        },
        formatDateTime: function(timeStamp) {
            var date = new Date();
            date.setTime(timeStamp * 1000);
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            m = m < 10 ? ('0' + m) : m;
            var d = date.getDate();
            d = d < 10 ? ('0' + d) : d;
            var h = date.getHours();
            h = h < 10 ? ('0' + h) : h;
            var minute = date.getMinutes();
            var second = date.getSeconds();
            minute = minute < 10 ? ('0' + minute) : minute;
            second = second < 10 ? ('0' + second) : second;
            return y + '-' + m + '-' + d + ' ' + h + ':' + minute + ':' + second;
        },
        parame: function(param, defaultParam) {
            return param !== undefined ? param : defaultParam;
        },
        request: {
            post: function(option, ok, no, ex) {
                return yzn.request.ajax('post', option, ok, no, ex);
            },
            get: function(option, ok, no, ex) {
                return yzn.request.ajax('get', option, ok, no, ex);
            },
            ajax: function(type, option, ok, no, ex) {
                type = type || 'get';
                option.url = option.url || '';
                option.data = option.data || {};
                option.prefix = option.prefix || false;
                option.statusName = option.statusName || 'code';
                option.statusCode = option.statusCode || 1;
                ok = ok || function(res) {};
                no = no || function(res) {
                    var msg = res.msg == undefined ? '返回数据格式有误' : res.msg;
                    yzn.msg.error(msg);
                    return false;
                };
                ex = ex || function(res) {};
                if (option.url == '') {
                    yzn.msg.error('请求地址不能为空');
                    return false;
                }
                if (option.prefix == true) {
                    //option.url = yzn.url(option.url);
                }
                var index = yzn.msg.loading('加载中');
                $.ajax({
                    url: option.url,
                    type: type,
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    dataType: "json",
                    data: option.data,
                    timeout: 60000,
                    success: function(res) {
                        yzn.msg.close(index);
                        if (eval('res.' + option.statusName) == option.statusCode) {
                            return ok(res);
                        } else {
                            return no(res);
                        }
                    },
                    error: function(xhr, textstatus, thrown) {
                        yzn.msg.error('Status:' + xhr.status + '，' + xhr.statusText + '，请稍后再试！', function() {
                            ex(this);
                        });
                        return false;
                    }
                });
            }
        },
        notice:{
            // 成功消息
            success: function(msg) {
                var index = notice.success(msg);
                return index;
            },
            // 失败消息
            error: function(msg) {
                var index = notice.error(msg);
                return index;
            },
            // 警告消息框
            warning: function(msg) {
                var index = notice.warning(msg);
                return index;
            },
            // 消息提示
            info: function(msg) {
                var index = notice.info(msg);
                return index;
            },
        },
        msg: {
            // 成功消息
            success: function(msg, callback) {
                if (callback === undefined) {
                    callback = function() {}
                }
                var index = layer.msg(msg, { icon: 1, shade: yzn.config.shade, scrollbar: false, time: 2000, shadeClose: true }, callback);
                return index;
            },
            // 失败消息
            error: function(msg, callback) {
                if (callback === undefined) {
                    callback = function() {}
                }
                var index = layer.msg(msg, { icon: 2, shade: yzn.config.shade, scrollbar: false, time: 3000, shadeClose: true }, callback);
                return index;
            },
            // 警告消息框
            alert: function(msg, callback) {
                var index = layer.alert(msg, { end: callback, scrollbar: false });
                return index;
            },
            // 对话框
            confirm: function(msg, ok, no) {
                var index = layer.confirm(msg, { title: '操作确认', btn: ['确认', '取消'] }, function() {
                    typeof ok === 'function' && ok.call(this);
                }, function() {
                    typeof no === 'function' && no.call(this);
                    self.close(index);
                });
                return index;
            },
            // 消息提示
            tips: function(msg, time, callback) {
                var index = layer.msg(msg, { time: (time || 3) * 1000, shade: this.shade, end: callback, shadeClose: true });
                return index;
            },
            // 加载中提示
            loading: function(msg, callback) {
                var index = msg ? layer.msg(msg, { icon: 16, scrollbar: false, shade: this.shade, time: 0, end: callback }) : layer.load(2, { time: 0, scrollbar: false, shade: this.shade, end: callback });
                return index;
            },
            // 关闭消息框
            close: function(index) {
                return layer.close(index);
            }
        },
    }

    exports(MOD_NAME, yzn);
});