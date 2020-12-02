<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>登录</title>
    <link type="text/css" rel="stylesheet" href="/static/admin/identify/css/login.css"/>
    <link type="text/css" rel="stylesheet" href="/static/public/layui/css/layui.css"/>
    <script type="text/javascript" src="/static/public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/public/js/jsencrypt.min.js"></script>
    <script type="text/javascript" src="/static/public/js/jquery.cookie.js"></script>

</head>

<body>
<div class="main">
    <div class="main0">
        <div class="main_left">
            <div class="theimg"></div>
            <div class="secimg"></div>
            <div class="firimg"></div>
        </div>
        <div class="main_right">
            <div class="main_r_up">
                <div class="user"></div>
                <div class="pp">登录</div>
            </div>
            <div class="sub"><p>还没有账号？<a href="<?php echo $register_url?>"><span class="blue">立即注册</span></a></p></div>

            <form class="layui-form layui-form-pane" lay-filter="loginForm">

                <div class="txt">
                    <span style="float:left;letter-spacing:8px;">手机号:</span>
                    <input name="phone" type="text" class="txtphone" placeholder="请输入手机号" lay-verify="phone"
                           autocomplete="off"/>
                </div>
                <div class="txt">
                    <span style="letter-spacing:18px;">密码:</span>
                    <!--后端传过来的公钥-->
                    <input id="public_key" type="hidden" value="<?php echo $public_key?>">
                    <input name="password" type="password" class="txtphone" placeholder="请输入登录密码" maxlength="20"/>
                </div>

                <div class="txt">
                    <span style="letter-spacing:8px;">验证码:</span>
                    <input name="captcha" type="text" class="captcha" placeholder="请输入验证码" lay-verify="captcha"
                           autocomplete="off"/>
                    <img src="<?php echo $captcha_url?>" onclick="this.src='<?php echo $captcha_url?>?'+Math.random()"
                         class="captcha" id="captcha"/>
                </div>

                <div class="xieyi">
                    <input id="remember" name="remember" lay-verify="remember" lay-skin="primary" type="checkbox" value="0" title="记住我"/>
                    <a href="<?php echo $forget_url?>">
                        <span class="blue" style=" padding-left:110px; cursor:pointer;">忘记密码?</span></a>
                </div>

                <div class="login" id="submit" lay-submit="" lay-filter="login">登录</div>
            </form>

        </div>
    </div>
</div>

<div class="footer">
    <div class="footer0">
        <div class="footer_l">使用条款 | 隐私保护</div>
        <div class="footer_r">© 2020 盟大塑化科技有限公司</div>

    </div>
</div>
</div>
<script type="text/javascript" src="/static/public/layui/layui.js"></script>
<script src="/static/admin/identify/js/script.js"></script>

<script>
    //后端传来的参数
    var login_url='<?php echo $login_url?>';
    var autoLogin_url='<?php echo $autoLogin_url?>';


    //layui的checkbox选项动态添加数据后，需要初始化容器，否则数据可能显示不出来
    layui.use(['form'], function () {
        var form = layui.form;
        form.render('checkbox');

        //自动登录验证
        autoLogin();



        if ($.cookie('remember') == "true") {
            form.val('loginForm', {
                "phone": $.cookie('phone'), // "name": "value"
                "remember": 1,//记住密码框状态
            });
        }


        $('body').keydown(function () {
            if (event.keyCode == 13) {
                //enter 键值 为13
                $('#submit').click();
            }
            ;

        });

        form.on('submit(login)', function (data) {
            var field = data.field;
            var remember = $('#remember').is(':checked') ? 1 : 0;  //替换选中值：0或1
            var public_key = $('#public_key').val(); //获取公钥

            field['remember'] = remember;
            rememberLogin(field);
            field['phone'] = encryptPublic(field['phone'], public_key); //手机号rsa加密传输
            field['password'] = encryptPublic(field['password'], public_key); //密码rsa加密传输
            $.ajax({
                url: login_url,
                type: 'post',
                dataType: 'json',
                data: field,
                success: function (data) {
                    if (data.code == '000') {
                        layer.msg(data.message);
                        location.href = data.data;
                    } else {
                        layer.msg(data.message, {
                            anim: 6
                        })
                    }
                }
            })
        })
        $(function () {
            layer.tips("看不清？点我", '#captcha', {
                tips: 2,
                time: 5000
            });
        });
        $('#captcha').hover(function () {
            layer.closeAll('tips');
            layer.tips("看不清？点我", '#captcha', {
                tips: 2,
                time: 5000
            });
        }, function () {
            layer.closeAll('tips');
        });
        //自动登录验证
        function autoLogin(){
            $.ajax({
                url: autoLogin_url,
                type: 'post',
                dataType: 'json',
                headers: {'token': $.cookie("token")},
                success: function (data) {
                    if (data.code == '000') {
                        layer.msg(data.message, {
                            icon: 6,
                            time: 1000
                        });
                        location.href = data.data;
                    }
                }
            });
        }
    });


</script>
</body>
</html>
