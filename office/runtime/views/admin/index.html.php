<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>便捷办公后台</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" href="/static/public/images/logoa.png">
    <link rel="stylesheet" href="/static/admin/index/css/font.css">
    <link rel="stylesheet" href="/static/admin/index/css/xadmin.css">
    <link rel="stylesheet" href="/static/public/layui/css/layui.css">
    <!-- <link rel="stylesheet" href="./css/theme5.css"> -->
    <script src="/static/public/js/jquery.min.js" charset="utf-8"></script>
    <script src="/static/public/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/index/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/admin/index/js/vue.min.js"></script>

    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        // 是否开启刷新记忆tab功能
        //var is_remember = false;
    </script>
</head>
<body class="index">
<!-- 顶部开始 -->
<div class="container">
    <div class="logo">
        <a href="javascript:;" onclick="location.reload();" style="width: 158px;">后台管理</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;" style="width: 70px;">待处理<span id="allHandle"></span></a>
            <dl class="layui-nav-child" style="width: 160px;">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.add_tab('会议室预约','<?php echo $apply_url?>?status=0')">
                        <i class="iconfont">&#xe6f3;</i>会议室预约<span id="apply"></span></a></dd>
                <dd>
                    <a onclick="xadmin.add_tab('休假申请处理','<?php echo $vacation_url?>?status=0')">
                        <i class="iconfont">&#xe6f3;</i>休假申请<span id="vacation"></span></a></dd>
                <dd>
                    <a onclick="xadmin.add_tab('办公用品申请处理','<?php echo $goodsRecord_url?>'+'?status=0')">
                        <i class="iconfont">&#xe6f3;</i>办公用品申请<span id="supply"></span></a></dd>
            </dl>
        </li>
    </ul>
    <!--时钟-->
    <div id="clock" style="display: inline-block">
        <span class="date">{{ date }}</span>
        <span class="time">{{ time }}</span>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item to-index">
            <a href="/home/index/index"><i class="iconfont">&#xe6da;</i>前台</a></li>
        <li class="layui-nav-item">
            <a href="javascript:;"><img src="<?php echo $loginUser['headImg']?>" id="headImg" class="layui-nav-img"><span
                    id="username"><?php echo $loginUser['username']?></span></a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.add_tab('个人信息','/home/index/userInfo')">个人信息</a></dd>
                <dd>
                    <a onclick="xadmin.add_tab('修改密码','/home/index/editPwdView')">修改密码</a></dd>
                <dd>
                    <a id="logout">退出登录</a></dd>
            </dl>
        </li>

    </ul>
</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="公共管理">&#xe6b8;</i>
                    <cite>公共管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('部门管理','<?php echo $department_url?>')">
                            <i class="iconfont">&#xe6a9;</i>
                            <cite>部门管理</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('岗位管理','<?php echo $position_url?>')">
                            <i class="iconfont">&#xe6c7;</i>
                            <cite>岗位管理</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('用户管理','<?php echo $user_url?>')">
                            <i class="iconfont">&#xe70b;</i>
                            <cite>用户管理</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('角色管理','<?php echo $role_url?>')">
                            <i class="iconfont">&#xe753;</i>
                            <cite>角色管理</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('权限管理','<?php echo $permission_url?>')">
                            <i class="iconfont">&#xe82b;</i>
                            <cite>权限管理</cite></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="会议室管理">&#xe820;</i>
                    <cite>会议室管理</cite>

                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('会议室','<?php echo $entity_url?>')">
                            <i class="iconfont">&#xe6b4;</i>
                            <cite>会议室</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('会议室预约','<?php echo $apply_url?>')">
                            <i class="iconfont">&#xe6f3;</i>
                            <cite>会议室预约</cite></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="休假管理">&#xe723;</i>
                    <cite>休假管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('休假申请','<?php echo $vacation_url?>')">
                            <i class="iconfont">&#xe828;</i>
                            <cite>休假申请</cite></a>
                    </li>
                    <li>
                        <a onclick="xadmin.add_tab('休假类型管理','<?php echo $vacationType_url?>')">
                            <i class="iconfont">&#xe6ae;</i>
                            <cite>休假类型管理</cite></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="办公用品管理">&#xe723;</i>
                    <cite>办公用品管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a onclick="xadmin.add_tab('仓库','<?php echo $storehouse_url?>')">
                            <i class="iconfont">&#xe722;</i>
                            <cite>仓库</cite>
                            <i class="iconfont nav_right">&#xe697;</i></a>
                        <ul class="sub-menu">
                            <li>
                                <a onclick="xadmin.add_tab('物品统计','<?php echo $storehouse_url?>')">
                                    <i class="iconfont">&#xe74e;</i>
                                    <cite>物品统计</cite></a>
                            </li>
                            <li>
                                <a onclick="xadmin.add_tab('入库记录','<?php echo $storehouse_url?>')">
                                    <i class="iconfont">&#xe6b9;</i>
                                    <cite>入库记录</cite></a>
                            </li>
                            <li>
                                <a onclick="xadmin.add_tab('出库记录','<?php echo $storehouse_url?>')">
                                    <i class="iconfont">&#xe6fe;</i>
                                    <cite>出库记录</cite></a>
                            </li>

                            <li>
                                <a onclick="xadmin.add_tab('物品分类','<?php echo $goodsCategory_url?>')">
                                    <i class="iconfont">&#xe699;</i>
                                    <cite>物品分类</cite></a>
                            </li>
                        </ul>

                    </li>

                    <li>
                        <a onclick="xadmin.add_tab('申领记录','<?php echo $goodsRecord_url?>')">
                            <i class="iconfont">&#xe6f3;</i>
                            <cite>申领记录(出库)</cite></a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title" title="双击刷新">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>我的桌面
            </li>
        </ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="refresh">刷新当前</dd>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd>
            </dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='/admin/index/welcome' frameborder="0" scrolling="yes" class="x-iframe"
                        id="iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<div class="page-content-bg"></div>
