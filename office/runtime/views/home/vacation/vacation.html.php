<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>休假申请</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8">
    <link rel="stylesheet" href="/static/admin/index/css/font.css">
    <link rel="stylesheet" href="/static/admin/index/css/xadmin.css">
    <link rel="stylesheet" href="/static/vacation/css/process.css">

    <link rel="stylesheet" href="/static/public/layui/css/layui.css" media="all">


    <link rel="stylesheet" type="text/css" href="/static/public/layui/label/css/label.css">

    <!--<script type="text/javascript" src="/static/public/js/jquery.min.js" charset="utf-8"></script>-->
    <script type="text/javascript" src="/static/public/timerange/js/jquery.min.js"></script>

    <script type="text/javascript" src="/static/public/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/index/js/xadmin.js"></script>

    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            padding-top: 15px;
            box-sizing: border-box;
            border: 5px solid #009688;
            border-top: none;
            height: 100%;
            overflow-y: auto;
        }

        label {
            width: 55px;
            display: inline-block;
            text-align: right
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!--休假添加功能：begin-->
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 0px;margin-bottom: 5px;">
                    <legend class="shadow" style="margin-left: calc(50% - 100px)">请假条</legend>
                </fieldset>
                <div style="padding: 10px 15px;background-color: #eeeeee">
                    <form class="layui-form layui-col-space5" id="addForm">

                        <div class="layui-input-inline">
                            <label for="type" class="layui-input-inline">请假类型</label>
                            <div class="layui-input-inline" style="width: 170px">
                                <select id="type" name="type" lay-verify="required" lay-search>
                                    <option value="">请搜索选择请假类型</option>
                                </select>
                            </div>
                            <label for="userNo" class="layui-input-inline">工号</label>
                            <div class="layui-input-inline" style="width: 100px;">
                                <!--隐藏id-->
                                <input type="hidden" id="applicant" name="applicant">

                                <input type="text" name="userNo" id="userNo" placeholder="请输入工号"
                                       autocomplete="off" class="layui-input applicant" lay-verify="required">
                            </div>
                            <label for="username" class="layui-input-inline">姓名</label>
                            <div class="layui-input-inline" style="width: 134px">
                                <input type="text" name="username" id="username" placeholder="请输入姓名"
                                       autocomplete="off" class="layui-input applicant">
                            </div>
                            <label for="department" class="layui-input-inline">部门</label>
                            <div class="layui-input-inline" style="width: 134px">
                                <input type="text" name="department" id="department" placeholder="请输入部门"
                                       autocomplete="off" class="layui-input applicant">
                            </div>
                            <label for="position" class="layui-input-inline">岗位</label>
                            <div class="layui-input-inline" style="width: 134px">
                                <input type="text" name="position" id="position" placeholder="请输入岗位"
                                       autocomplete="off" class="layui-input applicant">
                            </div>
                        </div>

                        <div class="layui-input-inline">
                            <label for="reason" class="layui-input-inline">请假原因</label>
                            <div class="layui-input-inline" style="width: 332px;">
                               <textarea name="reason" id="reason" placeholder="请输入请假原因"
                                         autocomplete="off" class="layui-textarea" lay-verify="required"></textarea>
                            </div>
                            <label for="startDate">请假开始</label>
                            <div class="layui-inline" style="width: 134px">
                                <input type="text" id="startDate" name="start" autocomplete="off"
                                       class="layui-input"
                                       placeholder="请输入日期时间" lay-verify="required"/>
                            </div>
                            <label for="position">请假结束</label>
                            <div class="layui-inline" style="width: 134px">
                                <input type="text" id="endDate" name="end" autocomplete="off"
                                       class="layui-input"
                                       placeholder="请输入日期时间" lay-verify="required"/>
                            </div>

                            <label for="days">请假天数</label>
                            <div class="layui-inline" style="width: 134px">
                                <input type="text" name="days" id="days" disabled
                                       autocomplete="off" class="layui-input">
                            </div>

                            <label for="agent">代理人</label>
                            <div class="layui-inline" style="width: 134px">
                                <!--隐藏id-->
                                <input type="hidden" id="agent" name="agent" value="<?php echo $loginUser['id']?>" disabled>
                                <input type="text" id="agentNoName" placeholder="请输入代理人"
                                       autocomplete="off" class="layui-input"
                                       value="<?php echo $loginUser['userNo']?>--<?php echo $loginUser['username']?>" disabled>
                            </div>

                            <div class="layui-inline layui-show-xs-block">
                                <a class="layui-btn" lay-submit="submit" lay-filter="submit" title="提交"
                                   id="submit">提交</a>
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <a class="layui-btn layui-btn-primary" lay-filter="reset" title="重置"
                                   id="reset"><i class="iconfont">&#xe6aa;</i></a>
                            </div>
                        </div>

                    </form>
                </div>
                <!--休假添加功能：end-->
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <blockquote class="layui-elem-quote layui-text shadow">休假记录</blockquote>

                <div class="layui-card-body">
                    <!--休假申请搜索功能：begin-->

                    <form class="layui-form" id="searchForm">

                        <label for="searchType">请假类型</label>
                        <div class="layui-inline" style="width: 170px">
                            <select id="searchType" name="searchType" lay-verify="" lay-search>
                                <option value="">请搜索选择请假类型</option>
                            </select>
                        </div>

                        <label for="searchStatus">申请状态</label>
                        <div class="layui-inline" style="width: 100px">
                            <select id="searchStatus" name="searchStatus" lay-verify="" lay-search>
                                <option value="-1">全部</option>
                                <option value="0">申请中</option>
                                <option value="1">同意申请</option>
                                <option value="2">拒绝申请</option>
                                <option value="3">取消申请</option>
                            </select>
                        </div>
                        <div class="layui-inline" style="width: 40px">
                            <input class="layui-input" autocomplete="off" id="dayMin">
                        </div>
                        <span class="iconfont">&#xe697;=</span>
                        <label for="dayMax">请假天数</label>
                        <span class="iconfont">&#xe697;</span>
                        <div class="layui-inline" style="width: 40px">
                            <input class="layui-input" autocomplete="off" id="dayMax">
                        </div>


                        <div class="layui-inline" style="width: 130px">
                            <input class="layui-input" autocomplete="off" placeholder="请输入日期" name="searchStart"
                                   id="searchStart">
                        </div>

                        <span class="iconfont">&#xe697;=</span>
                        <div class="layui-inline" style="width: 65px;margin-top: -10px">
                            <input type="checkbox" checked="" name="open" id="timeType" lay-skin="switch"
                                   lay-filter="switchTest"
                                   lay-text="开始|结束">
                        </div>
                        <span class="iconfont">&#xe697;=</span>
                        <div class="layui-inline" style="width: 130px">
                            <input class="layui-input" autocomplete="off" placeholder="请输入日期" name="searchEnd"
                                   id="searchEnd">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <a class="layui-btn" lay-submit="" lay-filter="sreach" title="搜索" id="search"><i
                                    class="layui-icon">&#xe615;</i></a>
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <a class="layui-btn layui-btn-primary" lay-submit="" lay-filter="sreach" title="重置"
                               id="resetSearch"><i
                                    class="iconfont">&#xe6aa;</i></a>
                        </div>

                    </form>
                    <!--休假搜索功能：end-->

                    <!--=========table表格数据：begin=============-->
                    <div>
                        <!--表格-->
                        <div style="width:calc(100% - 215px);float: left;">
                            <table class="layui-hide layui-table layui-form" id="vacation"
                                   lay-filter="vacation"></table>
                        </div>
                        <!--审核进度显示-->
                        <div class="vacation" style="padding-bottom: 5px">
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend class="shadow">申请进度</legend>
                            </fieldset>
                            <form class="layui-form layui-form-pane" action="">

                                <div class="layui-form-item process_body" id="process">
                                    <!--<div class="item">
                                        <div class="name" id="applicant"></div>
                                        <div class="detail">
                                            <p id="createTime"></p>
                                            <p>操作：<span>创建</span></p>
                                        </div>
                                    </div>
                                    <div class="arrow">
                                    <div class="item refuse">结束</div>-->
                                </div>

                            </form>
                        </div>
                    </div>
                    <!--自定义显示-->
                    <script type="text/html" id="typeTpl">
                        {{d.type}}
                    </script>
                    <script type="text/html" id="applicantTpl">
                        {{d.userNo}}--{{d.username}}
                    </script>
                    <script type="text/html" id="statusTpl">
                        {{#  switch(d.status){
                        case '0':
                        }}
                        <span style="color: #7d72f5;">申请中</span>
                        {{#  break;
                        case '1':
                        }}
                        <span style="color: #5FB878;">申请通过</span>
                        {{#  break;
                        case '2':
                        }}
                        <span style="color: #f52e21;">拒绝申请</span>
                        {{#  break;
                        case '3':
                        }}
                        <span style="color: #ff934a;">取消申请</span>
                        {{#  break;
                        case '4':
                        }}
                        <span style="color: #c2c2c2;">申请过期</span>
                        {{#  break;} }}
                    </script>

                    <script type="text/html" id="barDemo">
                        {{#if(d.status=='0'||d.status=='1'){}}
                        <a class="layui-btn layui-btn-xs" lay-event="cancel" title="取消"><i
                                class="layui-icon">&#xe616;</i></a>
                        {{#}}}
                    </script>
                    <!--=========table表格数据：end=============-->

                </div>

            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="/static/public/layui/label/js/label.js"></script>


<script>
    //接收后端传来的参数
    //定义统一资源路径
    var apiUrl = '<?php echo $apiUrl?>';


    //初始化
    $(function () {

        //回车触发搜索
        $(document).keyup(function (event) {
            if (event.keyCode == 13) {
                $("#search").trigger("click");
            }
        });
        //搜索按钮
        $('#search').on("click", function () {
            reloadTable();

        });
        //重置搜索表单按钮
        $("#resetSearch").on("click", function () {
            refresh();//刷新，重置申请条件
        });


        //重置添加表单按钮
        $("#reset").on("click", function () {
            reset();//刷新，重置申请条件
        });

        getType(); //获取请假类型，初始化下拉框

        //setInterval(reloadTable, 60*1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题
    })

    function getLeaveDays(start, end) {
        if (start >= end) {
            layer.msg('时间段有误', {
                anim: 6
            })
            return;
        }
        $.ajax({
            url: apiUrl + 'getLeaveDays',
            type: 'get',
            dataType: 'json',
            data: {'start': start, 'end': end},
            success: function (data) {
                if (data.code == '000') {
                    $('#days').val(data.data);
                } else {
                    layer.msg(data.message, {
                        anim: 6
                    })
                }
            }
        })
    }

    //获取所有休假类型
    function getType() {
        $.ajax({
            url: apiUrl + 'getType',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#type option").not(":first").remove();
                    $("#searchType option").not(":first").remove();
                    $.each(data.data, function (index, value) {
                        $("#type,#searchType").append("<option value='" + value.id + "'>" + value.typeNo + '--' + value.typeName + "</option>"); //为Select追加一个Option(下拉项)
                    });
                    //layui的select选项动态添加数据后，需要初始化容器，否则数据显示不出来
                    layui.use(['form'], function () {
                        layui.form.render('select');
                    });
                } else {
                    layer.msg(data.message, {
                        anim: 6
                    })
                }
            }
        })
    }


    //获取休假记录详细数据
    //获取数据
    function reloadTable() {
        layui.use('table', function () {
            var table = layui.table;
            var index = layer.msg('查询中，请稍等...', {icon: 16, time: false, shade: 0});
            table.reload('vacation', {
                //page: {curr: 2},
                where: {
                    type: $('#searchType').val(),
                    status: $('#searchStatus').val(),
                    dayMin: $('#dayMin').val(),
                    dayMax: $('#dayMax').val(),
                    timeType: $('#timeType').is(':checked') ? 'start' : 'end',
                    start: $("#searchStart").val(),
                    end: $("#searchEnd").val(),
                },//传递参数 keyWord是获取页面中的关键词
            })
            setTimeout(function () {
                layer.close(index)
            }, 500)
        });
    }

    //重置添加申请表单
    function reset() {
        $("input[type=file]").val("");//清空文件输入框
        $(".layui-upload-choose").text("");
        $('#addForm')[0].reset();
    }

    //刷新，重置搜索条件
    function refresh() {
        $('#searchForm')[0].reset();
        reloadTable();
    }

    //添加请假申请人（供子页面iframe调用）
    function addApplicant(user) {
        /*//给表单赋值
        form.val("formTest", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "username": "贤心" // "name": "value"
            ,"sex": "女"
            ,"auth": 3
            ,"check[write]": true
            ,"open": false
            ,"desc": "我爱layui"
        });

        //获取表单区域所有值
        //var data1 = form.val("formTest");*/

        $('#applicant').val(user.id);
        $('#userNo').val(user.userNo);
        $('#username').val(user.username);
        $('#department').val(user.departmentName);
        $('#position').val(user.positionName);
        $('#agent').val(user.id);
        $('#agentNoName').val(user.userNo + '-' + user.username);

    }

    layui.use(['layer', 'table', 'laydate', 'form'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            form = layui.form,
            table = layui.table,
            laydate = layui.laydate;

        /*==================表格数据初始化：begin=================*/
        table.render({
            elem: '#vacation',
            height: 'full-320',
            toolbar: false, //开启头部工具栏，并为其绑定左侧模板
            //cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: apiUrl + 'getHistory',
            cols: [[

                {type: 'numbers', width: 80, title: '序号'},
                //{field: 'id', width: '2%', title: 'ID', sort: true},
                {templet: '#typeTpl', width: 100, title: '请假类型'},
                {templet: '#applicantTpl', width: 120, title: '申请人'},
                {field: 'reason', minWidth: 180, title: '请假原因'},
                {
                    field: 'create_time',
                    width: 138,
                    title: '申请日期',
                    templet: '<span>{{d.create_time.substr(0, 16)}}</span>',
                    sort: true
                },
                {field: 'days', width: 100, title: '请假天数', sort: true},
                {
                    field: 'start',
                    width: 138,
                    title: '开始时间',
                    templet: '<span>{{d.start.substr(0, 16)}}</span>',
                    sort: true
                },
                {
                    field: 'end',
                    width: 138,
                    title: '结束时间',
                    templet: '<span>{{d.end.substr(0, 16)}}</span>',
                    sort: true
                },
                {field: 'agent', width: 120, title: '代理人'},

                {field: 'status', width: 100, title: '状态', templet: '#statusTpl', unresize: true, sort: true},
                {fixed: 'right', width: 90, title: '操作', align: 'center', toolbar: '#barDemo'}
            ]],
            page: true,
            where: {
                type: '',
                applicant: '',
                status: $('#searchStatus').val(),
                dayMin: '',
                dayMax: '',
                timeType: '',
                start: '',
                end: '',
            },//传递参数 keyWord是获取页面中的关键词

        });

        //添加审核进度项目
        function addProcessItem(name, time, checkStatus, refuseReason = null) {
            var item = $('<div class="item trends"></div>');
            var name = $('<div class="name">' + name + '</div>');
            var detail = $('<div class="detail"></div>');
            var p1 = $('<p></p>');
            var p2 = $('<p>操作：</p>');
            var p3 = $('');
            var arrow = $('<div class="arrow trends"></div>');
            var operateSpan = '';
            var end = $('');
            p1.text(time ? time.substr(0, 16) : '');
            switch (checkStatus) {
                case '0':
                    operateSpan = $('<span class="doing">审核中</span>');
                    break;
                case '1':
                    operateSpan = $('<span class="pass">同意</span>');
                    p3 = $('<p>反馈：<span class="pass">同意</span></p>');
                    break;
                case '2':
                    operateSpan = $('<span class="refuse">拒绝</span>');
                    p3 = $('<p>反馈：<span class="refuse">' + refuseReason + '</span></p>');
                    end = $('<div class="item refuse trends">结束</div>');
                    break;
                case '3':
                    operateSpan = $('<span class="cancel">取消</span>');
                    end = $('<div class="item cancel trends">结束</div>')
                    break;
                case '4':
                    operateSpan = $('<span>创建</span>');
                    break;
            }
            p2.append(operateSpan);
            detail.append(p1);
            detail.append(p2);
            detail.append(p3);
            item.append(name);
            item.append(detail);
            $('#process').append(item);
            $('#process').append(arrow);
            $('#process').append(end);
        }

        //监听行单击事件
        table.on('row(vacation)', function (obj) {
            var data = obj.data;
            $('.layui-table tr').css("background-color", "");//取消颜色
            $(this).css("background-color", "#e2e2e2");//添加行颜色
            $('#reason').val(data.reason);
            //先移除动态添加的item
            $('#process').find("div").remove(".trends");

            //创建第一个item
            addProcessItem(data.username, data.create_time, '4', data.status);
            if (data.status == '3') {
                //已经取消申请
                addProcessItem(data.username, data.update_time, '3', data.status);
                return;
            }
            //创建第二个item(直接上级审核)
            addProcessItem(data.superiorName, data.superiorCheckTime, data.superiorCheckStatus, data.refuseReason);
            //创建第三个item(部门负责人审核)
            if (data.superiorCheckTime && data.superiorCheckStatus == '1') {
                //直接上级审核时间不为空,已审核,并且拒绝理由为空
                addProcessItem(data.departmentHeadName, data.departmentHeadCheckTime, data.departmentHeadCheckStatus, data.refuseReason);
                //如果已同意,最终添加结束标记(绿色)
                if (data.status == '1') {
                    $('#process').append($('<div class="item pass trends">结束</div>'));
                }
            }
            //判断是否是申请过期
            if(data.status=='4'){
                let end = $('<div class="item overdue trends">申请过期</div>');
                $('.doing').text('未审核');
                $('#process').append(end);
            }
        });
        /*=======================表格数据初始化：end======================*/

        //弹出选择申请人视图
        $('.applicant').on('click', function () {
            layer.open({
                type: 2,
                title: '添加申请人',
                shadeClose: true,
                shade: 0.2,
                area: ['800px', '80%'],
                //offset: 't',
                content: apiUrl + 'user' //iframe的url
            });
        })


        /*============添加表单监听：begin==============*/


        //提交，添加申请
        form.on('submit(submit)', function (data) {
            var field = data.field;//添加表单字段
            console.log(field);

            //ajax提交，添加预约
            $.ajax({
                url: apiUrl + 'addSubmit',
                type: 'post',
                dataType: 'json',
                data: field,
                success: function (data) {
                    if (data.code == '000') {
                        refresh();
                        layer.msg(data.message);
                    } else {
                        layer.msg(data.message, {
                            anim: 6
                        })
                    }
                },
                error: function (data) {
                    layer.msg('登录过期！', {
                        anim: 6
                    }, function () {
                        //location.href = data.responseText;
                    })
                }
            })

            return false; //不刷新页面
        });

        /**
         * 申请状态操作
         * ids 操作的id
         * action 操作类型
         * reason 操作理由
         * */
        function editStatus(id, action, refuseReason = '') {
            $.ajax({
                url: apiUrl + 'editStatus',
                type: 'post',
                dataType: 'json',
                data: {id: id, action: action, refuseReason: refuseReason},
                success: function (data) {
                    if (data.code == '000') {
                        console.log(data.message);
                        layer.msg(data.message, {
                            time: 2000,
                            closeBtn: true,
                        }, function () {
                            //layer.closeAll('tips');
                            refresh();
                        });

                    } else {
                        layer.msg(data.message, {
                            time: 2000,
                            anim: 6
                        }, function () {
                            refresh();
                        })
                        //layer.closeAll('tips');
                    }
                },
                error: function (data) {
                    layer.msg(data.responseText, {
                        anim: 6
                    });
                }
            })
        }

        //表格行右侧按钮
        table.on('tool(vacation)', function (obj) {
            var data = obj.data;
            console.log(data);
            switch (obj.event) {
                case 'cancel':
                    var rowIndex = obj.tr['0'].rowIndex + 1; //行号
                    layer.msg('是否取消申请？' + rowIndex, {
                        time: 5000,
                        closeBtn: true,
                        btn: ['确认', '取消'],
                        btn1: function (index) {
                            editStatus(data.id, 'cancel');
                            layer.close(index);
                        }
                    })
                    //xadmin.open('编辑', apiUrl + 'edit?id=' + data.id, 460, 500)
                    break;
            }
        });


        /*============添加表单监听：end==============*/

        //执行一个laydate实例

        //添加申请
        layDateInit('#startDate');
        layDateInit('#endDate', true);

        //搜索
        layDateInit2('#searchStart');
        layDateInit2('#searchEnd');

        function layDateInit(selector, flag = false) {
            laydate.render({
                elem: selector, //指定元素
                type: 'datetime',
                format: 'yyyy-MM-dd HH:mm',
                min: getDateTime('datetime'),
                done: function (date) { //监听日期被切换
                    if (flag) {
                        var start = $('#startDate').val();
                        getLeaveDays(start, date);
                    }
                }
            });
        }

        function layDateInit2(selector) {
            laydate.render({
                elem: selector, //指定元素
                type: 'datetime',
                format: 'yyyy-MM-dd HH:mm',
            });
        }

    });

    function getDateTime(type) {
        var date = new Date;
        var year = date.getFullYear();//获取当前年
        var month = date.getMonth() + 1;//获取当前月
        var day = date.getDate();//获取当前日
        var h = date.getHours();//获取当前小时数(0-23)
        var m = date.getMinutes();//获取当前分钟数(0-59)
        var s = date.getSeconds();//获取当前秒
        if (type == 'date') {
            return year + '-' + month + '-' + day;
        } else if (type == 'time') {
            return h + ':' + m + ':' + s;
        } else {
            return year + '-' + month + '-' + day + ' ' + h + ':' + m + ':' + s;
        }

    }


</script>
</body>
</html>