<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
    <link rel="stylesheet" href="/static/admin/index/css/font.css">
    <link rel="stylesheet" href="/static/admin/index/css/xadmin.css">
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
    <!--<span class="layui-breadcrumb">
      <a href="/admin/index/index">首页</a>
      <a href="">演示</a>
      <a><cite>导航元素</cite></a>
    </span>-->
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">

                <!--岗位搜索功能：begin-->
                <div class="" style="padding: 10px 15px;">
                    <form class="layui-form layui-col-space5" id="searchForm">

                        <label for="tree">部门</label>
                        <!--<a class="layui-btn layui-btn-primary"
                           href="https://wujiawei0926.gitee.io/treeselect/docs/doc.html">查看文档</a>-->
                        <div class="layui-inline">
                            <input type="hidden" id="searchDepartment" name="searchDepartment">
                            <input type="text" id="tree" lay-filter="tree" class="layui-input">
                        </div>

                        <label for="searchPosition">岗位</label>
                        <div class="layui-inline">
                            <input type="hidden" id="searchPositionHidden">
                            <select id="searchPosition" name="searchPosition" lay-verify="" lay-search>
                                <option value="">请选择岗位</option>
                            </select>
                        </div>
                        <label for="searchNoName">工号/姓名</label>
                        <div class="layui-inline">
                            <input type="text" name="searchNoName" id="searchNoName" placeholder="请输入工号或姓名"
                                   autocomplete="off"
                                   class="layui-input">
                        </div>


                        <div class="layui-inline">
                            <input class="layui-input" autocomplete="off" placeholder="请输入开始时间" name="start" id="start">
                        </div>

                        <span class="iconfont">&#xe697;</span>
                        <div class="layui-inline" style="width: 66px;">
                            <input type="checkbox" checked="" name="open" id="timeType" lay-skin="switch"
                                   lay-filter="switchTest"
                                   lay-text="添加|更新">
                        </div>
                        <span class="iconfont">&#xe697;</span>
                        <div class="layui-inline">
                            <input class="layui-input" autocomplete="off" placeholder="请输入截至时间" name="end" id="end">
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
                <!--岗位搜索功能：end-->
                <!--表格数据：begin-->
                <script type="text/html" id="toolbarDemo">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-sm" lay-event="add">添加<i
                                class="layui-icon">&#xe640;</i></button>
                        <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="delete">批量删除<i
                                class="layui-icon">&#xe640;</i></button>
                    </div>
                </script>
                <table class="layui-hide layui-table layui-form" id="test" lay-filter="test"></table>
                <!--自定义显示角色状态，可操作-->
                <script type="text/html" id="headImgTpl">
                    <img id="headImg" src="{{d.headImg}}" height="50px" width="50px">
                </script>
                <script type="text/html" id="switchTpl">
                    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="启用|禁用"
                           lay-filter="statusAction" {{ d.status== 1 ? 'checked' : '' }}>
                </script>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="role" title="角色管理"><i
                            class="layui-icon">&#xe683;</i></a>
                    <a class="layui-btn layui-btn-xs" lay-event="edit" title="编辑"><i class="layui-icon">&#xe642;</i></a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" title="删除"><i
                            class="layui-icon">&#xe640;</i></a>
                </script>
                <!--表格数据：begin-->
            </div>
        </div>
    </div>
