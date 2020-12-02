var $form;
var form;
var $;
layui.config({
    base: "/static/public/layui/"
}).use(['form', 'layer'], function () {
    form = layui.form;
    $ = layui.jquery;
    $form = $('form');

    //添加验证规则
    form.verify({
        oldPwd: function (value, item) {
            if (value.length < 6) {
                return "密码长度不能小于6位";
            }
        },
        newPwd: function (value, item) {
            if (value.length < 6) {
                return "密码长度不能小于6位";
            }
        },
        confirmPwd: function (value, item) {
            if (!new RegExp($("#oldPwd").val()).test(value)) {
                return "两次输入密码不一致，请重新输入！";
            }
        }
    })


    //修改密码
    form.on('submit(changePwd)', function (data) {
        console.log(data.field);
        var field = data.field;
        var public_key = $('#public_key').val(); //获取公钥

        field['oldPwd'] = encryptPublic(field['oldPwd'], public_key); //密码rsa加密传输
        field['newPwd'] = encryptPublic(field['newPwd'], public_key); //密码rsa加密传输
        $.ajax({
            url: apiUrl + 'editPassword',
            type: 'post',
            dataType: 'json',
            data: field,
            success: function (data) {
                console.log(data.message);
                if (data.code == '000') {
                    layer.msg("修改成功",
                        {
                            icon: 6,
                            time: 2000,
                            closeBtn: true,
                        }, function () {
                        });

                } else {
                    layer.msg(data.message, {
                        time: 2000,
                        anim: 6
                    });
                }
            },
            error: function (data) {
                layer.msg(data.responseText, {
                    anim: 6
                });
            }
        })
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。

    });
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

})
