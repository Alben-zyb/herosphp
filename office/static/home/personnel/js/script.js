$(function () {

    //输入文本框失去焦点
    $("#userAdd input").blur(function () {
        layer.closeAll('tips');
    });

    //真实姓名栏获得焦点
    $("#relName").focus(function () {
        $("#relName").parent().removeClass("errorC");
        $("#relName").parent().removeClass("checkedN");
        $(".errorRelName").hide();
        layer.tips('长度为2-10个汉字', '#relName', {
            tips: [1, '#3595CC'],
            time: 0
        });
    });
    //真实姓名栏失去焦点
    $("#relName").blur(function () {
        reg = /^[\u2E80-\u9FFF]+$/;//Unicode编码中的汉字范围

        if ($("#relName").val() == "") {
            $("#relName").parent().addClass("errorC");
            $(".errorRelName").html("*请输入用户真实姓名");
            $(".errorRelName").css("display", "block");
        } else if ($("#relName").val().length > 10 || $("#relName").val().length < 2) {
            $("#relName").parent().addClass("errorC");
            $(".errorRelName").html("*真实姓名长度有误！");
            $(".errorRelName").css("display", "block");
        } else if (!reg.test($("#relName").val())) {
            $("#relName").parent().addClass("errorC");
            $(".errorRelName").html("*真实姓名格式有误!!");
            $(".errorRelName").css("display", "block");
        } else {
            $("#relName").parent().addClass("checkedN");
            var value=$(this).val();
            var pinYin=getPinYinByName(value);
            //检查用户名拼音是否已存在，若存在，返回拼音+数字后缀
            $.ajax({
                url: API_URL + 'checkUsername',
                type: 'post',
                dataType: 'json',
                async: false,
                data: {'newUsername':pinYin,'oldUsername':''},
                success: function (data) {
                    //console.log(data);
                    if (data.code == 1) {
                        $('#username').val(data.msg.username);
                    } else {
                        layer.msg('出错！', {
                            time: 3000, //自动关闭
                            icon:7,//图标
                            anim:6,//动画样式
                            //offset: ['50px', '150px'] //位置
                        });
                    }
                }
            })


        }
    });

    //手机号栏获得焦点
    $("#mobile").focus(function () {
        $("#mobile").parent().removeClass("errorC");
        $("#mobile").parent().removeClass("checkedN");
        $(".errorMobile").hide();
        layer.tips('输入11位手机号码，可用于登录和找回密码', '#mobile', {
            tips: [1, '#3595CC'],
            time: 0
        });
    });
    //手机号栏失去焦点
    $("#mobile").blur(function () {
        reg = /^1[3|4|5|8][0-9]\d{4,8}$/i;//验证手机正则(输入前7位至11位)
        if ($("#mobile").val() == "") {
            $("#mobile").parent().addClass("errorC");
            $(".errorMobile").html("*请输入手机号!");
            $(".errorMobile").css("display", "block");
        } else if ($("#mobile").val().length < 11) {
            $("#mobile").parent().addClass("errorC");
            $(".errorMobile").html("*手机号长度有误！");
            $(".errorMobile").css("display", "block");
        } else if (!reg.test($("#mobile").val())) {
            $("#mobile").parent().addClass("errorC");
            $(".errorMobile").html("*逗我呢吧，你确定这是你的手机号!!");
            $(".errorMobile").css("display", "block");
        } else {
            //检查电话号是否已存在
            $.ajax({
                url: API_URL + 'checkMobile',
                type: 'post',
                dataType: 'json',
                async: false,
                data: {'mobile':$("#mobile").val()},
                success: function (data) {
                    console.log(data);
                    if (data.code == 0) {
                        $("#mobile").parent().addClass("checkedN");
                    } else {
                        $("#mobile").parent().addClass("errorC");
                        $(".errorMobile").html("*手机号码已存在！");
                        $(".errorMobile").css("display", "block");
                    }

                }
            })
        }
    });

    //身份证栏获得焦点
    $("#identityCard").focus(function () {
        $("#identityCard").parent().removeClass("errorC");
        $("#identityCard").parent().removeClass("checkedN");
        $(".errorIDCard").hide();
        layer.tips('请输入身份证号码', '#identityCard', {
            tips: [1, '#3595CC'],
            time: 0
        });
    });

    //身份证失去焦点
    $("#identityCard").blur(function () {
        // 身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
        var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        var identityCard = $("#identityCard").val();
        if (identityCard == "") {
            $("#identityCard").parent().addClass("errorC");
            $(".errorIDCard").html("*身份证不能为空!");
            $(".errorIDCard").css("display", "block");
        } else if (!reg.test(identityCard)) {
            $("#identityCard").parent().addClass("errorC");
            $(".errorIDCard").html("*身份证格式错误！");
            $(".errorIDCard").css("display", "block");
        } else {
            $("#identityCard").parent().addClass("checkedN");
            var date = new Date();
            var year = date.getFullYear();
            var birthday_year = parseInt(identityCard.substr(6, 4));
            var age = year - birthday_year;
            $('#age').val(age);

        }
    });
    //邮箱栏获得焦点
    $("#email").focus(function () {
        $("#email").parent().removeClass("errorC");
        $("#email").parent().removeClass("checkedN");
        $(".errorEmail").hide();
        layer.tips('用于找回密码，提高账户安全等级', '#email', {
            tips: [1, '#3595CC'],
            time: 0
        });
    });

    //邮箱栏失去焦点
    $("#email").blur(function () {
        reg = /^\w+[@]\w+((.com)|(.net)|(.cn)|(.org)|(.gmail))$$/;
        if ($("#email").val() == "") {
            $("#email").parent().addClass("errorC");
            $(".errorEmail").html("*邮箱不能为空!");
            $(".errorEmail").css("display", "block");
        } else if (!reg.test($("#email").val())) {
            $("#email").parent().addClass("errorC");
            $(".errorEmail").html("*邮箱格式错误！");
            $(".errorEmail").css("display", "block");
        } else {
            $("#email").parent().addClass("checkedN");
        }
    });



    //邮箱栏键盘操作
    $("#email").keyup(function () {//键盘监听keyup,keydown,keypress
        var emailVal = $("#email").val();
        emailValN = emailVal.replace(/\s/g, '');//去空
        emailValN = emailValN.replace(/[\u4e00-\u9fa5]/g, '');//屏蔽中文
        if (emailValN != emailVal) {
            $("#email").val(emailValN);
        }

        var mailVal = emailValN.split("@");
        var mailHtml = mailVal[0];
        if (mailHtml.length > 15) {
            mailHtml = mailHtml.slice(0, 15) + "...";//字数超加省略
        }

        for (var i = 1; i < 6; i++) {
            var M = $(".item" + i).attr("data-mail");
            $(".item" + i).html(mailHtml + M);
        }
    });
    //邮箱提示
    $(".item").click(function () {
        var v = $(this).html();
        $("#email").val(v);
        $("#email").trigger("focus");//setTimeout($("#email").("focus") );效果同，时间设多少无所谓
    });


    $("#email").click(function () {
        $(".mail").show();
        return false;
    });
    $(document).click(function () {
        $(".mail").hide();
    });

    //密码栏获得焦点(mainform2)
    $(".register_password,.register_password1").focus(function () {
        $(".register_password").parent().removeClass("errorC");
        $(this).parent().removeClass("checkedN");
        $(".error4").hide();
        layer.tips('长度为8-16个字符，区分大小写，至少包含两种类型', '#register_password', {
            tips: [1, '#3595CC'],
            time: 0
        });
    });


    //密码栏失去焦点(mainform2)
    $(".register_password,.register_password1").blur(function () {
        reg1 = /^.*[\d]+.*$/;
        reg2 = /^.*[A-Za-z]+.*$/;
        reg3 = /^.*[_@#%&^+-/*\/\\]+.*$/;//验证密码
        if ($(".pwdBtnShowN").attr("isshow") == "false") {
            var Pval = $(".register_password1").val();
        } else {
            var Pval = $(".register_password").val();
        }

        if (Pval == "") {
            $(".register_password1").parent().addClass("errorC");
            $(".error4").html("请填写密码");
            $(".error4").css("display", "block");
        } else if (Pval.length > 16 || Pval.length < 8) {
            $(".register_password").parent().addClass("errorC");
            $(".error4").html("密码应为8-16个字符，区分大小写");
            $(".error4").css("display", "block");
        } else if (!((reg1.test(Pval) && reg2.test(Pval)) || (reg1.test(Pval) && reg3.test(Pval)) || (reg2.test(Pval) && reg3.test(Pval)))) {
            $(".register_password").parent().addClass("errorC");
            $(".error4").html("需至少包含数字、字母和符号中的两种类型");
            $(".error4").css("display", "block");
        } else {
            $(".register_password").parent().addClass("checkedN");
        }
    });


});

