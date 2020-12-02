<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-03
 */

namespace app\common\service;


use herosphp\model\CommonService;
use app\common\dao\RoleDao;
use herosphp\filter\Filter;
use app\common\dao\RolePermissionDao;
use herosphp\core\Loader;
use herosphp\utils\JsonResult;


class RoleService extends CommonService {

    //属性
    protected $modelClassName = RoleDao::class;


    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getPageData($search = array()) {

        //数据过滤规则

        $filterRoleName = array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
            array("type" => "角色名称数据类型有误！")
        );
        $filterDetail = array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
            array("type" => "角色描述数据类型有误！")
        );

        //前端输入的查询条件
        $searchRoleName = array(
            'roleName' => $search['roleName']
        );
        $searchDetail = array(
            'detail' => $search['detail']
        );

        $searchCondition = array();//查询的最终条件


        //角色状态条件
        if (is_numeric($search['status']) && $search['status'] != -1) {
            $searchCondition[] = array('status', '=', $search['status']);
        }


        //角色名称条件过滤添加
        if ($result = Filter::loadFromModel($searchRoleName, $filterRoleName, $error)) {
            $searchCondition[] = array('roleName', 'LIKE', '%' . $result['roleName'] . '%');
        }
        //角色描述条件过滤添加
        if ($result = Filter::loadFromModel($searchDetail, $filterDetail, $error)) {
            $searchCondition[] = array('detail', 'LIKE', '%' . $result['detail'] . '%');
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

        //整理数据
        foreach ($data as &$position) {
            $position['create_time']=substr( $position['create_time'],0,16);
            $position['update_time']=substr( $position['update_time'],0,16);
        }

        return JsonResult::layTable($data,$count); //返回JsonResult对象数据
    }


    /**
     * 根据角色id获取所有权限
     * @param $Rid
     * @return mixed
     */
    public function getPermissionByRid($Rid) {

        //使用模型方法
        $rolePermissionModel = Loader::model(RolePermissionDao::class);//加载角色权限Dao类

        $permissionService = new PermissionService(); //实例化权限服务类

        $permissions = array(); //存放权限信息

        //根据角色id从 "角色权限联系表" 获取权限id
        $permissionIds = $rolePermissionModel->fields('permissionId')->where('roleId', $Rid)->find(); //角色所有的权限id

        //遍历权限id获取权限信息
        foreach ($permissionIds as $permissionId) {
            $permissions[] = $permissionService->findById($permissionId['permissionId']); //调用权限服务类方法获取权限信息
        }
        return $permissions; //返回权限信息
    }
}