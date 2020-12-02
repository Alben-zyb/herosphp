<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-23 下午1:16
 * @function  会议室预约服务类
 */

namespace app\home\service;


use app\admin\service\IdentifyService;
use app\room\dao\ApplyDao;
use app\room\dao\MeetingMemberDao;
use app\room\service\ApplyService;
use app\room\service\EntityService;
use herosphp\core\Loader;
use herosphp\filter\Filter;
use herosphp\session\Session;
use herosphp\utils\JsonResult;

class RoomService extends EntityService {

    /**
     * 根据查询条件查询数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getRoomDetail($search = array()) {

        //数据过滤规则

        $filterRoomId = array(
            'roomId' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室id数据类型有误！")
            ));
        $filterDevice = array(
            'device' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "设备情况描述数据类型有误！")
            ));
        $filterMin = array(
            'min' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));
        $filterMax = array(
            'max' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));

        //前端输入的查询条件
        $searchRoomId = array(
            'roomId' => $search['roomId']
        );

        $searchDevice = array(
            'device' => $search['device']
        );

        $searchMin = array(
            'min' => $search['min'],
        );
        $searchMax = array(

            'max' => $search['max'],
        );
        $searchCondition = array();//查询的最终条件


        $searchCondition[] = array('a.status', '=', 1); //会议室可以使用状态条件
        //会议室id条件过滤添加
        if ($result = Filter::loadFromModel($searchRoomId, $filterRoomId, $error)) {
            $searchCondition[] = array('a.Id', '=', $result['roomId']);
        }

        //设备情况描述条件过滤添加
        if ($result = Filter::loadFromModel($searchDevice, $filterDevice, $error)) {
            $searchCondition[] = array('a.device', 'LIKE', '%' . $result['device'] . '%');
        }

        //最小容纳人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMin, $filterMin, $error)) {
            $minPass = $result['min'];
            $searchCondition[] = array('a.capacity', '>=', $minPass);
        }

        //最大容纳人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMax, $filterMax, $error)) {
            $maxPass = $result['max'];
            if ($maxPass != 0) {
                $searchCondition[] = array('a.capacity', '<', $maxPass);
            }

        }

        $applyService = new ApplyService(); //调用会议室预约服务类
        $applyService->updateBaseOnTime(); //根据当前时间更新预约状态

        //不添加日期条件
        //left join
        $dataLeft = $this->alias('a')
            ->fields('a.id,a.roomNo,a.roomName,a.device,a.capacity,b.date,b.status,b.start,b.finish')
            ->join('roomApply b', MYSQL_JOIN_LEFT)
            ->on('a.id=b.roomId')
            ->whereArr($searchCondition)
            ->order('b.date')
            ->find();//查询数据
        //inner join
        $dataInner = $this->alias('a')
            ->fields('a.id,a.roomNo,a.roomName,a.device,a.capacity,b.date,b.status,b.start,b.finish')
            ->join('roomApply b', MYSQL_JOIN_INNER)
            ->on('a.id=b.roomId')
            ->whereArr($searchCondition)
            ->order('b.date')
            ->find();//查询数据

        $notApplyData = array();

        //找出还没有预约过的会议室数据(没有历史预约的会议室)，即left join为null的room数据
        foreach ($dataLeft as $key => $value) {
            if (!in_array($value, $dataInner)) {
                $notApplyData[] = $value;
            }
        }

        //1.有指定日期条件
        if ($search['date']) {

            //添加日期条件(是条件当天)
            $withDateData1 = $this->alias('a')
                ->fields('a.id,a.roomNo,a.roomName,a.device,a.capacity,b.date,b.status,b.start,b.finish')
                ->join('roomApply b', MYSQL_JOIN_LEFT)
                ->on('a.id=b.roomId')
                ->whereArr($searchCondition)
                ->where('b.date', '=', $search['date'])
                ->order('b.date')
                ->find();//查询数据

            //添加日期条件(不是条件当天)
            $withDateData2 = $this->alias('a')
                ->fields('a.id,a.roomNo,a.roomName,a.device,a.capacity')
                ->join('roomApply b', MYSQL_JOIN_LEFT)
                ->on('a.id=b.roomId')
                ->whereArr($searchCondition)
                ->where('b.date', '!=', $search['date'])
                ->order('b.date')
                ->find();//查询数据

            //合并数组:在当天有预约的会议室信息、在当天没有预约的会议室信息、没有预约过的会议室信息
            $withDateDataMerge = array_merge($withDateData1, $withDateData2, $notApplyData);

            //所有数据行添加date日期字段=$search['date']
            foreach ($withDateDataMerge as &$item) {
                $item['date'] = $search['date'];
            }

            //合并数组:预约日期大于当前日期的会议室预约信息、会议室实体信息(防止遗漏历史有预约而将来没预约的会议室)、没有预约过的会议室信息
            $withDateData = $this->dataCombine($withDateDataMerge, true);

            return JsonResult::layTable($this->arraySort($withDateData, 'date'), $withDateDataMerge); //返回JsonResult对象数据

        }

        //2.无指定日期条件

        //会议室实体信息
        $roomData = $this->alias('a')
            ->fields('a.id,a.roomNo,a.roomName,a.device,a.capacity')
            ->whereArr($searchCondition)
            ->find();//查询数据
        //当前时间之后的日期才有意义
        $date = date('Y-m-d'); //当前日期
        $searchCondition[] = array('b.date', '>=', $date); //添加日期

        //添加大于当前日期条件获取的数据
        $overDateData = $this->alias('a')
            ->fields('a.id,a.roomNo,a.roomName,a.device,a.capacity,b.date,b.status,b.start,b.finish')
            ->join('roomApply b', MYSQL_JOIN_LEFT)
            ->on('a.id=b.roomId')
            ->whereArr($searchCondition)
            ->order('b.date')
            ->find();//查询数据


        //合并数组:预约日期大于当前日期的会议室预约信息、会议室实体信息(防止遗漏历史有预约而将来没预约的会议室)、没有预约过的会议室信息
        $withoutDateData = array_merge($overDateData, $roomData, $notApplyData);

        //优化合并处理数据(以"会议室id,日期"为主键合并数据,预约时间段组合成一维数组,添加到主键数据中的新字段detail中)
        $combineData = $this->dataCombine($withoutDateData, false);

        return JsonResult::layTable($this->arraySort($combineData, 'date'), $withoutDateData); //返回JsonResult对象数据
    }

    /**
     * 添加
     * @param $data
     * @return JsonResult
     */
    public function addSubmit($data) {

        $roomApply = Loader::model(ApplyDao::class); //调用预约Dao类，连接数据库

        //添加验证规则
        $filterMap = array(
            'roomId' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "未选中会议室添加")),
            'theme' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "会议主题不能为空", "length" => "会议主题长度为1~30字符")),
            'date' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "会议日期不能为空")),
            'start' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "会议开始时间不能为空")),
            'finish' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "会议结束时间不能为空")),
        );

        //验证过滤
        $result = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$result) {
            return JsonResult::fail('数据类型有误！', $error);// 返回JsonResult对象数据
        }
        $datetime = date('Y-m-d H:i:s'); //当前日期+时间
        $date = substr($datetime, 0, 10);  //截取当前日期
        $time = substr($datetime, 11);  //截取当前时间

        //控制预约时间只能是未来时间
        if ($result['date'] <= $date && $result['start'] < $time) {
            return JsonResult::fail('预约失败,时间已过期！', $error);// 返回JsonResult对象数据
        }
        //验证通过
        //查询会议室预约时间有无冲突
        //预约时间时间“右”相交旧时间或旧时间“包含”预约时间
        $checkData1 = [
            ['roomId', '=', $result['roomId']],
            ['date', '=', $result['date']],
            ['status', 'IN', ['0', '1', '2']],
            ['start', '<=', $result['start']],
            ['finish', '>', $result['start']],
        ];
        //预约时间时间“左”相交旧时间或旧时间“包含”预约时间
        $checkData2 = [
            ['roomId', '=', $result['roomId']],
            ['date', '=', $result['date']],
            ['status', 'IN', ['0', '1', '2']],
            ['start', '<', $result['finish']],
            ['finish', '>=', $result['finish']],
        ];
        //预约时间“包含”旧时间
        $checkData3 = [
            ['roomId', '=', $result['roomId']],
            ['date', '=', $result['date']],
            ['status', 'IN', ['0', '1', '2']],
            ['start', '>=', $result['start']],
            ['finish', '<=', $result['finish']],
        ];

        //使用闭包查询
        $result = $roomApply->where(1, 1)
            ->where(function () use ($roomApply, $checkData1) {
                $roomApply->whereArr($checkData1);
            })
            ->whereOr(function () use ($roomApply, $checkData2) {
                $roomApply->whereArr($checkData2);
            })
            ->whereOr(function () use ($roomApply, $checkData3) {
                $roomApply->whereArr($checkData3);
            })
            ->findOne();
        //时间有冲突
        if ($result) {
            return JsonResult::fail('预约失败，时间有冲突', $result); //返回JsonResult对象数据
        }

        //时间无冲突，添加预约申请
        //添加会议室预约（成功返回自增id）
        //添加申请人字段
        $user = IdentifyService::getUser();
        if (!$user) {
            die('/admin/identify/index');
        }
        $data['applicant'] = $user['id'];

        $roomApply->beginTransaction(); //开启事务
        $successId = $roomApply->add($data); //成功返回插入的自增id

        //添加预约记录失败
        if (!is_numeric($successId)) {
            return JsonResult::fail('预约申请失败！', $successId);// 返回JsonResult对象数据
        }
        //添加预约记录成功,添加参会人员并关联到对于会议
        $meetingMember = Loader::model(MeetingMemberDao::class); //调用参会人员数据模型
        $meetingMember->beginTransaction(); //开启事务
        //循环添加
        foreach ($data['meetingMember'] as $Uid) {
            $member = [
                'roomApplyId' => $successId,
                'userId' => $Uid,
            ];
            $result = $meetingMember->add($member); //添加参会人员
            if (!$result) {
                $meetingMember->rollback(); //操作失败,回滚
                $roomApply->rollback(); //操作失败,回滚
                return JsonResult::fail('预约申请失败！', $result);// 返回JsonResult对象数据
                break; //退出循环
            }
        }

        $meetingMember->commit(); //提交事务
        $roomApply->commit(); //提交事务
        return JsonResult::success('预约申请成功！', $successId); //返回JsonResult对象数据
    }


    /**
     * 将具有相同roomId和date的数据进行合并
     * 预约时间段组合成一维数组,添加到主键数据中的新字段detail中
     * @param array $dataArr
     * @param bool $withDate
     * @return array
     */
    protected function dataCombine(array $dataArr, $withDate = true) {
        $firstArr = array();
        //第一轮循环，将会议室数据和时间数据整合
        //具有相同会议室id和相同日期date的数据整合为一条数据（二维数组，value值存放会议室信息，time（一维数组）存放申请时段信息）
        foreach ($dataArr as $item) {
            $key = $item['id'] . '-' . $item['date'];

            if (in_array($item['status'], ['0', '1', '2'])) {
                //添加状态条件过滤,有意义的状态是(0:申请中,1:申请通过,2:正在使用)
                $detail['status'] = $item['status']; //detail字段存放预约的时间和状态
                $detail['time'] = $item['start'] . "-" . $item['finish'];
                $firstArr[$key]['detail'][] = $detail;
            } else {
                if (!$withDate) {
                    $item['date'] = '';
                    $key = $item['id'] . '-' . $item['date'];
                }
            }
            //其他状态下的会议室,不添加detail字段

            $firstArr[$key]['value'] = $item;

        }
        $secondArr = array();
        //第二轮循环，将会议室value和时间time整合（一维数组）
        foreach ($firstArr as $item) {
            $value = $item['value'];
            unset($value['status']); //去除多余数据
            unset($value['start']); //去除多余数据
            unset($value['finish']);//去除多余数据
            $value['detail'] = $item['detail'];  //可能不存在detail字段,值为null
            $secondArr[] = $value;
        }
        return $secondArr;
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys 要排序的键字段
     * @param int $sort 排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    function arraySort($array, $keys, $sort = SORT_ASC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
}