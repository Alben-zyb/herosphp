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

<div class="layui-fluid" style="margin: 0px;padding: 0px">
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
                        <div class="layui-inline" style="width: 120px">
                            <select id="searchPosition" name="searchPosition" lay-verify="" lay-search>
                                <option value="">请选择岗位</option>
                            </select>
                        </div>
                        <label for="searchNoName">工号/姓名</label>
                        <div class="layui-inline" style="width: 120px;">
                            <input type="text" name="searchNoName" id="searchNoName" placeholder="请输入工号或姓名"
                                   autocomplete="off"
                                   class="layui-input">
                        </div>

                        <div class="layui-inline ">
                            <a class="layui-btn" lay-submit="" lay-filter="sreach" title="搜索" id="search"><i
                                    class="layui-icon">&#xe615;</i></a>
                        </div>
                        <div class="layui-inline ">
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
                        <button class="layui-btn layui-btn-sm" lay-event="addMeetingMember"><i
                                class="layui-icon">&#xe624;</i>添加</button>
                    </div>
                </script>
                <table class="layui-hide layui-table layui-form" id="test" lay-filter="test"></table>
                <!--自定义显示角色状态，可操作-->

                <script type="text/html" id="barDemo">
                    <a class="layui-btn  layui-btn-xs" lay-event="add" title="添加"><i
                            class="layui-icon">&#xe624;</i></a>
                </script>
                <!--表格数据：begin-->
            </div>
        </div>
    </div>
</div>
<script src="/static/public/layui/import.js"></script>
<script>
    //接收后端传来的参数
    var url_getPositionByDepartment = '<?php echo $url_getPositionByDepartment?>';
    var url_getDepartmentTree = '<?php echo $url_getDepartmentTree?>';
    var url_getData = '<?php echo $url_getData?>';
    var login_id = '<?php echo $login_id?>';


    //setInterval(getData, 60*1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题

    //初始化岗位下拉框，全部岗位可选
    getPositionByDepartment('');

    //根据部门id获取所有岗位
    function getPositionByDepartment(departmentId) {
        $.ajax({
            url: url_getPositionByDepartment,
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
    function getData() {
        layui.use('table', function () {
            var table = layui.table;
            var index = layer.msg('查询中，请稍等...', {icon: 16, time: false, shade: 0});
            table.reload('test', {
                //page: {curr: 2},
                where: {
                    departmentId: $("#searchDepartment").val(),
                    positionId: $("#searchPosition option:selected").val(),
                    NoName: $('#searchNoName').val(),
                    loginId: login_id,
                    timeType: $('#timeType').is(':checked') ? 'create_time' : 'update_time',
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

    layui.use(['layer', 'table', 'treeSelect', 'form'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            table = layui.table,
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
                data: url_getDepartmentTree,
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

        /*============================表格数据初始化：begin=========================*/
        table.render({
            elem: '#test',
            height: 'full-78',
            toolbar: '#toolbarDemo', //开启头部工具栏，并为其绑定左侧模板
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: url_getData,
            cols: [[

                {type: 'checkbox',width:'8%'},
                //{field: 'id', width: '2%', title: 'ID', sort: true},
                {field: 'userNo', width: '10%', title: '工号'},
                {field: 'username', width: '12%', title: '姓名'},
                // {field: 'phone', width: '8%', title: '手机号'},
                {field: 'email', title: '企业邮箱'},
                {field: 'departmentName', width: '15%', title: '部门'},
                {field: 'positionName', width: '16%', title: '岗位'},
                {fixed: 'right',width:80, title: '操作', align: 'center', toolbar: '#barDemo'}
            ]],
            page: true,
            where: {
                departmentId: '',
                positionId: '',
                NoName: '',
                loginId: login_id,
                timeType: '',
            },//传递参数 keyWord是获取页面中的关键词

        });
        /*=====================表格数据初始化：end==========================*/



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
                //console.log($("#searchDepartment").val())
                getData();
            });
            //重置按钮
            $("#reset").on("click", function () {
                refresh();//刷新，重置搜索条件
            });

        });

        //表头工具栏事件
        table.on('toolbar(test)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'addMeetingMember':
                    var userArr = checkStatus.data;
                    if (userArr.length == 0) {
                        layer.msg('未选中数据！', {
                            time:3000,
                            closeBtn:true,
                            anim:6
                        });
                    } else {
                        //layer.close(index);
                        //deleteByIds(deleteIds);
                        //$(".layui-form-checked").not('.header').parents('tr').remove(); //移除表格行
                        //console.log(data);
                        parent.addMeetingMember(userArr);
                    }
                    break;
            }
        });
        //监听行工具事件
        table.on('tool(test)', function (obj) {
            var userArr=new Array(obj.data);
            switch (obj.event) {
                case 'add':
                    parent.addMeetingMember(userArr);
                    break;


            }
        });


    });
    /*显示数据：end*/


</script>

</body>
</html>