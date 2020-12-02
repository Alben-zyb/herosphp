<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-04
 */

namespace app\common\service;


use herosphp\model\CommonService;
use app\common\dao\PermissionDao;
use herosphp\filter\Filter;
use herosphp\utils\JsonResult;


class PermissionService extends CommonService {

    //属性
    protected $modelClassName = PermissionDao::class;


    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getPageData($search = array()) {

        //数据过滤规则

        $filterPermissionName = array(
            'permissionName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "权限名数据类型有误！")
            ));

        $filterModule = array(
            'module' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "模块名数据类型有误！")
            ));
        $filterRule = array(
            'method' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "权限规则数据类型有误！")
            ));

        //前端输入的查询条件
        $searchPermissionName = array(
            'permissionName' => $search['permissionName']
        );
        $searchModule = array(
            'module' => $search['module']
        );
        $searchRule = array(
            'method' => $search['method']
        );

        $searchCondition = array();//查询的最终条件


        //权限名条件过滤
        if ($result = Filter::loadFromModel($searchPermissionName, $filterPermissionName, $error)) {
            $searchCondition[] = array('permissionName', 'LIKE', '%' . $result['permissionName'] . '%');
        }
        //模块名条件过滤
        if ($result = Filter::loadFromModel($searchModule, $filterModule, $error)) {
            $searchCondition[] = array('module', '=', $result['module']);
        }
        //规则条件过滤
        if ($result = Filter::loadFromModel($searchRule, $filterRule, $error)) {
            $searchCondition[] = array('method', 'LIKE', '%' . $result['method'] . '%');
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

        return JsonResult::layTable($data,$count); //返回jsonResult对象

    }

    /**
     * 添加数据
     * @param $data
     * @return bool|mixed
     */
    public function addData($data) {

        //数据过滤
        $filter = array(
            'module' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "模块名数据类型有误！")),
            'method' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "方法名数据类型有误！")),
            'permissionName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "权限名数据类型有误！"))
        );

        $check = array(
            'module' => $data['module'],
            'method' => $data['method'],
            'permissionName' => $data['permissionName'],
        );

        //检验数据
        $addData = Filter::loadFromModel($check, $filter, $error);
        //校验失败
        if (!$addData) {
            return false;
        }
        //校验通过
        //添加时间字段
        $addData['create_time'] = date('Y-m-d H:i');
        $addData['update_time'] = date('Y-m-d H:i');
        $result = $this->add($addData);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function deleteData($ids) {
        $result = $this->where('id', 'IN', $ids)->deletes();//删除
        //删除失败
        if (!$result) {
            return false;
        }
        //删除成功
        return $result;
    }
}