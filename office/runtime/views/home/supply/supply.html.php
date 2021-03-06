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
    <style>
        body {
            padding-top: 15px;
            box-sizing: border-box;
            border: 5px solid #FFB800;
            border-top: none;
            height: 100%;
            overflow-y: auto;
        }

    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!--物品搜索功能：begin-->
                <div class="" style="padding: 10px 15px;">
                    <form class="layui-form layui-col-space5" id="searchForm">


                        <label for="searchGoodsCategory">物品分类</label>
                        <div class="layui-inline" style="width: 130px;">
                            <select id="searchGoodsCategory" name="searchGoodsCategory" lay-verify="" lay-search>
                                <option value="">搜索选择分类</option>
                            </select>
                        </div>
                        <label for="searchGoodsName">物品名称</label>
                        <div class="layui-inline" style="width: 120px;">
                            <input type="text" name="searchGoodsName" id="searchGoodsName" placeholder="请输入物品名称"
                                   autocomplete="off" class="layui-input">
                        </div>

                        <div class="layui-inline" style="width: 50px">
                            <input type="text" name="searchNumberMin" id="searchNumberMin"
                                   placeholder="" autocomplete="off" class="layui-input">
                        </div>
                        <label for="searchNumberMax"><span class="iconfont">&#xe697;= </span>物品数量<span
                                class="iconfont">&#xe697;</span></label>
                        <div class="layui-inline" style="width: 50px">
                            <input type="text" name="searchNumberMax" id="searchNumberMax"
                                   laceholder="" autocomplete="off" class="layui-input">
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

                        <div class="layui-inline layui-show-xs-block" style="float: right">
                            <a class="layui-btn layui-btn-normal" lay-submit="" lay-filter="sreach" title="申领记录"
                               id="history"><i
                                    class="iconfont">&#xe6f3;申领记录</i></a>
                        </div>

                    </form>
                </div>
                <!--物品搜索功能：end-->
                <!--表格数据：begin-->
                <table class="layui-hide layui-table layui-form" id="goodsTb" lay-filter="goodsTb"></table>
                <!--自定义显示操作-->
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="outHouse" title="申领"><i class="layui-icon">&#xe631;申领</i></a>
                </script>

                <!--表格数据：begin-->
            </div>
        </div>
    </div>
</div>

<script src="/static/public/layui/import.js"></script>
<script>
    //接收后端传来的参数
    //定义统一资源路径
    var apiUrl = '<?php echo $apiUrl?>';

    getCategory();

    //获取所有物品分类
    function getCategory() {
        $.ajax({
            url: apiUrl + 'getCategory',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#searchGoodsCategory option").not(":first").remove();
                    $.each(data.data, function (index, value) {
                        $("#searchGoodsCategory").append("<option value='" + value.id + "'>" + value.categoryNo + '--' + value.categoryName + "</option>"); //为Select追加一个Option(下拉项)
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

    /*===================================显示数据：begin==========================*/

    layui.define(['layer', 'table', 'tablePlug'], function (exports) {
        var $ = layui.jquery,
            layer = layui.layer,
            table = layui.table,
            tablePlug = layui.tablePlug;

        tablePlug.smartReload.enable(true);//处理不闪动的关键代码

        /*==================表格数据初始化：begin=================*/
        var dataTable = table.render({
            elem: '#goodsTb',
            height: 'full-200',
            toolbar: true, //开启头部工具栏，并为其绑定左侧模板
            //cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            url: apiUrl + 'getData',
            smartReloadModel: true, //处理不闪动的关键代码
            cols: [[

                {type: 'checkbox', width: 90},
                {type: 'numbers', width: 90, title: '序号'},
                {field: 'category', title: '物品分类', width: 140},
                {field: 'goodsNo', title: '物品编号', width: 140},
                {field: 'goodsName', title: '物品名称', minWidth: 140},
                {field: 'number', title: '剩余库存', width: 100},

                {fixed: 'right', width: 100, title: '操作', align: 'center', toolbar: '#barDemo'}
            ]],
            page: true,

        });

        window.setInterval(getData, 5000);  //每5秒重载数据

        //获取更新数据
        function getData() {
            dataTable.reload({
                //page: {curr: 2},
                where: {
                    category: $("#searchGoodsCategory").val(),
                    goodsName: $("#searchGoodsName").val(),
                    min: $("#searchNumberMin").val(),
                    max: $("#searchNumberMax").val(),
                    handler: $("#searchHandler").val(),
                    status: $("#searchStatus").val(),
                    timeType: $('#timeType').is(':checked') ? 'create_time' : 'update_time',
                    start: $("#start").val(),
                    end: $("#end").val(),
                },//传递参数
            })
        }

        //向外部暴露的刷新方法
        exports('exportGetData', function () {
            getData();  // 刷新一级数据(数据模式)
        });

        //刷新，重置搜索条件
        function refresh() {
            $("input[type=file]").val("");//清空文件输入框
            $(".layui-upload-choose").text("");
            $('#searchForm')[0].reset();
            getData();
        }


        /*=======================表格数据初始化：end======================*/

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
                refresh();//刷新，重置搜索条件
            });

            //申领记录按钮
            $('#history').on('click',function () {
                xadmin.open('申领记录', apiUrl + 'history', 1300, 600);
            })

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

        //监听行工具事件
        table.on('tool(goodsTb)', function (obj) {
            var data = obj.data;
            switch (obj.event) {
                case 'outHouse':
                    xadmin.open('办公用品申领', apiUrl + 'outHouse?id=' + data.id, 430, 450, false, 0.2)
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


    });
    //获取更新数据(编辑页面调用)
    function getData() {
        layui.exportGetData();
    }
    /*显示数据：end*/
    /*tips层:begin*/

    /*tips层:end*/

</script>
</body>
</html>