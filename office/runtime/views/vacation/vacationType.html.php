<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
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
    <span class="layui-breadcrumb">
      <a href="">首页</a>
      <a href="">休假管理</a>
      <a><cite>休假类型管理</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!--添加请假类型：begin-->
                <div class="" style="border-bottom: 4px solid #f1f1f1;padding: 10px 15px;">

                    <form class="layui-form layui-col-space5" id="addForm">

                        <label for="typeNo">类型编号</label>
                        <!--<a class="layui-btn layui-btn-primary"
                           href="https://wujiawei0926.gitee.io/treeselect/docs/doc.html">查看文档</a>-->
                        <div class="layui-inline">
                            <input type="text" id="typeNo" name="typeNo" placeholder="请输入请假类型编号"
                                   required lay-verify="typeNo" autocomplete="off" class="layui-input">
                        </div>


                        <label for="typeName">类型名称</label>
                        <div class="layui-inline">
                            <input type="text" name="typeName" id="typeName" required
                                   lay-verify="typeName" placeholder="请输入请假类型名称"
                                   autocomplete="off" class="layui-input">
                        </div>

                        <div class="layui-input-inline">
                            <button class="layui-btn" lay-submit lay-filter="addForm">添加请假类型
                            </button>
                            <a id="resetAdd" class="layui-btn layui-btn-primary">重置</a>
                        </div>
                    </form>
                </div>

                <!--添加请假类型：end-->

                <!--请假类型搜索功能：begin-->
                <div class="" style="padding: 10px 15px;">
                    <form class="layui-form layui-col-space5" id="searchForm">
                        <label for="searchTypeNo">类型编号</label>
                        <div class="layui-inline">
                            <input type="text" name="searchTypeNo" id="searchTypeNo" placeholder="请输入请假类型编号"
                                   autocomplete="off"
                                   class="layui-input">
                        </div>
                        <label for="searchTypeName">类型名称</label>
                        <div class="layui-inline">
                            <input type="text" name="searchTypeName" id="searchTypeName" placeholder="请输入请假类型名称"
                                   autocomplete="off"
                                   class="layui-input">
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
                <!--请假类型搜索功能：end-->
                <!--表格数据：begin-->
                <script type="text/html" id="toolbarDemo">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="delete">批量删除<i
                                class="layui-icon">&#xe640;</i></button>
                    </div>
                </script>
                <table class="layui-hide layui-table layui-form" id="vacationType" lay-filter="vacationType"></table>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除<i
                            class="layui-icon">&#xe640;</i></a>
                </script>
                <!--表格数据：end-->
            </div>
        </div>
    </div>
