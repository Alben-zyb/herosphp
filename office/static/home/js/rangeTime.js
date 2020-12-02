//创建时间轴
function createTimeAxis(detailArr) {
    var td = $('<td style="float: left;"></td>');
    //创建时间轴容器
    var outer = $('<div class="outer"></div>');
    var timeAxis = $('<div class="timeAxis"></div>');
    var container_t = $('<div class="container"></div>'); //时间段块容器
    var directRix_t = $('<div class="directRix"><ul><li>8:00</li><li>12:00</li><li>16:00</li><li>20:00</li><li>24:00</li></ul></div>'); //时间轴

    //循环添加时间段块
    $.each(detailArr, function (index, detail) {
        if (detail.time == '-') {
            //会议室在当天没有被预约
            return;
        }
        container_t = setTimePiece(detail, container_t); //设置时间块
    });
    timeAxis.append(container_t);
    timeAxis.append(directRix_t);
    outer.append(timeAxis);
    td.append(outer);
    return td;
}

//设置时间块
function setTimePiece(timeDetail, container) {
    var time = timeDetail.time;
    var status = timeDetail.status;
    var timeArr = time.split('-');  //分割时间段（原始数据格式：09:20:00-10:00:00）
    var start = timeArr[0].substr(0, 5);  //提取时：分（09:20）
    var finish = timeArr[1].substr(0, 5);
    var startArr = start.split(':');
    var finishArr = finish.split(':');
    var s_hour = startArr[0];
    var s_min = startArr[1];
    var s_p = (s_hour - 8) * 60 + s_min * 1; //转换为分钟计算

    var f_hour = finishArr[0];
    var f_min = finishArr[1];
    var f_p = (f_hour - 8) * 60 + f_min * 1;

    var left = (s_p / (16 * 60)) * 625 * 0.8; //计算开始距离原点距离
    var length = ((f_p - s_p) / (16 * 60)) * 625 * 0.8; //计算时间块长度

    var statusText = ''; //状态提示
    var bgcolor = ''; //背景颜色
    switch (status) {
        case '0':
            statusText = '申请中';
            bgcolor = '#ff934a';
            break;
        case '1':
            statusText = '申请成功';
            bgcolor = '#2c8af5';
            break;
        case '2':
            statusText = '正在使用';
            bgcolor = '#5FB878';
            break;
        case '3':
            statusText = '已使用';
            bgcolor = '#6b6b6b';
            break;
        case '4':
            statusText = '申请过期';
            bgcolor = '#858af5';
            break;
        case '5':
            statusText = '申请关闭';
            bgcolor = '#f52e21';
            break;
    }

    //构建div时间块显示
    var timeLength = $('<div class="timeLength">\n' +
        '            <div class="pieces" style="background-color: '+bgcolor+'">\n' +
        '                <span style="color: '+bgcolor+'"></span>\n' +
        '            </div>\n' +
        '        </div>')
    timeLength.css('margin-left', left + 'px');
    timeLength.css('width', length + 'px');



    timeLength.find('span').text(statusText+': '+start + '~' + finish);
    container.append(timeLength); //添加时间块到容器
    return container; //返回容器
}


//添加时间悬浮监听
$(function () {
    $("body").on("mouseenter", ".pieces", function () {
        $(this).find("span").show();
    });
    $("body").on("mouseleave", ".pieces", function () {
        $(this).find("span").hide();
    });
})