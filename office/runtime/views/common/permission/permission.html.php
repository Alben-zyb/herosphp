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
                <!--添加岗位：begin-->
                <div class="" style="border-bottom: 4px solid #f1f1f1;padding: 10px 15px;">

                    <form class="layui-form layui-col-space5" id="addForm">

                        <label for="module">模块</label>
                        <div class="layui-inline">
                            <select id="module" name="module" lay-filter="module" lay-verify="required" lay-search>
                                <option value="">请选择模块</option>
                            </select>
                        </div>


                        <label for="controller">控制器</label>
                        <div class="layui-inline">
                            <select id="controller" name="controller" lay-filter="controller" lay-verify="required"
                                    lay-search>
                                <option value="">请选择控制器</option>
                            </select>
                        </div>
                        <label for="method">方法</label>
                        <div class="layui-inline">
                            <select id="method" name="method" lay-filter="method" lay-verify="required" lay-search>
                                <option value="">请选择方法</option>
                            </select>
                        </div>

                        <label for="permissionName">权限名称</label>
                        <div class="layui-inline">
                            <input type="text" name="permissionName" id="permissionName" placeholder="请输入权限名称"
                                   autocomplete="off" lay-verify="required"
                                   class="layui-input">
                        </div>

                        <div class="layui-input-inline">
                            <button class="layui-btn" lay-submit lay-filter="addForm">添加权限
                            </button>
                            <a id="resetAdd" class="layui-btn layui-btn-primary">重置</a>
                        </div>
                    </form>
                </div>

                <!--添加岗位：end-->
                <!--岗位搜索功能：begin-->
                <div class="" style="padding: 10px 15px;">
                    <form class="layui-form layui-col-space5" id="searchForm">

                        <label for="searchModule">模块</label>
                        <div class="layui-inline">
                            <select id="searchModule" name="searchModule" lay-filter="searchModule" lay-verify=""
                                    lay-search>
                                <option value="">请选择模块</option>
                            </select>
                        </div>
                        <label for="searchPermissionName">权限名称</label>
                        <div class="layui-inline">
                            <input type="text" name="searchPermissionName" id="searchPermissionName"
                                   placeholder="请输入权限名称"
                                   autocomplete="off"
                                   class="layui-input">
                        </div>
                        <label for="searchMethod">方法规则</label>
                        <div class="layui-inline">
                            <input type="text" name="searchMethod" id="searchMethod" placeholder="请输入方法规则"
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
                        <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="delete">批量删除<i
                                class="layui-icon">&#xe640;</i></button>
                    </div>
                </script>
                <table class="layui-hide layui-table layui-form" id="test" lay-filter="test"></table>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="edit" title="编辑">
                        <i class="layui-icon">&#xe642;</i></a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" title="删除">
                        <i class="layui-icon">&#xe640;</i></a>
                </script>
                <!--表格数据：begin-->
            </div>
        </div>
    </div>
