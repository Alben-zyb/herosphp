<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>会议室预约</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8">
    <link rel="stylesheet" href="/static/home/css/rangeTime.css">
    <link rel="stylesheet" href="/static/admin/index/css/font.css">
    <link rel="stylesheet" href="/static/admin/index/css/xadmin.css">
    <link rel="stylesheet" href="/static/public/layui/css/layui.css" media="all">

    <link rel="stylesheet" href="/static/public/timerange/css/jquery.range.css">

    <link rel="stylesheet" type="text/css" href="/static/public/layui/label/css/label.css">

    <!--<script type="text/javascript" src="/static/public/js/jquery.min.js" charset="utf-8"></script>-->
    <script type="text/javascript" src="/static/public/timerange/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/public/timerange/js/jquery.range.js"></script>
    <script type="text/javascript" src="/static/home/js/rangeTime.js"></script>

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
            border: 5px solid #1E9FFF;
            border-top: none;
            height: 100%;
            overflow-y: auto;
        }

        .table-head {
            padding-right: 17px;
            background-color: #f2f2f2;
            color: #000;
        }

        .table-body {
            width: 100%;
            height: 340px;
            overflow-y: scroll;
        }

        .table-head table, .table-body table {
            width: 100%;
        }

        #add span {
            width: 70px;
        }
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!--会议室搜索功能：begin-->
                <div class="" style="padding: 10px 15px;">
                    <form class="layui-form layui-col-space5" id="searchForm">


                        <label for="searchRoom">会议室</label>
                        <div class="layui-inline" style="width: 140px">
                            <select id="searchRoom" name="searchRoom" lay-verify="" lay-search>
                                <option value="">搜索选择会议室</option>
                            </select>
                        </div>

                        <label for="searchDevice">设备情况</label>
                        <div class="layui-inline" style="width: 300px">
                            <input type="text" name="searchDevice" id="searchDevice" placeholder="输入设备情况"
                                   autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline" style="width: 50px">
                            <input type="text" name="searchCapacityMin" id="searchCapacityMin"
                                   placeholder="" autocomplete="off" class="layui-input">
                        </div>
                        <label for="searchCapacityMax"><span class="iconfont">&#xe697;＝</span>容纳人数<span
                                class="iconfont">&#xe697;</span></label>
                        <div class="layui-inline" style="width: 50px">
                            <input type="text" name="searchCapacityMax" id="searchCapacityMax"
                                   laceholder="" autocomplete="off" class="layui-input">
                        </div>

                        <label for="searchApplyDate">预约日期</label>
                        <div class="layui-inline">
                            <input class="layui-input" autocomplete="off" placeholder="输入日期" name="searchApplyDate"
                                   id="searchApplyDate">
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
                            <a class="layui-btn layui-btn-normal" lay-submit="" lay-filter="sreach" title="预约记录"
                               id="history"><i
                                    class="iconfont">&#xe6f3;</i></a>
                        </div>

                    </form>
                </div>
                <!--会议室搜索功能：end-->
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <blockquote class="layui-elem-quote layui-text">会议室状态</blockquote>

                <div class="layui-card-body">

                    <!--=========table表格数据：begin=============-->
                    <form class="layui-form" action="">
                        <div class="table-head">
                            <table class="layui-table" lay-skin="nob">
                                <colgroup>
                                    <col width="30" align="center">
                                    <col width="150">
                                    <col width="400">
                                    <col width="20" align="center">
                                    <col width="30">
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>选中</th>
                                    <th>编号名称</th>
                                    <th>设备情况</th>
                                    <th>容纳人数</th>
                                    <th>预约日期</th>
                                    <th>预约情况</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-body">
                            <table class="layui-table" lay-skin="nob">
                                <colgroup>
                                    <col width="30" align="center">
                                    <col width="150">
                                    <col width="400">
                                    <col width="20" align="center">
                                    <col width="30">
                                    <col>
                                </colgroup>
                                <tbody id="tbody">
                                <!-- <tr>
                                     <td>
                                         <input type="radio" name="check" value="">
                                     </td>
                                     <td>A1-人才招待室</td>
                                     <td>投影仪</td>
                                     <td>6</td>
                                     <td>
                                         <div class="outer">
                                             <div class="timeAxis">
                                                 <div class="container"></div>
                                                 <div class="directRix">
                                                     <ul>
                                                         <li>8:00</li>
                                                         <li>12:00</li>
                                                         <li>16:00</li>
                                                         <li>20:00</li>
                                                         <li>24:00</li>
                                                     </ul>
                                                 </div>
                                             </div>
                                         </div>
                                     </td>
                                 </tr>-->

                                </tbody>
                            </table>
                        </div>
                    </form>
                    <!--=========table表格数据：end=============-->

                </div>
                <div class="layui-card-body ">

                    <!--=========table表格数据：begin=============-->
                    <form class="layui-form" action="" id="applyForm">
                        <div class="table-head" id="add">
                            <table class="layui-table" lay-skin="nob">
                                <colgroup>
                                    <col width="30" align="center">
                                    <col width="150">
                                    <col width="400">
                                    <col width="20">
                                    <col width="30">
                                    <col>
                                </colgroup>
                                <thead>
                                <tr style="height: 100px">
                                    <th>
                                        <a href="javascript:;" id="check" class="layui-btn">选中会议室</a>
                                    </th>
                                    <th>
                                        <label id="room"></label>
                                        <input type="hidden" id="roomId" name="roomId" lay-verify="roomId">
                                    </th>
                                    <th>
                                        <label id="device"></label>
                                    </th>
                                    <th>
                                        <label id="capacity"></label>
                                    </th>
                                    <th>
                                        <input class="layui-input" autocomplete="off" placeholder="输入日期"
                                               name="date"
                                               id="date" lay-verify="date"
                                               style="margin-left: -8px;padding-left:8px;text-align: left">
                                    </th>
                                    <th style="padding-left: 25px">
                                        <div class="layui-inline">
                                            <div class="rangeTime">
                                                <input class="range-slider" type="hidden" value="540,1080"/>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                            </table>

                            <!--预约会议室功能：begin-->
                            <div class="layui-form" style="padding: 10px 15px;">
                                <label for="theme">会议主题</label>
                                <div class="layui-inline" style="width: 200px">
                                    <input type="text" name="theme" id="theme" placeholder="输入会议主题"
                                           autocomplete="off" class="layui-input" lay-verify="theme">
                                </div>
                                <a id="addMeetingMember" class="layui-btn layui-btn-normal">添加参会人员</a>
                                <div class="layui-inline" style="width: 170px">
                                    <select id="meetingMember" lay-filter="check" lay-search>
                                        <option value="">搜索选择参会人员</option>
                                    </select>
                                </div>
                                <!--参会人员-->
                                <div class="layui-inline">
                                    <div class="wrap">
                                        <div id="tagValue" class="label-selected">

                                        </div>


                                        <!-- 前两个用于向后台提交数据     后3个用于页面判断 -->
                                        <!-- 从标签库里选择的标签ID   1-->                      <!--仅从标签库选择，仅ID-->
                                        <!-- <label>从标签库里选择的标签ID  :</label> -->
                                        <div style="margin:15px;display:none;">
                                            <input name="imagelabels" id="imagelabels" lay-verify="meetingMember"
                                                   style="width:300px">
                                        </div>

                                        <!-- 新增的自定义标签文字     2-->                      <!--2、3、4 都是文字，且有对应顺序的数组-->
                                        <!-- <label>新增的自定义标签文字  :</label> -->
                                        <div style="margin:15px;display:none">
                                            <input name="newtext" style="width:300px">
                                        </div>

                                        <!-- 所有已经选择的标签文字   3-->
                                        <!-- <label>所有已经选择的标签文字  :</label> -->
                                        <div style="margin:15px;display:none">
                                            <input name="selectedtext" style="width:300px">
                                        </div>
                                        <!-- 所有标签库里的标签文字   4-->
                                        <!-- <label>所有标签库里的标签文字  :</label> -->
                                        <div style="margin:15px;display:none">
                                            <input name="existedtext" style="width:300px">
                                        </div>


                                    </div>
                                </div>

                                <div class="layui-input-inline">
                                    <a class="layui-btn layui-btn-primary"
                                       onclick="labelReset()">重置参会人员</a>
                                    <a id="resetApply" class="layui-btn layui-btn-primary">重置</a>
                                    <a class="layui-btn layui-btn-normal" lay-submit lay-filter="applyForm">添加预约</a>
                                </div>

                            </div>

                            <!--预约会议室功能：end-->
                        </div>


                    </form>
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

        initTimeAxis(); //初始化时间轴滑块选择
        initRoomSelect(); //获取会议室，初始化下拉框
        initTable(); //初始化预约表格
        //setInterval(initTable, 60*1000); //每隔1分钟刷新数据表,当停留在在其他页面时,该页面刷新数据,table表格列宽出现问题
    })

    //初始化时间轴滑块
    function initTimeAxis() {

        //时间范围滑块选择
        $('.range-slider').jRange({
            from: 480, to: 1440, step: 5,
            scale: ['08:00', '12:00', '16:00', '20:00', '24:00'],
            format: '%s',
            width: 500,
            showLabels: true,
            theme: 'theme-blue',
            isRange: true
        });
    }

    //获取所有会议室（下拉选择）
    function initRoomSelect() {
        $.ajax({
            url: apiUrl + 'getRoom',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#searchRoom option").remove();
                    $("#searchRoom").append("<option value=''>搜索选择会议室</option>"); //为Select追加一个Option(下拉项)
                    $.each(data.data, function (index, value) {
                        $("#searchRoom").append("<option value='" + value.id + "'>" + value.roomNo + '--' + value.roomName + "</option>"); //为Select追加一个Option(下拉项)
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

    //获取所有用户（下拉选择）
    function initUserSelect() {
        $.ajax({
            url: apiUrl + 'getUser',
            type: 'get',
            dataType: 'json',
            data: '',
            success: function (data) {
                if (data.code == '000') {
                    //console.log(data.message);
                    $("#meetingMember option").remove();
                    $("#meetingMember").append("<option value=''>搜索选择参会人员</option>"); //为Select追加一个Option(下拉项)
                    $.each(data.data, function (index, value) {
                        $("#meetingMember").append("<option value='" + value.id + "'>" + value.userNo + '--' + value.username + "</option>"); //为Select追加一个Option(下拉项)
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


    //获取会议室详细数据
    function initTable() {
        var data = {
            roomId: $("#searchRoom").val(),
            device: $("#searchDevice").val(),
            min: $("#searchCapacityMin").val(),
            max: $("#searchCapacityMax").val(),
            date: $('#searchApplyDate').val(),
        }
        $.ajax({
            url: apiUrl + 'getRoomDetail',
            type: 'get',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.code == '0') {
                    //console.log(data.data);
                    $('#tbody').empty();
                    $.each(data.data, function (index, value) {
                        setRowData('#tbody', value);
                    });
                    layui.use(['form'], function () {
                        layui.form.render();
                    });

                } else {
                    layer.msg(data.message, {
                        anim: 6
                    })
                }
            }
        })
    }

    //向指定id的table添加行
    function setRowData(tableId, data) {
        var tr = $('<tr></tr>');
        var td1 = $('<td><input type="radio" name="check" value="' + data.id + '"></td>');
        var td2 = $('<td>' + data.roomNo + '-' + data.roomName + '</td>');
        var td3 = $('<td>' + data.device + '</td>');
        var td4 = $('<td>' + data.capacity + '</td>');
        var date = data.date ? data.date : '';
        var td5 = $('<td>' + date + '</td>');
        var td6 = createTimeAxis(data.detail); //添加时间轴,该函数在rangeTime.js

        tr.append(td1);
        tr.append(td2);
        tr.append(td3);
        tr.append(td4);
        tr.append(td5);
        tr.append(td6);
        $(tableId).append(tr);
    }


    //刷新，重置搜索条件
    function refresh() {
        $("input[type=file]").val("");//清空文件输入框
        $(".layui-upload-choose").text("");
        $('#searchForm')[0].reset();
        initTable();
    }

    //批量添加参会人员（供子页面iframe调用）
    function addMeetingMember(userArr) {
        $.each(userArr, function (index, user) {
            addLabelInterface(user.id, user.userNo + '-' + user.username);
        })
    }

    layui.use(['layer', 'table', 'laydate', 'form'], function () {
        var $ = layui.jquery,
            layer = layui.layer,
            form = layui.form,
            laydate = layui.laydate;

        //获取选中行，设置选中的会议室信息
        $("#check").on('click', function () {
            var checkId = $('input:radio[name="check"]:checked').val();
            if (!checkId) {
                layer.msg('未选中数据！', {
                    time: 3000,
                    closeBtn: true,
                    anim: 6
                });
                return;
            }
            $.ajax({
                url: apiUrl + 'getRoom',
                type: 'get',
                dataType: 'json',
                data: {id: checkId},
                success: function (data) {
                    if (data.code == '000') {
                        var room = data.data;
                        $('#roomId').val(room.id);
                        $('#room').html(room.roomNo + '-' + room.roomName);
                        $('#device').html(room.device);
                        $('#capacity').html(room.capacity);
                        initUserSelect(); //初始化参会人员下拉选择框
                    } else {
                        layer.msg(data.message, {
                            anim: 6
                        })
                    }
                }
            })

        });

        //弹出选择参会人员视图
        $('#addMeetingMember').on('click', function () {
            layer.open({
                type: 2,
                title: '添加参会人员',
                shadeClose: true,
                shade: 0,
                area: ['800px', '80%'],
                offset: 't',
                content: apiUrl + 'user' //iframe的url
            });
        })


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
                initTable();

            });
            //重置按钮
            $("#reset").on("click", function () {
                refresh();//刷新，重置搜索条件
            });

            //历史记录按钮
            $("#history").on("click", function () {
                xadmin.open('预约记录', apiUrl + 'history', 1200, 600);
            });

        });

        /*============添加表单监听：begin==============*/

        //重置
        layui.$('#resetApply').on('click', function () {
            $('#applyForm')[0].reset();
            $('#room').html('');
            $('#device').html('');
            $('#capacity').html('');
            $('#roomId').val('');
            labelReset();
        });

        //自定义验证规则
        form.verify({
            roomId: function (value) {
                if (!value) {
                    return '未选中会议室';
                }
            },
            theme: function (value) {
                var pattern = /([\u4E00-\u9FA5]{1,20}$)|([a-zA-Z0-9]{1,20}$)/;
                if (!pattern.test(value.trim())) {
                    return '会议主题名称为1~20个字符';
                }
            },
            meetingMember: function (value) {
                if (!value) {
                    return '未添加参会人员';
                }
            },
        });
        //提交，添加预约
        form.on('submit(applyForm)', function (data) {
            var field = data.field;//添加表单字段
            field['meetingMember'] = getLabelIds();　//添加参会人员字段
            field['start'] = $('.pointer-label-low').text();　//添加会议时间开始字段
            field['finish'] = $('.pointer-label-high').text();　//添加会议时间结束字段
            delete field.selectedtext; //删除对象属性
            delete field.imagelabels;
            delete field.newtext;
            delete field.existedtext;
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
                    console.log(data)
                    layer.msg('登录过期！', {
                        anim: 6
                    }, function () {
                        location.href = data.responseText;
                    })
                }
            })

            return false; //不刷新页面
        });

        //监听参会人员下拉选择
        form.on('select(check)', function (data) {
            var labelId = $("#meetingMember").val();
            var labelName = $("#meetingMember option:selected").text();
            addLabelInterface(labelId, labelName);

        });

        /*============添加表单监听：end==============*/

        //执行一个laydate实例
        laydate.render({
            elem: '#searchApplyDate', //指定元素
            type: 'date',
            min: getDateTime('date')
        });
        laydate.render({
            elem: '#date', //指定元素
            type: 'date',
            min: getDateTime('date')
        });


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
        } else {
            return h + ':' + m + ':' + s;
        }

    }

</script>
</body>
</html>