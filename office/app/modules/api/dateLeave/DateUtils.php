<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-11 下午3:08
 * @function  时间处理工作类
 */

namespace app\api\dateLeave;


class DateUtils {

    //属性

    /**
     * 定义常见的时间格式
     */
    private static $dateFormat = [
        "Y-m-d H:i:s",                  //"yyyy-MM-dd HH:mm:ss",        // 0
        "Y/m/d H:i:s",                  //"yyyy/MM/dd HH:mm:ss",        // 1
        "Y年m月d日H时i分s秒",             //"yyyy年MM月dd日HH时mm分ss秒",  // 2
        "Y-m-d",                        // "yyyy-MM-dd",                // 3
        "Y/m/d",                        //"yyyy/MM/dd",                 // 4
        "y-m-d",                        //"yy-MM-dd",                   // 5
        "y/m/d",                        //"yy/MM/dd",                   // 6
        "Y年m月d日",                     //"yyyy年MM月dd日",              // 7
        "H:i:s",                        //"HH:mm:ss",                   // 8
        "YmdHis",                       //"yyyyMMddHHmmss",             // 9
        "Ymd",                          //"yyyyMMdd",                   // 10
        "Y.m.d",                        //"yyyy.MM.dd",                 // 11
        "y.m.d",                        //"yy.MM.dd",                   // 12
        "m月d日H时i分",                  //"MM月dd日HH时mm分",            // 13
        "Y年m月d日 H:i:s",               //"yyyy年MM月dd日 HH:mm:ss",     // 14
        "Y-m-d H:i",                    //"yyyy-MM-dd HH:mm",           // 15
        "Ymd",                          //"yyMMdd"                      // 16
    ];

    //方法

    /**
     * 去重
     *
     * @param array $str
     * @return array
     */
    public static function removal(array $str) {
        array_unique($str);
        return $str;
    }

    /**
     * 日期时间转相应格式日期
     * @param $date
     * @param $format
     * @return false|string
     */
    public static function dateFormat($date,$format){
       return date(self::$dateFormat[$format], strtotime($date));
    }

    /**
     * 获取两个日期之间的所有日期，去掉周末
     * @param String $startDate
     * @param String $endDate
     * @return array
     */
    public static function getDates(String $startDate, String $endDate) {
        $result = Array();
        $startDay = self::dateFormat($startDate,3); //转化成指定格式的日期格式
        $endDay = self::dateFormat($endDate,3);


        while ($startDay < $endDay) {
            $week = date('w', strtotime($startDay));
            if ($week != "6" && $week != "0") {

                $result[] = $startDay;
            }
            $startDay = date(self::$dateFormat[3], strtotime("+1 day", strtotime($startDay)));
        }
        // 验证结束日期是否是周六周日
        $week = date('w', strtotime($endDay));
        if ($week != "6" && $week != "0") {
            $result[] = $endDay;
        }

        return $result;
    }

    /**
     * 获取法定节假日或者调休
     * @param int $num
     * @return array
     */
    public static function holiday(int $num) {
        return $num == 2 ? MyData::$holiday2 : MyData::$holiday1;
    }

    /**
     * 获取不同部门工作时间
     * @param int $num
     * @return array
     */
    public static function workTime(int $num) {
        return $num == 2 ? MyData::$workTime2 : MyData::$workTime1;
    }


}