</div>
<script src="/static/public/layui/import.js"></script>
<script>
    /*定义统一资源路径*/
    //接收后端传来的参数
    var apiUrl = '<?php echo $apiUrl?>';

    // 每隔1分钟刷新数据
    //setInterval(getData, 60*1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题
    //初始化模块下拉框
    getModule();

    //根据获取所有模块方法
    function getModule() {
        $.ajax({
            url: apiUrl + 'getModule',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#module option").remove();
                    $("#module").append("<option value=''>请选择模块</option>"); //为Select追加一个Option(下拉项)
                    $.each(data.data, function (index, value) {
                        $("#module").append("<option value='" + index + "'>" + value + "</option>"); //为Select追加一个Option(下拉项)
                    });

                    $("#searchModule option").remove();
                    $("#searchModule").append("<option value=''>请选择模块</option>"); //为Select追加一个Option(下拉项)
                    $.each(data.data, function (index, value) {
                        $("#searchModule").append("<option value='" + index + "'>" + value + "</option>"); //为Select追加一个Option(下拉项)
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

    //根据模块名获取模块下的控制器
    function getControllerByModule(module) {
        //为空，没选择选项，返回
        if (module == '') {
            return false;
        }
        $.ajax({
            url: apiUrl + 'getControllerByModule',
            type: 'get',
            dataType: 'json',
            data: {module: module},
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#controller option").remove();
                    $("#controller").append("<option value=''>请选择控制器</option>"); //为Select追加一个Option(下拉项)
                    $.each(data.data, function (index, value) {
                        $("#controller").append("<option value='" + index + "'>" + value + "</option>"); //为Select追加一个Option(下拉项)
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

    //根据控制器名获取控制器下的方法
    function getMethodByController(controller) {
        //为空，没选择选项，返回
        if (controller == '') {
            return false;
        }
        $.ajax({
            url: apiUrl + 'getMethodByController',
            type: 'get',
            dataType: 'json',
            data: {controller: controller},
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#method option").remove();
                    $("#method").append("<option value=''>请选择方法</option>"); //为Select追加一个Option(下拉项)
                    $.each(data.data, function (index, value) {
                        $("#method").append("<option value='" + index + "'>" + value + "</option>"); //为Select追加一个Option(下拉项)
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

    /*显示数据：begin*/
    //获取表格数据
    function getTable() {
        layui.use('table', function () {
            var table = layui.table;
            var index = layer.msg('查询中，请稍等...', {icon: 16, time: false, shade: 0});
            table.reload('test', {
                //page: {curr: 2},
                where: {
                    permissionName: $("#searchPermissionName").val(),
                    module: $('#searchModule option:selected').text() == '请选择模块' ? '' : $('#searchModule option:selected').text(),
                    method: $("#searchMethod").val(),
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
        $('#searchForm')[0].reset();
        getTable();
    }

    layui.use(['layer', 'table', 'treeSelect', 'tablePlug','laydate'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            table = layui.table,
            tablePlug = layui.tablePlug,
            laydate = layui.laydate,
            form = layui.form;

        tablePlug.smartReload.enable(true);//处理不闪动的关键代码
        $('body').removeClass('layui-hide');//移除有hide属性的类

        /*===========添加表单监听：begin============*/
        //监听模块下拉框
        form.on('select(module)', function (data) {
            var module = data.value; //得到被选中的值
            console.log(module);
            //每一次选择，清空控制器和方法下拉框
            $('#controller option').not(":first").remove();
            $('#method option').not(":first").remove();
            getControllerByModule(module);
        });
        //监听控制器下拉框
        form.on('select(controller)', function (data) {
            console.log(data.value); //得到被选中的值
            var controller = data.value;
            getMethodByController(controller);
        });
        //监听方法下拉框
        form.on('select(method)', function (data) {
            console.log(data.value); //得到被选中的值
            var method = data.value;
            //layer.msg(method);
        });


        //重置
        layui.$('#resetAdd').on('click', function () {
            $('#addForm')[0].reset();
            //清空控制器和方法下拉框
            $('#controller option').not(":first").remove();
            $('#method option').not(":first").remove();
        });

        //自定义验证规则
        form.verify({
            addDepartmentName: function (value) {
                var pattern = /(^[\u4E00-\u9FA5]{1,20}$)|(^[\u4E00-\u9FA5]{1,20}[a-zA-Z0-9]{1,20}$)/;
                if (!pattern.test(value.trim())) {
                    return '部门名称为1~20个字符';
                }
            },
        });

        //添加表单
        form.on('submit(addForm)', function (data) {
            var field = data.field;//添加表单字段
            var module = $('#module option:selected').text(); //获取选中的模块名称（非value值）
            field['module'] = module;　//替换表单字段中module的值为text值
            console.log(field);

            $.ajax({
                url: apiUrl + 'add',
                type: 'post',
                dataType: 'json',
                data: field,
                success: function (data) {
                    if (data.code == '000') {
                        layer.msg(data.message,
                            {
                                icon: 6,
                                time: 2000,
                                closeBtn: true,
                            }, function () {
                                // 获得frame索引
                                //var index = parent.layer.getFrameIndex(window.name);
                                //关闭当前frame
                                //parent.layer.close(index);
                                getTable();
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
            return false; //不刷新页面
        });

        /*===========添加表单监听：end============*/

        /*表格数据初始化：begin*/
        table.render({
            elem: '#test',
            height: 'full-200',
            toolbar: '#toolbarDemo', //开启头部工具栏，并为其绑定左侧模板
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: apiUrl + 'getTableData',
            smartReloadModel: true, //处理不闪动的关键代码
            cols: [[

                {type: 'checkbox', width: 80},
                {type: 'numbers', width: 80, title: '序号'},
                // {field: 'id', width: '5%', title: 'ID', sort: true},
                {field: 'module', width: 150, title: '模块', sort: true},
                {field: 'permissionName', width: 200, title: '权限名称'},
                {field: 'method',minWidth: 250, title: '方法规则', sort: true},
                {field: 'update_time', width: 138, title: '更新时间', sort: true},
                {field: 'create_time', width: 138, title: '添加时间', sort: true},
                {fixed: 'right', width: 130, title: '操作', align: 'center', toolbar: '#barDemo'}
            ]],
            page: true,
            where: {
                permissionName: '',
                module: '',
                method: '',
                timeType: '',
                start: '',
                end: '',
            },//传递参数 keyWord是获取页面中的关键词

        });
        /*表格数据初始化：end*/


        //监听搜索按钮、Entry（回车）
        $(function () {
            //搜索按钮
            $('#search').on("click", function () {
                console.log($("#searchDepartment").val())
                getTable();

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
                    xadmin.open('添加权限', '/common/permission/add', 460, 530, false)

                    break;
                case 'delete':
                    var data = checkStatus.data;
                    var deleteIds = new Array();
                    //提取需要删除的id
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
        //监听行工具事件
        table.on('tool(test)', function (obj) {
            var data = obj.data;
            //console.log(obj)
            switch (obj.event) {
                case 'edit':
                    xadmin.open('编辑', apiUrl + 'edit?id=' + data.id, 460, 400)
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