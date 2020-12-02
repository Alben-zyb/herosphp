<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>协同办公系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" href="/static/public/images/logoa.png">
    <link type="text/css" rel="stylesheet" href="/static/admin/index/css/font.css">
    <link type="text/css" rel="stylesheet" href="/static/public/layui/css/layui.css" media="all">
    <link type="text/css" rel="stylesheet" href="/static/home/css/index.css"/>
    <script type="text/javascript" src="/static/public/js/jquery.min.js"></script>


</head>
<body class="bg">
<div class="container">
    <div class="cover"></div>
    <div class="layui-row nav">
        <div class="layui-col-lg9 layui-col-md12 layui-col-sm12 title">
            <fieldset class="layui-elem-field layui-field-title" style="margin: 0px;">
                <legend style="margin-left: 50px"><a onclick="location.reload()" style="cursor: pointer">
                    <h1 style="color: #393D49">欢迎来到盟大协同办公系统</h1></a>
                </legend>
            </fieldset>
        </div>
        <div class="layui-col-lg3 layui-col-md12 layui-col-sm12">
            <ul class="layui-nav right" lay-filter="demo">
                <!--根据是否是管理员显示后台按钮-->
                <?php if ( $loginUser['isAdmin'] ) { ?>
                <li class="layui-nav-item">
                    <a href="javascript:;" id="admin"><i class="layui-icon layui-icon-home"></i>后台</a>
                </li>
                <?php } ?>
                <li class="layui-nav-item" lay-unselect="">
                    <a href="javascript:;"><img src="<?php echo $loginUser['headImg']?>" id="headImg" class="layui-nav-img"><span><?php echo $loginUser['username']?></span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" id="userInfo">个人中心</a></dd>
                        <dd><a href="javascript:;" id="editPwd">修改密码</a></dd>
                        <dd><a href="javascript:;" id="logout">退出登录</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>


    <div class="layui-row main">
        <!--休假申请：begin-->
        <div class="layui-col-md3 layui-col-md-offset1">
            <div class="choose" id="vacation">
                <div class="content layui-bg-green">休假申请</div>
            </div>
        </div>
        <!--休假申请：end-->


        <!--会议室预约：begin-->
        <div class="layui-col-md4">
            <div class="choose" id="roomApply">
                <div class="content layui-bg-blue">会议室预约</div>
            </div>
        </div>
        <!--会议室预约：end-->


        <!--办公用品申领：begin-->
        <div class="layui-col-md3">
            <div class="choose" id="supply">
                <div class="content layui-bg-orange">办公用品申领</div>
            </div>
        </div>
        <!--办公用品申领：end-->

    </div>
    <!--个人中心：begin-->
    <div class="userInfo">
        <div style="padding: 10px;">
            <!--收起按钮：begin-->
            <div style="float: left;width: 10%">
                <span id="close" onclick="userInfoClose()" class="layui-icon" title="收起">&#xe66b;</span>
            </div>
            <!--收起按钮：end-->

            <div style="float: left;width: 90%">
                <fieldset class="layui-elem-field layui-field-title" style="margin: 0px;">
                    <legend style="margin-left: 26px">
                        <a style="line-height:1.6em;margin-top:3px;float:right;cursor: pointer"
                           onclick="userInfoRefresh();" title="刷新"><h3 style="color: #393D49;" id="center">个人中心</h3></a>
                    </legend>
                </fieldset>
            </div>
        </div>
        <iframe src='' frameborder="0" scrolling="yes" id="userInfo-iframe"
                style="width: 100%;height: calc(100% - 40px);overflow: hidden;"></iframe>
    </div>
    <!--个人中心：end-->
    <!--隐藏划出：begin-->
    <div class="service">
        <div class="btn">
            <button onclick="setSrc('vacation')" type="button" class="layui-btn">休假申请</button>
            <button onclick="setSrc('roomApply')" type="button" class="layui-btn layui-btn-normal">会议室预约</button>
            <button onclick="setSrc('supply')" type="button" class="layui-btn layui-btn-warm">办公用品申领</button>
            <div class="close"><i class="layui-icon layui-icon-close-fill" title="关闭"></i></div>
            <div class="refresh"><i class="layui-icon layui-icon-refresh" title="刷新"></i></div>
        </div>
        <div id="bar" class="layui-bg-blue"
             style="width: 100%;height: 5px;border-top-right-radius: 5px;border-top-left-radius: 5px"></div>

        <iframe src='' frameborder="0" scrolling="yes" class="iframe-show" id="service-iframe"></iframe>
    </div>
    <!--隐藏划出：end-->
</div>