</div>
<script src="/static/public/layui/import.js"></script>
<script>
    //接收后端传来的参数
    //定义统一资源路径
    var apiUrl = '<?php echo $apiUrl?>';

    /*=================================显示数据：begin==================================*/


    layui.use(['table', 'tablePlug'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            table = layui.table,
            form = layui.form,
            tablePlug = layui.tablePlug;
        tablePlug.smartReload.enable(true);//处理不闪动的关键代码


        /*==========================添加数据:begin=========================*/
        //重置
        $('#resetAdd').on('click', function () {
            $('#addForm')[0].reset();
            $('#parentDepartment').val('');
        });

        //自定义验证规则
        form.verify({
            typeNo: function (value) {
                var rep = /^[a-z]{2,10}$/;
                if (!rep.test(value)) {
                    return '请假类型编号为2~10个小写英文字符';
                }
            },
            typeName: function (value) {
                if (value.length < 1 || value.length > 20) {
                    return '请假类型名称为1~20个字符';
                }
            },
        });
        //提交
        form.on('submit(addForm)', function (data) {
            var field = data.field;//添加表单字段
            //var module=$('#module option:selected').text(); //获取选中的模块名称（非value值）
            //field['module']=module;　//替换表单字段中module的值为text值
            console.log(field);

            $.ajax({
                url: apiUrl + 'addData',
                type: 'post',
                dataType: 'json',
                data: field,
                success: function (data) {
                    if (data.code == '000') {
                        layer.msg(data.message);
                        getData();
                    } else {
                        layer.msg(data.message, {
                            anim: 6
                        });
                    }
                },
                error: function (data) {
                    layer.msg(data.responseText, {
                        anim: 6
                    })
                }
            })

            return false; //不刷新页面
        });


        /*============================添加数据:end============================*/


        /*======================表格数据初始化：begin==================*/
        var dataTable = table.render({
            elem: '#vacationType',
            height: 'full-200',
            toolbar: '#toolbarDemo', //开启头部工具栏，并为其绑定左侧模板
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: apiUrl + 'getData',
            smartReloadModel: true, //处理不闪动的关键代码
            cols: [[

                {type: 'checkbox', width: 80},
                {type: 'numbers', width: 80, title: '序号'},
                {field: 'typeNo', width: 100, title: '类型编号', edit: 'text', sort: true},
                {field: 'typeName', minWidth: 180, title: '类型名称', edit: 'text'},
                {field: 'operator', width: 130, title: '最近操作人'},
                {
                    field: 'update_time',
                    width: 150,
                    title: '更新时间',
                    templet: '<span>{{d.update_time.substr(0, 16)}}</span>',
                    sort: true
                },
                {
                    field: 'create_time',
                    width: 150,
                    title: '添加时间',
                    templet: '<span>{{d.create_time.substr(0, 16)}}</span>',
                    sort: true
                },
                {fixed: 'right', width: 100, title: '操作', align: 'center', toolbar: '#barDemo'}
            ]],
            page: true,
            where: {
                typeNo: '',
                typeName: '',
            },//传递参数 keyWord是获取页面中的关键词

        });

        //window.setInterval(getData, 5*1000);  //每5秒重载数据

        //获取更新数据
        function getData() {
            dataTable.reload({
                //page: {curr: 2},
                where: {
                    typeNo: $("#searchTypeNo").val(),
                    typeName: $("#searchTypeName").val(),
                },//传递参数
            })
        }

        //刷新，重置搜索条件
        function refresh() {
            $("input[type=file]").val("");//清空文件输入框
            $(".layui-upload-choose").text("");
            $('#searchDepartment').val('');
            $('#searchForm')[0].reset();
            layer.msg('加载中...',{icon: 16, time: 500, shade: 0});
            getData();
        }

        //监听搜索按钮、Entry（回车）
        $(function () {
            //回车触发
            $(document).keyup(function (event) {
                if (event.keyCode == 13) {
                    getData();
                }
            });
            //搜索按钮
            $('#search').on("click", function () {
                layer.msg('查询中，请稍等...', {icon: 16, time: 500, shade: 0});
                getData();
            });
            //重置按钮
            $("#reset").on("click", function () {
                refresh();//刷新，重置搜索条件
            });

        });

        /*=====================表格数据初始化：end==================*/


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
                    })
                }
            })
        }

        //监听表格复选框选择
        //头工具栏事件
        table.on('toolbar(vacationType)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'delete':
                    var data = checkStatus.data;
                    var deleteIds = new Array();
                    data.forEach((item, index) => {
                        deleteIds[index] = item.id;
                    })
                    if (data.length == 0) {
                        layer.msg('未选中数据', {
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
        table.on('tool(vacationType)', function (obj) {
            var data = obj.data;
            //console.log(obj)
            switch (obj.event) {
                case 'del':
                    var rowIndex = obj.tr['0'].rowIndex + 1; //行号
                    layer.msg('确定删除吗？' + rowIndex, {
                        //icon: 1,
                        time: 5000,
                        closeBtn: true,
                        btn: ['yes', 'no'],
                        yes: function (index, layero) {
                            deleteByIds(data.id, obj);
                            //do something
                            layer.close(index);
                        }
                    });
                    break;
            }
        });

        //监听单元格编辑(更新操作)
        table.on('edit(vacationType)', function (obj) {
            var id = obj.data.id,  //修改的数据id
                field = obj.field, //得到字段
                value = obj.value; //得到修改后的值

            $.ajax({
                url: apiUrl + 'updateData',
                type: 'post',
                dataType: 'json',
                data: "id=" + id + "&" + field + "=" + value,
                success: function (data) {
                    console.log(data.message);
                    if (data.code == '000') {
                        //发异步，把数据提交给php
                        layer.msg("修改成功",
                            {
                                icon: 6,
                                time: 2000,
                                closeBtn: true,
                            });

                    } else {
                        layer.msg(data.message, {
                            anim: 6,
                            time: 2000,
                        }, function () {
                            refresh();
                        });

                    }
                }
            })
            return false;
        });


    });
    /*显示数据：end*/

    /*tips层:begin*/
    $('#typeName').focus(function () {
        layer.tips('2~10位中文', '#typeName', {
            tips: 1,
            time: 3000
        });
    })
    /*tips层:end*/

</script>

</body>
</html>