//动态调整页面高度，全屏显示图片
$(document).ready(function () {
    var height = $(document).height();
    $('.main').css('height', height);
})

/**
 *
 * @param {文本值} value
 * @param {公钥} publicKey
 */
function encryptPublic(value, publicKey) {
    // 使用公钥加密，default_key_size可为512，1024，2048等
    let encrypt = new JSEncrypt();
    encrypt.setPublicKey(publicKey);
    let encrypted = encrypt.encrypt(value);
    return encrypted;
}

/**
 * 使用jQuery.cookie.js记住登录状态
 * @param data
 */
function rememberLogin(data) {
    if (data['remember']) {
        $.cookie("remember", "true", {expires: 7}); //存储一个带7天期限的cookie
        $.cookie("phone", data['phone'], {expires: 7});

    } else {
        $.cookie("remember", "false", {expires: 7}); //设置cookie过期
        $.cookie("phone", data['phone'], {expires: -1});
    }
}

/*验证码倒计时*/
var clock = '';
var nums = 10;
var btn;

//获取验证码
function getPhoneCode(thisBtn) {
    $('#captcha').focus();
    if (!phoneCheck()) {
        return;
    }
    btn = thisBtn;
    nums = 60; //初始化倒计时时间
    btn.disabled = true; //将按钮置为不可点击
    btn.style = 'cursor: not-allowed';
    btn.value = '重新获取（' + nums + '）';

    sendCode(); //发送验证码
}

//通知后端发送验证码
function sendCode() {
    var public_key = $('#public_key').val(); //获取公钥
    var phone = $('#phone').val();
    phone = encryptPublic(phone, public_key); //手机号rsa加密传输
    $.ajax({
        url: phoneCode_url,
        type: 'post',
        data: {'phone': phone},
        dataType: 'json',
        success: function (data) {
            if (data.code == '000') {
                //验证码发送成功,倒计时(验证码失效剩余时间)
                clock = setInterval(doLoop, 1000); //一秒执行一次
                layer.msg(data.message, {
                    icon: 6,
                    time: 1000
                })
            } else {
                cleanClock();
                layer.msg(data.message, {
                    icon: 5,
                    anim: 6
                })
            }
        }
    })
}

function doLoop() {
    nums--;
    if (nums > 0) {
        btn.value = '重新获取（' + nums + '）';
    } else {
        cleanClock();
    }
}

function cleanClock() {
    clearInterval(clock); //清除js定时器
    $('#getCaptcha').attr('disabled', false);
    $('#getCaptcha').attr('style', 'cursor:pointer;');
    $('#getCaptcha').attr('value', '重新获取');
}

//手机号验证
function phoneCheck() {
    reg = /^1[3|4|5|8][0-9]\d{4,8}$/i;//验证手机正则(输入前7位至11位)
    if (reg.test($("#phone").val())) {
        $("#phone").css('border-color', '#5FB878');
        return true;
    } else {
        $("#phone").attr('placeholder', '手机号码不正确');
        $("#phone").css('border-color', 'red');
        $("#phone").focus();
        return false;
    }
}