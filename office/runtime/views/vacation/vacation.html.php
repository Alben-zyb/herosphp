<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
    <link rel="stylesheet" href="/static/admin/index/css/font.css">
    <link rel="stylesheet" href="/static/admin/index/css/xadmin.css">
    <link rel="stylesheet" href="/static/vacation/css/process.css">
    <script src="/static/public/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/index/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/public/js/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
      <a href="">首页</a>
      <a href="">休假管理</a>
      <a><cite>休假申请</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">

                <!--休假申请搜索功能：begin-->
                <div class="" style="padding: 10px 15px;">
                    <form class="layui-form layui-col-space5" id="searchForm">

                        <label for="searchType">请假类型</label>
                        <div class="layui-inline" style="width: 170px">
                            <select id="searchType" name="searchType" lay-verify="" lay-search>
                                <option value="">请搜索选择请假类型</option>
                            </select>
                        </div>
                        <label for="searchApplicant">申请人</label>
                        <div class="layui-inline">
                            <input type="text" name="searchApplicant" id="searchApplicant" placeholder="请输入申请人"
                                   autocomplete="off" class="layui-input">
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
                            <input class="layui-input" autocomplete="off" placeholder="请输入日期" name="start" id="start">
                        </div>

                        <span class="iconfont">&#xe697;=</span>
                        <div class="layui-inline" style="width: 65px">
                            <input type="checkbox" checked="" name="open" id="timeType" lay-skin="switch"
                                   lay-filter="switchTest"
                                   lay-text="开始|结束">
                        </div>
                        <span class="iconfont">&#xe697;=</span>
                        <div class="layui-inline" style="width: 130px">
                            <input class="layui-input" autocomplete="off" placeholder="请输入日期" name="end"
                                   id="end">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <a class="layui-btn" lay-submit="" lay-filter="sreach" title="搜索" id="search"><i
                                    class="layui-icon">&#xe615;</i></a>
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <a class="layui-btn layui-btn-primary" lay-submit="" lay-filter="sreach" title="重置"
                               id="reset"><i
                                    class="iconfont">&#xe6aa;</i></a>
                        </div>

                    </form>
                </div>
                <!--休假搜索功能：end-->
                <!--表格数据：begin-->
                <script type="text/html" id="toolbarDemo">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="delete">批量删除<i
                                class="layui-icon">&#xe640;</i></button>
                    </div>
                </script>
                <div>
                    <!--表格-->
                    <div style="width:calc(100% - 220px);float: left;">
                        <table class="layui-hide layui-table layui-form" id="test" lay-filter="test"
                               style="float: left"></table>
                        <!--自定义显示-->
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
                            <span style="color: #5FB878;">同意申请</span>
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
                        <script type="text/html" id="myCheckTpl">
                            {{#  switch(d.myCheck){
                            case '0':
                            }}
                            <span style="color: #7d72f5;">未审核</span>
                            {{#  break;
                            case '1':
                            }}
                            <span style="color: #5FB878;">已同意</span>
                            {{#  break;
                            case '2':
                            }}
                            <span style="color: #f52e21;">拒绝</span>

                            {{#  break;} }}
                        </script>

                        <script type="text/html" id="barDemo">
                            {{#  switch(d.status){
                            case '0':
                            }}
                            <a class="layui-btn layui-btn-xs" lay-event="operate" title="操作"><i class="layui-icon">&#xe631;</i></a>
                            {{#  break;
                            case '1':
                            }}
                            <a class="layui-btn layui-btn-xs" lay-event="refuse" title="拒绝"><i
                                    class="layui-icon">&#xe631;</i></a>
                            {{#  break;
                            default:
                            break;} }}
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" title="删除"><i
                                    class="layui-icon">&#xe640;</i></a>
                        </script>
                    </div>
                    <!--表格数据：begin-->
                    <!--审核进度显示-->
                    <div class="vacation">
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend class="shadow">请假清单补充信息</legend>
                        </fieldset>
                        <form class="layui-form layui-form-pane" action="">
                            <div class="layui-form-item layui-form-text shadow">
                                <label class="layui-form-label" style="font-size: 18px">请假原因</label>
                                <div class="layui-input-block">
                                    <textarea class="layui-textarea" id="reason"
                                              style="font-size: 16px;color: #01AAED"></textarea>
                                </div>
                            </div>
                            <blockquote class="layui-elem-quote layui-text shadow">申请进度</blockquote>

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


            </div>
        </div>
    </div>
</div>
<script src="/static/public/layui/import.js"></script>

<script>
    //接收后端传来的参数
    //定义统一资源路径
    var apiUrl = '<?php echo $apiUrl?>';
    var status = '<?php echo $status?>';
    if (status) {
        $('#searchStatus').val(status);
    }

    //setInterval(getData, 60*1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题


    //初始化
    getType(); //获取休假类型

    /*===================================显示数据：begin==========================*/


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
                    $("#searchType option").not(":first").remove();
                    $.each(data.data, function (index, value) {
                        $("#searchType").append("<option value='" + value.id + "'>" + value.typeNo + '--' + value.typeName + "</option>"); //为Select追加一个Option(下拉项)
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



    layui.use(['layer', 'table', 'tablePlug', 'laydate', 'form'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            table = layui.table,
            form = layui.form,
            laydate = layui.laydate,
            tablePlug = layui.tablePlug;
        tablePlug.smartReload.enable(true);//处理不闪动的关键代码

        /*==================表格数据初始化：begin=================*/
        table.render({
            elem: '#test',
            height: 'full-200',
            toolbar: '#toolbarDemo', //开启头部工具栏，并为其绑定左侧模板
            //cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: apiUrl + 'getData',
            smartReloadModel: true, //处理不闪动的关键代码
            cols: [[

                {type: 'checkbox', width: 80},
                {type: 'numbers', width: 80, title: '序号'},
                //{field: 'id', width: '2%', title: 'ID', sort: true},
                {field: 'type', width: 100, title: '请假类型'},
                {field: 'applicant', templet: '#applicantTpl', width: 130, title: '申请人'},
                {field: 'agent', width: 130, title: '代理人'},

                {field: 'create_time', width: 140, title: '申请日期', sort: true},
                {field: 'days', width: 100, title: '请假天数', sort: true},
                {field: 'start', width: 138, title: '开始时间', sort: true,templet:function (d) {
                        return d.start.substr(0,16);
                    }},
                {field: 'end', width: 138, title: '结束时间', sort: true,templet:function (d) {
                        return d.start.substr(0,16);
                    }},
                {field: 'status', minWidth: 100, title: '状态', templet: '#statusTpl', unresize: true},
                {field: 'myCheck', width: 100, title: '我的审核', templet: '#myCheckTpl', unresize: true},
                {field: 'reason', width: 200, title: '请假原因', hide: true},
                {field: 'result', width: 200, title: '结果反馈', hide: true},
                {field: 'update_time', width: 138, title: '更新时间', sort: true, hide: true,},
                {fixed: 'right', width: 130, title: '操作', align: 'right', toolbar: '#barDemo'}
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
            done: function (res, curr, count) {
                //$('.layui-table-cell').css('padding','0 3px');
                //$('.layui-table-cell').css('line-height','50px');
            }

        });

        //window.setInterval(getData, 5*1000);  //每5秒重载数据

        //监听搜索按钮、Enter（回车）
        $(function () {
            //回车触发
            $(document).keyup(function (event) {
                if (event.keyCode == 13) {
                    $("#search").trigger("click");
                }
            });
            //搜索按钮
            $('#search').on("click", function () {
                layer.msg('查询中，请稍等...', {icon: 16, time: 500, shade: 0});
                getData();

            });
            //重置按钮
            $("#reset").on("click", function () {
                layer.msg('加载中...', {icon: 16, time: 500, shade: 0});
                refresh();//刷新，重置搜索条件
            });

        });

        //刷新，重置搜索条件
        function refresh() {
            $("input[type=file]").val("");//清空文件输入框
            $(".layui-upload-choose").text("");
            $('#searchForm')[0].reset();
            getData();
        }
        //获取数据
        function getData() {

            table.reload('test', {
                //page: {curr: 2},
                where: {
                    type: $('#searchType').val(),
                    applicant: $("#searchApplicant").val(),
                    status: $('#searchStatus').val(),
                    dayMin: $('#dayMin').val(),
                    dayMax: $('#dayMax').val(),
                    timeType: $('#timeType').is(':checked') ? 'start' : 'end',
                    start: $("#start").val(),
                    end: $("#end").val(),
                },//传递参数 keyWord是获取页面中的关键词
            })

        }

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
        table.on('row(test)', function (obj) {
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
            if (data.status == '4') {
                let end = $('<div class="item overdue trends">申请过期</div>');
                $('.doing').text('未审核');
                $('#process').append(end);
            }

            //obj.del(); //删除当前行
            //obj.update(fields) //修改当前行数据
            //createSpaceTable(obj.data.ENTERPRISE_NAME,obj.data.UUID);
        });
        /*=======================表格数据初始化：end======================*/


        //根据id删除数据
        function deleteByIds(ids, obj = '') {
            $.ajax({
                url: apiUrl + 'delete',
                type: 'post',
                dataType: 'json',
                data: {ids: ids},
                success: function (data) {
                    if (data.code == '000') {
                        if (obj) {
                            obj.del();
                        } else {
                            $(".layui-form-checked").not('.header').parents('tr').remove(); //移除表格行
                        }
                        layer.msg(data.message, {
                            time: 3000,
                            closeBtn: true
                        });
                    } else {
                        layer.msg(data.message, {
                            time: 5000,
                            closeBtn: true,
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
        }

        //头工具栏事件
        table.on('toolbar(test)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'add':
                    xadmin.open('添加休假', apiUrl + 'add', 460, 400);
                    break;
                case 'delete':
                    var data = checkStatus.data;
                    var deleteIds = new Array();
                    data.forEach((item, index) => {
                        deleteIds[index] = item.id;
                    })
                    if (data.length == 0) {
                        layer.msg('未选中数据！', {
                            time: 5000,
                            closeBtn: true,
                            anim: 6
                        });
                    } else {
                        layer.msg('确定删除数据吗(' + data.length + '行)？', {
                            //icon: 1,
                            time: 5000,
                            closeBtn: true,
                            btn: ['yes', 'no'],
                            yes: function (index) {
                                //do something
                                layer.close(index);
                                deleteByIds(deleteIds);
                            }
                        });
                    }
                    break;
            }
        });

        /**
         * 申请状态操作
         * ids 操作的id
         * action 操作类型
         * reason 操作理由
         * */
        function editStatus(ids, action, refuseReason = '') {
            $.ajax({
                url: apiUrl + 'editStatus',
                type: 'post',
                dataType: 'json',
                data: {ids: ids, action: action, refuseReason: refuseReason},
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

        //监听行工具事件
        table.on('tool(test)', function (obj) {
            var data = obj.data;
            switch (obj.event) {
                case 'operate':
                    layer.msg('是否通过申请?', {
                        time: 5000,
                        closeBtn: true,
                        btn: ['通过', '拒绝', '取消'],
                        btn1: function (index) {
                            //通过
                            editStatus([data.id], 'pass');
                            layer.close(index);
                        }, btn2: function (index) {
                            //拒绝
                            layer.prompt({
                                formType: 2,
                                title: '<span style="color: red">拒绝理由</span>',
                                area: ['300px', '150px']
                            }, function (value, index) {
                                console.log(value); //得到value
                                editStatus([data.id], 'refuse', value);
                                layer.close(index);
                            });
                            layer.close(index);
                        }
                    })
                    //xadmin.open('编辑', apiUrl + 'edit?id=' + data.id, 460, 500)
                    break;
                case 'refuse':
                    var rowIndex = obj.tr['0'].rowIndex + 1; //行号
                    layer.msg('是否拒绝申请？' + rowIndex, {
                        time: 5000,
                        closeBtn: true,
                        btn: ['确认', '取消'],
                        btn1: function (index) {
                            layer.prompt({
                                formType: 2,
                                title: '<span style="color: red">拒绝理由</span>',
                                area: ['300px', '150px']
                            }, function (value, index) {
                                console.log(value); //得到value
                                editStatus([data.id], 'refuse', value);
                                layer.close(index);
                            });
                            layer.close(index);
                        }
                    })
                    //xadmin.open('编辑', apiUrl + 'edit?id=' + data.id, 460, 500)
                    break;
                case 'del':
                    var rowIndex = obj.tr['0'].rowIndex + 1; //行号
                    layer.msg('确定删除吗？' + rowIndex, {
                        time: 5000,
                        closeBtn: true,
                        btn: ['yes', 'no'],
                        yes: function (index, layero) {
                            //do something
                            deleteByIds([data.id], obj);
                            layer.close(index);
                        }
                    });
                    break;


            }
        });


        //执行一个laydate实例
        laydate.render({
            elem: '#start', //指定元素
            type: 'datetime',
            format: 'yyyy-MM-dd HH:mm',
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end', //指定元素
            type: 'datetime',
            format: 'yyyy-MM-dd HH:mm',
        });

    });

    /*显示数据：end*/
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
        } else {
            return h + ':' + m + ':' + s;
        }

    }

</script>

</body>
</html>