</div>
<script src="/static/public/layui/import.js"></script>
<script>
    //接收后端传来的参数

    var apiUrl = '<?php echo $apiUrl?>';//定义统一资源路径

    var positionId = '<?php echo $positionId?>';//岗位id

    //初始化选中
    if (positionId) {
        $("#searchPositionHidden").val(positionId);//设置初始查询参数
    }
    //setInterval(getData, 60*1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题


    /*显示数据：begin*/
    function getData() {
        layui.use('table', function () {
            var table = layui.table;
            var index = layer.msg('查询中，请稍等...', {icon: 16, time: false, shade: 0});
            table.reload('test', {
                //page: {curr: 2},
                where: {
                    departmentId: $("#searchDepartment").val(),
                    positionId: $("#searchPosition").val(),
                    NoName: $('#searchNoName').val(),
                    timeType: $('#timeType').is(':checked') ? 'create_time' : 'update_time',
                    start: $("#start").val(),
                    end: $("#end").val(),
                },//传递参数 keyWord是获取页面中的关键词
            })
            setTimeout(function () {
                layer.close(index)
            }, 500)
        });
    }

    //刷新，重置搜索条件
    function refresh() {
        $("input[type=file]").val("");//清空文件输入框
        $(".layui-upload-choose").text("");
        $('#searchDepartment').val('');
        $('#searchForm')[0].reset();
        getData();
    }

    layui.use(['layer', 'table', 'treeSelect', 'laydate', 'form'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            table = layui.table,
            form = layui.form,
            laydate = layui.laydate,
            treeSelect = layui.treeSelect;

        $('body').removeClass('layui-hide');//移除有hide属性的类


        /*部门选择器:begin*/
        treeSelectInit('tree', 'searchDepartment', true);

        //部门选择器函数
        function treeSelectInit(treeId, hiddenId, isSearch) {
            treeSelect.render({
                // 选择器
                elem: '#' + treeId,
                // 数据
                data: '/common/department/getDepartmentTree',
                // 请求头
                headers: {},
                // 异步加载方式：get/post，默认get
                type: 'get',
                // 占位符
                placeholder: '请选择所属部门',
                // 是否开启搜索功能：true/false，默认false
                search: true,
                // 一些可定制的样式
                style: {
                    folder: {
                        enable: false
                    },
                    line: {
                        enable: true
                    }
                },
                // 点击回调
                click: function (d) {
                    //console.log(d.current); // 得到点击节点的treeObj对象
                    //console.log(d.current.id); // 得到点击节点的treeObj对象
                    var departmentId = d.current.id;
                    $('#' + hiddenId).val(departmentId);//设置部门id
                    //初始化岗位选择
                    getPositionByDepartment(departmentId);
                },
                // 加载完成后的回调函数
                success: function (d) {
                    //console.log(d);
                    //选中节点，根据id筛选
                    var parentId = $("#searchDepartment").val();
                    if (isSearch && parentId != 0) {
                        //该条件为判断是否是部门管理点击进入岗位管理
                        treeSelect.checkNode(treeId, parentId);//设置选中节点，第二个参数为节点id
                    }
                    //console.log($('#tree').val());

                    //获取zTree对象，可以调用zTree方法
                    //var treeObj = treeSelect.zTree('tree');
                    //console.log(treeObj);
                    //刷新树结构
                    treeSelect.refresh(treeId);
                }
            });
        }

        /*部门选择器:end*/

        //初始化岗位下拉框，全部岗位可选
        getPositionByDepartment('');

        //根据部门id获取所有岗位
        function getPositionByDepartment(departmentId) {
            $.ajax({
                url: '/common/position/getPositionByDepartment',
                type: 'get',
                dataType: 'json',
                data: {'departmentId': departmentId},
                success: function (data) {
                    if (data.code == '000') {
                        //console.log(data.message);
                        $("#searchPosition option").remove();
                        $("#searchPosition").append("<option value=''>请选择岗位</option>"); //为Select追加一个Option(下拉项)
                        $.each(data.data, function (index, value) {
                            $("#searchPosition").append("<option value='" + value.id + "'>" + value.positionName + "</option>"); //为Select追加一个Option(下拉项)
                        });

                        //初始化选中
                        if (positionId) {
                            $("#searchPosition").val(positionId);//设置初始查询参数
                        }

                        //layui的select选项动态添加数据后，需要初始化容器，否则数据显示不出来
                        form.render('select');

                    } else {
                        layer.msg(data.message, {
                            anim: 6
                        })
                    }
                }
            })
        }


        /*表格数据初始化：begin*/
        table.render({
            elem: '#test',
            height: 'full-200',
            toolbar: '#toolbarDemo', //开启头部工具栏，并为其绑定左侧模板
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: apiUrl + 'getData',
            cols: [[

                {type: 'checkbox', width: 80},
                {type: 'numbers', width: 80, title: '序号'},
                //{field: 'id', width: '2%', title: 'ID', sort: true},
                {field: 'head', width: 80, title: '头像', templet: '#headImgTpl', align: 'center'},
                {field: 'userNo', width: 90, title: '工号', sort: true},
                {field: 'username', width: 100, title: '姓名'},
                {field: 'phone', width: 120, title: '手机号'},
                {field: 'email', minWidth: 180, title: '企业邮箱'},
                {field: 'departmentName', width: 130, title: '部门'},
                {field: 'positionName', width: 130, title: '岗位'},
                {field: 'superior', width: 100, title: '直接上级'},
                {field: 'departmentHead', width: 100, title: '部门负责人'},
                {field: 'operator', width: 100, title: '最近操作人', hide: 'true'},
                {field: 'status', width: 100, title: '状态', templet: '#switchTpl', unresize: true},
                {field: 'update_time', width: 138, title: '更新时间', sort: true},
                {field: 'create_time', width: 138, title: '添加时间', sort: true, hide: 'true'},
                {fixed: 'right', width: 180, title: '操作', align: 'center', toolbar: '#barDemo'}
            ]],
            page: true,
            where: {
                departmentId: '',
                positionId: $("#searchPositionHidden").val(),
                NoName: '',
                timeType: '',
                start: '',
                end: '',
            },//传递参数 keyWord是获取页面中的关键词
            done: function (res, curr, count) {
                $('.layui-table-cell').css('height', '50px');
                $('.layui-table-cell').css('line-height', '50px');
            }

        });
        /*表格数据初始化：end*/

        //监听用户状态操作
        form.on('switch(statusAction)', function (obj) {
            var id = this.value;
            var status = obj.elem.checked ? 1 : 0;
            $.ajax({
                url: apiUrl + 'editStatus',
                type: 'post',
                dataType: 'json',
                data: {id: id, status: status},
                success: function (data) {
                    if (data.code == '000') {
                        layer.tips(data.message, obj.othis);
                    } else {
                        layer.tips(data.message, obj.othis, {
                            anim: 6
                        })
                    }
                },
                error: function (data) {
                    layer.msg(data.responseText, {
                        anim: 6
                    });
                    setTimeout(function () {
                        getData();
                    }, 1500)

                }
            })

        });

        //监听搜索按钮、Entry（回车）
        $(function () {
            //回车触发
            $(document).keyup(function (event) {
                if (event.keyCode == 13) {
                    $("#search").trigger("click");
                }
            });
            //搜索按钮
            $('#search').on("click", function () {
                console.log($("#searchDepartment").val())
                getData();

            });
            //重置按钮
            $("#reset").on("click", function () {
                refresh();//刷新，重置搜索条件
            });

        });


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
                        layer.msg(data.message);
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
        }

        //头工具栏事件
        table.on('toolbar(test)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'add':
                    xadmin.open('添加用户', '/common/user/add', 460, 530);
                    break;
                case 'delete':
                    var data = checkStatus.data;
                    var deleteIds = new Array();
                    data.forEach((item, index) => {
                        deleteIds[index] = item.id;
                    })
                    if (data.length == 0) {
                        layer.msg('未选中数据！', {
                            time: 3000,
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
        //监听行工具事件
        table.on('tool(test)', function (obj) {
            var data = obj.data;
            //console.log(obj)
            switch (obj.event) {
                case 'role':
                    xadmin.open('角色管理', '/common/user/role?id=' + data.id + '&userNo=' + data.userNo + '&username=' + data.username, 860, 500)
                    break;
                case 'edit':
                    xadmin.open('编辑', '/common/user/edit?id=' + data.id, 500, 610)
                    break;
                case 'del':
                    var rowIndex = obj.tr['0'].rowIndex + 1; //行号
                    layer.msg('确定删除吗？' + rowIndex, {
                        //icon: 1,
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
        //监听单元格编辑
        table.on('edit(test)', function (obj) {
            var value = obj.value //得到修改后的值
                , data = obj.data //得到所在行所有键值
                , field = obj.field; //得到字段
            layer.msg('[ID: ' + data.id + '] ' + field + ' 字段更改为：' + value);
        });


        // 监听全选
        form.on('checkbox(checkall)', function (data) {

            if (data.elem.checked) {
                $('tbody input').prop('checked', true);
            } else {
                $('tbody input').prop('checked', false);
            }
            form.render('checkbox');
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#start', //指定元素
            type: 'datetime',
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end', //指定元素
            type: 'datetime'
        });


    });
    /*显示数据：end*/
    /*tips层:begin*/

    /*tips层:end*/

</script>

</body>
</html>