<div class="footer">

    <div class="footer0">
        <div class="footer_l">使用条款 | 隐私保护</div>
        <div class="footer_r">© 2020 盟大塑化科技有限公司</div>
    </div>
    <!--底部上拉显示-->
    <div style="width:30px;height: 30px;position: relative;bottom: 50px;margin: 0 auto;transform:rotate(-90deg) translate(0px,-30px);">
        <i id="open" class="layui-icon layui-icon-next" style="font-size: 30px;color: #1E9FFF;cursor: pointer"></i>
    </div>
</div>

<script type="text/javascript" src="/static/public/layui/layui.js"></script>

<script>
    //接收后台数据
    var apiUrl = '<?php echo $apiUrl?>';
    var superior = '<?php echo $loginUser["superior"]?>';
    var departmentHead = '<?php echo $loginUser["departmentHead"]?>';
    //悬浮板块动画效果
    $('.choose').hover(function () {
        $(this).css('padding-top', '0px'); //内边距设置为10px
    }, function () {
        $(this).css('padding-top', '15px');
    })

    //设置iframe的src
    function setSrc(val) {
        var src = '';
        var bar = '';
        switch (val) {
            case 'vacation':
                src = '/home/vacation/index';
                bar = 'layui-bg-green';
                break;
            case 'roomApply':
                src = '/home/room/index';
                bar = 'layui-bg-blue';
                break;
            case 'supply':
                src = '/home/supply/index';
                bar = 'layui-bg-orange';
                break;
        }
        $('#bar').removeClass();
        $('#bar').addClass(bar);
        $('#service-iframe').attr('src', src); //设置iframe路径
    }


    //底部上拉
    $('#open').on('click', function () {
        $('.service').css('top', '100px'); //设置顶部距离为100px
    })
    //刷新iframe按钮
    $('.refresh').on('click', function () {
        document.getElementById('service-iframe').contentWindow.location.reload(true);
    })
    //关闭iframe按钮
    $('.close').on('click', function () {
        $('.service').css('top', '100%'); //设置顶部距离为100%(隐藏)
    })
    //点击遮罩层,关闭userInfo
    $('.cover').on('click', function () {
        userInfoClose();
        $('.cover').css('display', 'none');
    })

    //个人中心关闭
    function userInfoClose() {
        var width = $('.userInfo').width();
        $('.userInfo').css('right', -width - 3); //设置右部距离为容器宽度(隐藏)
    }

    //个人中心打开
    function userInfoOpen() {
        $('#center').html('个人中心');
        $('#userInfo-iframe').attr('src', apiUrl + 'userInfo'); //设置iframe路径
        $('.userInfo').css('right', '0'); //设置右部距离为-30%(隐藏)
        $('.cover').css('display', 'block');
    }


    //个人中心刷新
    function userInfoRefresh() {
        document.getElementById('userInfo-iframe').contentWindow.location.reload(true);
    }

    function editPwdOpen() {
        $('#center').html('密码修改');
        $('#userInfo-iframe').attr('src', apiUrl + 'editPwdView'); //设置iframe路径
        $('.userInfo').css('right', '0'); //设置右部距离为-30%(隐藏)
    }

    //导航的hover效果、二级菜单等功能，需要依赖element模块，不写这一步，导航条显示不出来
    layui.use(['element', 'layer'], function () {
        var element = layui.element;
        //监听导航点击
        element.on('nav(demo)', function (elem) {
            //console.log(elem.attr('id'))
            var id = elem.attr('id');
            switch (id) {
                case 'admin':
                    location.href = '/admin/index/index';
                    break;
                case 'userInfo':
                    userInfoOpen();
                    break;
                case 'editPwd':
                    editPwdOpen();
                    break;
                case 'logout':
                    location.href = '/admin/identify/logout';
                    break;
            }

        });
    });

    //刷新数据
    refresh(superior,departmentHead);

    function refresh(superior,departmentHead) {
        if (superior == '0' || departmentHead == '0') {
            userInfoOpen();
            layui.use('layer', function () {
                layui.layer.msg('请先完善信息');
            });
            $('.choose,#open').unbind('click').click(function () {//新的绑定事件
                layui.use('layer', function () {
                    layui.layer.msg('请先完善信息', {
                        closeBtn: true,
                        time: 3000,
                        anim: 6,
                    });
                });
                userInfoOpen();
            });
        }else {
            //点击板块弹出iframe
            $('.choose').unbind('click').click(function () {
                var id = $(this).attr('id');
                setSrc(id); //设置iframe的src
                $('.service').css('top', '100px'); //设置顶部距离为100px
            })
        }
    }
</script>

</body>
</html>