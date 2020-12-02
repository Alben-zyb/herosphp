<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
    <link rel="stylesheet" href="/static/admin/index/css/font.css">
    <link rel="stylesheet" href="/static/admin/index/css/xadmin.css">
    <script type="text/javascript" src="/static/public/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/index/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/public/js/jquery.min.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <!--用户id-->
                <input type="hidden" id="id" name="id">
                <label class="layui-form-label" style="width: 100px;">工号:</label>
                <div class="layui-input-inline" style="width: 120px;">
                    <input class="layui-input" type="text" id="userNo" name="userNo" disabled="disabled">
                </div>
                <label class="layui-form-label" style="width: 100px;">姓名:</label>
                <div class="layui-input-inline" style="width: 140px;">
                    <input class="layui-input" type="text" id="username" name="username" disabled="disabled"
                           title="hello">
                </div>

            </div>
            <div class="layui-form-item layui-form-text">
                <table class="layui-table layui-input-block">
                    <tbody>
                    <th>
                        <input class="layui-inline-block" type="checkbox" lay-skin="primary" lay-filter="father"
                               title="角色列表">
                    </th>
                    <tr>
                        <td>
                            <div class="layui-input-block" id="roleList">
                                <!--<span title="hello">
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="超级管理员">
                                </span>-->
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="update">更新</button>
            </div>
        </form>
    </div>
</div>
<script>
    //接收后端传来的参数
    var API_URL = '<?php echo $API_URL?>';//统一资源路径

    var id = <?php echo $id?> + '';//用户id
    var userNo = '<?php echo $userNo?>';//用户工号
    var username = '<?php echo $username?>';//用户名

    init();

    //初始化角色数据
    function init() {
        $('#id').val(id);
        $('#userNo').val(userNo);
        $('#username').val(username);
        getRole();//获取角色列表
    }

    //获取所有角色
    function getRole() {
        $.ajax({
            url: API_URL + 'getRole',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $('#roleList span').remove();//清空角色列表
                    $.each(data.data, function (index, value) {
                        var span = $('<span title="' + value.detail + '"></span>');
                        var input = $('<input name="roleIds" lay-skin="primary" type="checkbox" value="' + value.id + '" title="' + value.roleName + '">');
                        span.append(input);
                        $("#roleList").append(span); //添加checkbox到div=role
                    });

                    //获取用户拥有的角色，并设置选中已有的角色
                    $.ajax({
                        url: API_URL + 'getUserRole',
                        type: 'post',
                        dataType: 'json',
                        data: {id: id},
                        success: function (data) {
                            //console.log(data.data);
                            if (data.code == '000') {
                                var roles = data.data;
                                $.each(roles, function (index, role) {
                                    $("#roleList input[name='roleIds'][value='"+role['id']+"']").attr('checked', 'checked');
                                    if(role['status']==0){
                                        var i=$('<i class="iconfont" title="禁用">&#xe82b;</i>');
                                        $("#roleList input[name='roleIds'][value='"+role['id']+"']").parent().append(i);
                                    }
                                });
                                //layui的checkbox选项动态添加数据后，需要初始化容器，否则数据可能显示不出来
                                layui.use(['form'], function () {
                                    layui.form.render('checkbox');
                                });
                            } else {
                                layer.msg(data.message, {
                                    anim: 6
                                })
                            }
                        }
                    })

                } else {
                    layer.msg(data.message, {
                        anim: 6
                    })
                }
            }
        })
    }


    //表单监听
    layui.use(['form', 'layer'], function () {
        $ = layui.jquery;
        var form = layui.form
            , layer = layui.layer;


        //监听提交
        form.on('submit(update)', function (data) {
            var roleData = data.field; //表单字段
            var checkList = []; //存放选中的角色的id

            //循环遍历已选中的角色
            $("#roleList input[name='roleIds']:checked").each(function () {
                checkList.push($(this).val())
            })
            roleData['roleIds'] = checkList; //将表单中name=roleIds的字段赋值为数组(id数组)
            //发异步，把数据提交给php
            $.ajax({
                url: API_URL + 'updateUserRole',
                type: 'post',
                dataType: 'json',
                data: roleData,
                success: function (data) {
                    console.log(data.message);
                    if (data.code == '000') {
                        layer.msg("更新成功",
                            {
                                icon: 6,
                                time: 2000,
                                closeBtn: true,
                            }, function () {
                                // 获得frame索引
                                //var index = parent.layer.getFrameIndex(window.name);
                                //关闭当前frame
                                //parent.layer.close(index);
                                //parent.refresh();
                            });

                    } else {
                        layer.msg(data.message, {
                            anim: 6
                        })
                    }
                },
                error: function (data) {
                    layer.msg(data.responseText, {
                        anim: 6
                    });
                }
            })
            return false;
        });


        //角色全选控制
        form.on('checkbox(father)', function (data) {
            if (data.elem.checked) {
                $('#roleList').find('input').prop("checked", true);
                form.render();
            } else {
                $('#roleList').find('input').prop("checked", false);
                form.render();
            }
        });


    });
</script>
</body>

</html>