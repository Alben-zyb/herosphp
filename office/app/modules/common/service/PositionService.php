<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-06-28
 */

namespace app\common\service;


use app\common\dao\PositionDao;
use herosphp\model\CommonService;
use herosphp\core\Loader;
use herosphp\utils\JsonResult;
use herosphp\filter\Filter;

class PositionService extends CommonService {

    protected $modelClassName = PositionDao::class;

    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $searchCondition
     * @return JsonResult
     */
    public function getPageData($searchCondition = array()) {
        //获取条件参数（分页，查询条件等）
        $page = $searchCondition['page'];//页码
        $limit = $searchCondition['limit'];//每页条数
        $departmentId = $searchCondition['departmentId'];//部门自增id
        $positionNo = $searchCondition['positionNo'];//岗位编号
        $positionName = $searchCondition['positionName'];//岗位名称
        $timeType = $searchCondition['timeType'];//时间类型：创建|更新
        $start = $searchCondition['start'];//开始时间
        $end = $searchCondition['end'];//结束时间
        $min = $searchCondition['memberMin'];//岗位人数>
        $max = $searchCondition['memberMax'];//岗位人数<

        //数据过滤规则
        $filterNo = array(
            'No' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "岗位编号为字母、数字")
            ));
        $filterName = array(
            'name' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "岗位名称为字母、中文")
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
        $searchNo = array(
            'No' => $positionNo
        );
        $searchName = array(
            'name' => $positionName
        );
        $searchMin = array(
            'min' => $min,
        );
        $searchMax = array(

            'max' => $max,
        );

        $searchCondition = array();//查询的最终条件
        //部门id条件
        if ($departmentId) {
            $searchCondition[] = array('a.departmentId', '=', $departmentId);
        }

        //岗位编号条件过滤添加
        if ($result = Filter::loadFromModel($searchNo, $filterNo, $error)) {
            $positionNoPass = $result['No'];
            $searchCondition[] = array('a.positionNo', 'LIKE', $positionNoPass . '%');
        }

        //岗位名称条件过滤添加
        if ($result = Filter::loadFromModel($searchName, $filterName, $error)) {
            $positionNamePass = $result['name'];
            $searchCondition[] = array('a.positionName', 'LIKE', $positionNamePass . '%');
        }

        //岗位最小人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMin, $filterMin, $error)) {
            $minPass = $result['min'];
            $searchCondition[] = array('a.members', '>=', $minPass);
        }

        //岗位最大人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMax, $filterMax, $error)) {
            $maxPass = $result['max'];
            if ($maxPass != 0) {
                $searchCondition[] = array('a.members', '<', $maxPass);
            }

        }

        //时间条件，配合时间类型：添加｜更新　使用
        if ($start) {
            $searchCondition[] = array('a.' . $timeType, '>', $start);

        }
        if ($end) {
            $searchCondition[] = array('a.' . $timeType, '<', $end);
        }

        $count = $this->alias('a')->whereArr($searchCondition)->count();//获取数据总条数（加入查询条件）

        if ($count < $limit) {
            $page = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }

        //使用连接查询，查找部门名称
        $data = $this->alias('a')
            ->fields('a.id, a.departmentId,b.departmentName, a.positionNo,a.positionName, a.members,a.operator,a.create_time,a.update_time')
            ->join('department b', MYSQL_JOIN_LEFT)
            ->on('a.departmentId = b.id')
            ->whereArr($searchCondition)
            ->page($page, $limit)
            ->find();
        return JsonResult::layTable($data, $count);//返回jsonResult对象
    }

    /**
     * 查询指定字段数据
     * @param $field
     * @return array
     */
    public function getFieldData($field = array()) {
        return $this->fields($field)->find();
    }

    /**
     * 根据部门id获取岗位信息
     * @param $departmentId
     * @return JsonResult
     */
    public function getPositionByDepartment($departmentId) {
        $searchCondition = [
            'departmentId', '=', $departmentId
        ];
        if ($departmentId == 0 || $departmentId == '') {
            //如果提供的部门id为空，将查询条件设为空，查询全部岗位
            $searchCondition = '';
        }
        $data = $this->fields('id,positionName')->where($searchCondition)->find();

        return JsonResult::success('获取成功！', $data);
    }
}