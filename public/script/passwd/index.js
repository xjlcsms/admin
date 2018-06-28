//切换
(function(){
    var $module = $('.mod-form');
    var $panel = $module.find('.group');
    $panel.eq(0).addClass('active').siblings().removeClass('active');
})();

//找回密码-表单校验
(function(){
    var $module = $('.mod-form');
    var $form = $('form[name="find"]');
    var $username = $form.find('input[name="mobile"]'),
        $password = $form.find('input[name="password"]'),
        $code = $form.find('input[name="code"]'),
        $getcode = $form.find('.getcode'),
        $captcha = $form.find('input[name="captcha"]');
    var _reg = {
        mobilephone: /^(\+?0{0,2}86([\ |\-])?)?1[3|4|5|7|8][0-9]{9}$/,
        code: /^\d{4}$/,
        captcha: /^\w{4}$/
    };
    //表单验证器
    var validator = {
        'username': function(callback) { //手机号验证
            callback = callback || function() {};
            var value = $.trim($username.val());
            var placeholder = $username.attr('placeholder');
            if(value==placeholder){
                value = '';
            }
            var data = {
                'obj': $username,
                'value': value,
                'status': true,
                'msg': ''
            };
            if (!value) {
                data['status'] = false;
                data['msg'] = '手机号不能为空';
            } else if (!_reg.mobilephone.test(value)) {
                data['status'] = false;
                data['msg'] = '手机号格式输入错误';
            }
            callback(data);
        },
        'password': function(callback) { //密码验证
            callback = callback || function() {};
            var value = $.trim($password.val());
            var placeholder = $password.attr('placeholder');
            if(value==placeholder){
                value = '';
            }
            var data = {
                'obj': $password,
                'value': value,
                'status': true,
                'msg': ''
            };
            if (!value) {
                data['status'] = false;
                data['msg'] = '密码不能为空';
            } else if (value.length < 6) {
                data['status'] = false;
                data['msg'] = '密码不能少于6位';
            }
            callback(data);
        },
        'captcha': function(callback) { //验证码验证
            callback = callback || function() {};
            var value = $.trim($captcha.val());
            var placeholder = $captcha.attr('placeholder');
            if(value==placeholder){
                value = '';
            }
            var data = {
                'obj': $captcha,
                'value': value,
                'status': true,
                'msg': ''
            };
            if (!value) {
                data['status'] = false;
                data['msg'] = '验证码不能为空';
            } else if (!_reg['captcha'].test(value)) {
                data['status'] = false;
                data['msg'] = '验证码格式输入错误';
            }
            callback(data);
        },
        'code': function(callback) {
            callback = callback || function() {};
            var value = $.trim($code.val());
            var placeholder = $code.attr('placeholder');
            if(value==placeholder){
                value = '';
            }
            var data = {
                'obj': $code,
                'value': value,
                'status': true,
                'msg': ''
            };
            if (!value) {
                data['status'] = false;
                data['msg'] = '请输入短信验证码';
            } else if (!_reg['code'].test(value)) {
                data['status'] = false;
                data['msg'] = '请输入正确的短信验证码';
            }
            callback(data);
        }
    };
    //表单验证输出
    var checkFileds = function(fields, callback) {
        callback = callback || function() {};
        var num = 0;
        var length = fields.length;
        var status = true;
        var msg = '';
        for (var i = 0; i < length; i++) {
            if (validator[fields[i]]) {
                validator[fields[i]](function(data) {
                    if (!data['status']) {
                        status = false;
                        data['obj'].siblings('.error').remove();
						data['obj'].after('<label class="error">'+data.msg+'</label>');
						setTimeout(function(){
							data['obj'].siblings('.error').addClass('is-visible');
						});
                    }
                    num++;
                    if (num == length) {
                        if(status){
                            callback();
                        }
                    }
                });
            }
        }
    };
    /*** 事件绑定 ***/
    //切换验证码
    $form.on('click', '.captcha img', function() {
        this.src = '/i/captcha/?channel=login&v=' + (+new Date);
    });
    //发送短信
    (function() {
        var time = 0, 
            hander = null;
        //倒计时
        var countdown = function() {
            hander && clearInterval(hander);
            hander = setInterval(function() {
                if (time) {
                    time--;
                    $getcode.text(time + '秒后重发');
                } else {
                    clearInterval(hander);
                    $username.prop('disabled', false);
                    $getcode.prop('disabled', false).text('重新发送')
                }
            }, 1000);
            $code.focus(); //解锁验证输入框
            $username.prop('disabled', true);
            $getcode.prop('disabled', true).text(time + '秒后重发');
        };
        //初始化
        var from = +getCookie('form_time'),
            phonenumber = getCookie('form_user');
        if (phonenumber && from && $username) {
            var to = (+new Date());
            time = 60 - parseInt((to - from) / 1000);
            $username.val(phonenumber);
            countdown();
        }
        //获取手机验证码;
        $form.find('.getcode').click(function() {
            if (!time) {
                checkFileds(['username', 'captcha'], function() {
                    var number = $.trim($username.val());
                    var captcha = $.trim($captcha.val());
                    $.post('/sender/sms/', {
                        channel: 'resetpassword ',
                        mobile: number,
                        captcha: captcha
                    }, function(json) {
                        if (json.status) {
                            addCookie('form_user', number, 60);
                            addCookie('form_time', (+new Date), 60);
                            time = 60;
                            countdown();
                        } else {
                            $form.find('.captcha img').attr('src','/i/captcha/?channel=sms&v=' + (+new Date));
                        	var $tip = $module.find('.tip').html('<p class="text-danger bg-danger">'+json['msg']+'</p>').fadeIn();
                            setTimeout(function(){
                                $tip.fadeOut();
                            },2500);
                        }
                    });
                });
            };
            return false;
        });
    })();
    //表单提交
    $form.submit(function(){
        var fields = ['username','code','password'];
        checkFileds(fields, function() {
            var param = $form.serialize();
            if (param.indexOf('mobile') < 0) { //当状态为disabled不提交
                param += '&mobile=' + $username.val();
            }
            $.post('/passwd/find/',param,function(json){
                if (json['status']) {//登陆成功
                    delCookie('form_time');
                    delCookie('form_user');
                    window.location.href = '/';
                } else {
                    $form.find('.captcha img').attr('src','/i/captcha/?channel=sms&v=' + (+new Date));
                	var $tip = $module.find('.tip').html('<p class="text-danger bg-danger">'+json['msg']+'</p>').fadeIn();
                    setTimeout(function(){
                        $tip.fadeOut();
                    },2500);
                }
            },'json');
        });
        return false;
    });
    /*** 私有方法 ***/
    //添加cookie
    function addCookie(objName, objValue, objseconds, objDomain, objPath) {
        var str = objName + "=" + escape(objValue);
        if (objseconds > 0) { //为时不设定过期时间，浏览器关闭时cookie自动消失
            var date = new Date();
            var ms = objseconds * 1000;
            date.setTime(date.getTime() + ms);
            str += "; expires=" + date.toGMTString();
            if (objDomain) {
                str += ";domain=" + objDomain;
            }
            if (objPath) {
                str += ";path=" + objPath;
            }
        }
        document.cookie = str;
    }
    //获取指定名称的cookie的值  
    function getCookie(objName) {
        var arrStr = document.cookie.split("; ");
        for (var i = 0; i < arrStr.length; i++) {
            var temp = arrStr[i].split("=");
            if (temp[0] == objName) return unescape(temp[1]);
        }
    }
    //为了删除指定名称的cookie，可以将其过期时间设定为一个过去的时间
    function delCookie(name){
        var date = new Date();
        date.setTime(date.getTime() - 10000);
        document.cookie = name + "=a; expires=" + date.toGMTString();
    }
})();