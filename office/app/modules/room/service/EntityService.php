<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-15 下午7:09
 * @function
 */

namespace app\room\service;


use herosphp\model\CommonService;
use app\room\dao\EntityDao;
use herosphp\filter\Filter;
use herosphp\session\Session;
use herosphp\utils\JsonResult;

class EntityService extends CommonService {

    //属性
    protected $modelClassName = EntityDao::class;

    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getPageData($search = array()) {

        //数据过滤规则

        $filterRoomNo = array(
            'roomNo' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室编号数据类型有误！")
            ));
        $filterRoomName = array(
            'roomName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室名称数据类型有误！")
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
        $searchRoomNo = array(
            'roomNo' => $search['roomNo']
        );
        $searchRoomName = array(
            'roomName' => $search['roomName']
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


        //会议室编号条件过滤添加
        if ($result = Filter::loadFromModel($searchRoomNo, $filterRoomNo, $error)) {
            $searchCondition[] = array('roomNo', 'LIKE', '%' . $result['roomNo'] . '%');
        }
        //会议室名称条件过滤添加
        if ($result = Filter::loadFromModel($searchRoomName, $filterRoomName, $error)) {
            $searchCondition[] = array('roomName', 'LIKE', '%' . $result['roomName'] . '%');
        }
        //设备情况描述条件过滤添加
        if ($result = Filter::loadFromModel($searchDevice, $filterDevice, $error)) {
            $searchCondition[] = array('device', 'LIKE', '%' . $result['device'] . '%');
        }

        //最小容纳人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMin, $filterMin, $error)) {
            $minPass = $result['min'];
            $searchCondition[] = array('capacity', '>=', $minPass);
        }

        //最大容纳人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMax, $filterMax, $error)) {
            $maxPass = $result['max'];
            if ($maxPass != 0) {
                $searchCondition[] = array('capacity', '<', $maxPass);
            }

        }

        //时间条件，配合时间类型：添加｜更新　使用
        if ($search['start']) {
            $searchCondition[] = array($search['timeType'], '>', $search['start']);

        }
        if ($search['end']) {
            $searchCondition[] = array($search['timeType'], '<', $search['end']);
        }
        $count = $this->whereArr($searchCondition)->count();//获取数据总条数（加入查询条件）

        if ($count < $search['limit']) {
            $search['page'] = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }

        $data = $this->whereArr($searchCondition)->page($search['page'], $search['limit'])->find();//查询数据


        return JsonResult::layTable($data,$count); //返回JsonResult对象数据
    }

    /**
     * 添加数据
     * @param $data
     * @return array
     */
    public function addData($data) {

        //数据过滤
        $filter = array(
            'roomNo' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室编号数据类型有误！")),
            'roomName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室名称据类型有误！")),
            'device' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室设备数据类型有误！")),
            'capacity' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室容纳人数数据类型有误！"))
        );

        $check = array(
            'roomNo' => $data['roomNo'],
            'roomName' => $data['roomName'],
            'device' => $data['device'],
            'capacity' => $data['capacity'],
        );

        //检验数据
        $addData = Filter::loadFromModel($check, $filter, $error);
        //校验失败
        if (!$addData) {
            return array('code' => '001', 'msg' => '数据验证失败');
        }

        //查找是否已存在该会议室(编号、名称)
        $result = $this->where('roomNo', $addData['roomNo'])
            ->whereOr('roomName', '=', $addData['roomName'])
            ->find();
        if ($result) {
            return array('code' => '001', 'msg' => '该会议室已存在');
        }
        //校验通过
        //增加操作者信息
        $addData['operator'] = $_SESSION['user']['username'];
        $addData['operatorId'] = $_SESSION['user']['id'];
        //添加时间字段
        $addData['create_time'] = date('Y-m-d H:i');
        $addData['update_time'] = date('Y-m-d H:i');
        //将会议室编号字母转成大写
        $addData['roomNo'] = strtoupper($addData['roomNo']);
        $result = $this->add($addData);
        if (!$result) {
            return array('code' => '001', 'msg' => '数据库出错');
        }
        return array('code' => '000', 'msg' => '添加成功');
    }

    /**
     * 修改数据
     * @param $data
     * @return array
     */
    public function editData($data) {

        //数据过滤
        $filter = array(
            'id' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室id数据类型有误！")),
            'roomNo' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室编号数据类型有误！")),
            'roomName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室名称据类型有误！")),
            'device' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室设备数据类型有误！")),
            'capacity' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室容纳人数数据类型有误！"))
        );

        $check = array(
            'id' => $data['id'],
            'roomNo' => $data['roomNo'],
            'roomName' => $data['roomName'],
            'device' => $data['device'],
            'capacity' => $data['capacity'],
        );

        $id = $data['id'];
        //检验数据
        $editData = Filter::loadFromModel($check, $filter, $error);
        //校验失败
        if (!$editData) {
            return array('code' => '001', 'msg' => '数据验证失败');
        }

        //查找是否已存在该会议室(编号、名称)
        $result = $this->whereArr(
            array(
                ['roomNo', '=', $editData['roomNo']],
                ['id', '!=', $editData['id']]))
            ->whereOr('roomName', '=', $editData['roomName'])
            ->where('id', '!=', $editData['id'])
            ->find();
        if ($result) {
            return array('code' => '001', 'msg' => '该会议室已存在');
        }
        //校验通过
        //增加操作者信息
        Session::start(); //开启session
        $editData['operator'] = $_SESSION['user']['username'];
        $editData['operatorId'] = $_SESSION['user']['id'];
        //添加时间字段
        $editData['update_time'] = date('Y-m-d H:i');
        //将会议室编号字母转成大写
        $editData['roomNo'] = strtoupper($editData['roomNo']);
        unset($editData['id']);//移除id字段
        $result = $this->update($editData, $id);
        if (!$result) {
            return array('code' => '001', 'msg' => '数据库出错');
        }
        return array('code' => '000', 'msg' => '修改成功');
    }

}