<style id="theme_style"></style>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<script>

    //获取后台传过来的数据
    var API_URL = '<?php echo $API_URL?>'; //统一资源路径

    function addTab(tabName, url) {
        xadmin.add_tab(tabName, url);
    }

    $(function () {
        //获取前端session信息
        var welcome = window.sessionStorage.getItem('welcome');
        if (!welcome) {
            //第一次登录,提示欢迎
            window.sessionStorage.setItem("welcome", 'true'); //设置已欢迎提示,下次刷新不提示
            layui.use('layer', function () {
                layui.layer.msg('欢迎回来~!'); //欢迎提示
            })

        }

        //退出登录
        $('#logout').on('click', function () {
            location.href = '/admin/identify/logout';
        })

        getNeedToHandle();
        // 每隔1分钟刷新数据
        setInterval(getNeedToHandle, 100 * 1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题
    });


    //获取待处理的任务
    function getNeedToHandle() {
        //获取用户信息
        $.ajax({
            url: API_URL + 'getNeedToHandle',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    var handle = data.data;
                    var all = handle.applyCount + handle.vacationCount + handle.supplyCount;
                    //初始化待处理信息提示数量
                    if (all) {
                        $('#allHandle').text(all);
                        $('#allHandle').addClass("layui-badge");
                        if (handle.applyCount) {
                            $('#apply').text(handle.applyCount);
                            $('#apply').addClass("layui-badge");
                        }
                        if (handle.vacationCount) {
                            $('#vacation').text(handle.vacationCount);
                            $('#vacation').addClass("layui-badge");
                        }
                        if (handle.supplyCount) {
                            $('#supply').text(handle.supplyCount);
                            $('#supply').addClass("layui-badge");
                        }
                    }


                } else {
                    layer.msg(data.message, {
                        anim: 6
                    })
                }
            }
        })
    }
    var clock = new Vue({
        el: '#clock',
        data: {
            time: '',
            date: ''
        }
    });

    var week = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
    var timerID = setInterval(updateTime, 1000);
    updateTime();

    function updateTime() {
        var cd = new Date();
        clock.time = zeroPadding(cd.getHours(), 2) + ':' + zeroPadding(cd.getMinutes(), 2) + ':' + zeroPadding(cd.getSeconds(), 2);
        clock.date = zeroPadding(cd.getFullYear(), 4) + '-' + zeroPadding(cd.getMonth() + 1, 2) + '-' + zeroPadding(cd.getDate(), 2) + ' ' + week[cd.getDay()];
    };

    function zeroPadding(num, digit) {
        var zero = '';
        for (var i = 0; i < digit; i++) {
            zero += '0';
        }
        return (zero + num).slice(-digit);
    }

</script>
</body>

</html>