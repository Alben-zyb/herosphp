<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-11 下午4:20
 * @function
 */

namespace app\api\dateLeave;


class LeaveManage {

    protected $manage;

    public function __construct() {
        $this->manage = new self();
    }

    // 判断时间大小
    public function pdTime(String $startTime, String $endTime, int $login) {

        if ($startTime > $endTime) {

            return $this->calculationTime($endTime, $startTime, $login);
        } else
            if ($startTime < $endTime) {
                return $this->calculationTime($startTime, $endTime, $login);
            } else {
                return 0;
            }
    }

    public static function calculationTime(String $startTime, String $endTime, int $login=1) {
        // 获取startTime和endTime之间的所有日期，去掉周六周日
        $list = DateUtils::getDates($startTime, $endTime);
        // 获取法定节假日
        $fdList = DateUtils:: holiday(1);
        // 获取调休
        $txList = DateUtils:: holiday(2);
        // 上班时间
        $workTime = DateUtils::workTime($login);

        // 删除时间区间中的所有法定节假日
        $list = self::remove($list, $fdList);

        $st = substr($startTime, 0, 10);
        $et = substr($endTime, 0, 10);
        foreach ($txList as $s) {
            if ($s >= $st && $s <= $et) {
                // 添加时间区间中的所有调休日期
                $list[] = $s;
            }
        }
        // 去重
        $list = DateUtils:: removal($list);

        // 开始当天上午上班时间、上午下班时间、下午上班时间、下午下班时间
        $amWorkYes = substr($startTime, 0, 11) . $workTime[0];
        $amWorkNo = substr($startTime, 0, 11) . $workTime[1];
        $pmWorkYes = substr($startTime, 0, 11) . $workTime[2];
        $pmWorkNo = substr($startTime, 0, 11) . $workTime[3];

        // 结束当天上午上班时间、上午下班时间、下午上班时间、下午下班时间
        $amWorkYesEnd = substr($endTime, 0, 11) . $workTime[0];
        $amWorkNoEnd = substr($endTime, 0, 11) . $workTime[1];
        $pmWorkYesEnd = substr($endTime, 0, 11) . $workTime[2];
        $pmWorkNoEnd = substr($endTime, 0, 11) . $workTime[3];

        $time = 0;

        if (sizeof($list) == 0) {
            // 申请日期是法定节假日
            return $time;
        } else if (sizeof($list) == 1) {
            // 请假一天
            if ($startTime > $pmWorkNo) {
                return $time;
            }
            if ($endTime < $amWorkYes) {
                return $time;
            }
            if ($startTime >= $amWorkNo && $endTime <= $pmWorkYes) {
                return $time;
            }
            if ($startTime < $amWorkYes) {
                $startTime = $amWorkYes;
            }
            if ($endTime > $pmWorkNo) {
                $endTime = $pmWorkNo;
            }
            if ($startTime >= $amWorkNo && $startTime <= $pmWorkYes) {
                $startTime = $pmWorkYes;
            }
            if ($endTime >= $amWorkNo && $endTime <= $pmWorkYes) {
                $endTime = $amWorkNo;
            }
            $start = DateUtils::dateFormat($startTime, 15); // 0或者15
            $end = DateUtils::dateFormat($endTime, 15);

            // 三种情况，1：请假时间全在上午，2：请假时间全在下午，3：包含午休时间
            if ($startTime >= $amWorkYes && $endTime <= $amWorkNo) {
                $minute = (strtotime($end) - strtotime($start)) / 60;
                $time = $minute / (8 * 60);
            } else if ($startTime >= $pmWorkYes && $endTime <= $pmWorkNo) {
                $minute = (strtotime($end) - strtotime($start)) / 60;
                $time = $minute / (8 * 60);
            } else if ($startTime < $amWorkNo && $endTime > $pmWorkYes) {
                $minute = (strtotime($end) - strtotime($start)) / 60 - (strtotime($pmWorkYes) - strtotime($amWorkNo))/ 60;
                $time = $minute / (8 * 60);
            }
            return round($time,2); //返回请假天数（四舍五入保留2位小数）
        } else {
            // 处理请假多天的情况
            // 申请开始时间处理
            if (in_array($st, $list)) {
                //小于等于早上开始工作时间
                if ($startTime <= $amWorkYes) {
                    $startTime = $amWorkYes;
                }
                //大于等于下午结束工作时间
                if ($startTime >= $pmWorkNo) {
                    $startTime = $pmWorkNo;
                }
                //在午休之间
                if ($startTime >= $amWorkNo && $startTime <= $pmWorkYes) {
                    $startTime = $pmWorkYes;
                }
                $start = DateUtils::dateFormat($startTime, 15); // 0或者15
                $end = DateUtils::dateFormat($pmWorkNo, 15);
                if ($startTime < $amWorkNo) {
                    // 减去中午一小时
                    $t = (strtotime($end) - strtotime($start)) / 60 - (strtotime($pmWorkYes) - strtotime($amWorkNo)) / 60;
                    $time = $time + $t / (8 * 60);
                } else {
                    $t = (strtotime($end) - strtotime($start)) / 60;
                    $time = $time + $t / (8 * 60);
                }
                $list = self::remove($list, [$st]);
            }
            // 申请结束时间处理
            if (in_array($et, $list)) {
                //小于等于早上开始工作时间
                if ($endTime <= $amWorkYesEnd) {
                    $endTime = $amWorkYesEnd;
                }
                //大于等于下午工作结束时间
                if ($endTime >= $pmWorkNoEnd) {
                    $endTime = $pmWorkNoEnd;
                }

                //在午休时间
                if ($endTime >= $amWorkNoEnd && $endTime <= $pmWorkYesEnd) {
                    $endTime = $amWorkNoEnd;
                }

                $end = DateUtils::dateFormat($endTime, 15);// 0或者15
                $start = DateUtils::dateFormat($amWorkYesEnd, 15);
                if ($endTime > $pmWorkYesEnd) {
                    $t = (strtotime($end) - strtotime($start)) / 60 - (strtotime($pmWorkYes) - strtotime($amWorkNo)) /60;
                    $time = $time + $t / (8 * 60);
                } else {
                    $t = (strtotime($end) - strtotime($start)) / 60;
                    $time = $time + $t / (8 * 60);
                }
                $list = self::remove($list, [$et]);
            }
            // 天数计算集合中剩下的个数就可以
            $time = $time + sizeof($list);
            return round($time,2); //返回请假天数（四舍五入保留2位小数）
        }
    }

    /**
     * @param array $haystack
     * @param array $needle
     * @return array
     */
    private static function remove($haystack, $needle) {
        strpos('', '');
        $diff = [];
        foreach ($haystack as $val) {
            if (!in_array($val, $needle)) {
                $diff[] = $val;
            }
        }
        return $diff;
    }
}