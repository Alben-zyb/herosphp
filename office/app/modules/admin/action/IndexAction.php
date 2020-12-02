<?php
/**
 * @author     ZhengYiBin<zhengyb@pvc123.com>
 * @date       2020-06-19
 * @function   基础后台控制类
 */

namespace app\admin\action;


use app\admin\dao\CalendarDao;
use app\admin\service\IdentifyService;
use app\room\service\ApplyService;
use app\supply\service\GoodsRecordService;
use app\vacation\service\VacationService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\model\MysqlModel;
use herosphp\utils\JsonResult;


class IndexAction extends Controller {

    /**
     * 返回后台首页视图
     */
    public function index() {
//        $loginUser = IdentifyService::getUser();

        $this->assign('apiUrl', '/admin/index/'); //本类方法统一路径
        $this->assign('department_url', '/common/department/index'); //部门管理跳转路径
        $this->assign('position_url', '/common/position/index');  //岗位管理跳转路径
        $this->assign('user_url', '/common/user/index');  //用户管理跳转路径
        $this->assign('role_url', '/common/role/index');  //角色管理跳转路径
        $this->assign('permission_url', '/common/permission/index');  //权限管理跳转路径

        $this->assign('entity_url', '/room/entity/index');  //会议室管理跳转路径
        $this->assign('apply_url', '/room/apply/index');  //会议室申请管理跳转路径

        $this->assign('vacation_url', '/vacation/vacation/index');  //休假申请管理跳转路径
        $this->assign('vacationType_url', '/vacation/vacationType/index');  //休假类型管理跳转路径

        $this->assign('storehouse_url', '/supply/storehouse/index');  //办公用品仓库跳转路径
        $this->assign('goodsCategory_url', '/supply/goodsCategory/index');  //办公用品分类跳转路径
        $this->assign('goodsRecord_url', '/supply/goodsRecord/outHouseIndex');  //办公用品申领记录跳转路径

        $this->setView("index");
    }

    /**
     * 返回后台欢迎视图
     */
    public function welcome() {
        $this->setView("welcome");
    }

    /**
     * 错误跳转页面
     */
    public function error() {
        $this->setView("error");
    }


    /**
     * 获取日历(存储过程)
     */
    public function getCalendar() {

        $model = new MysqlModel('calendar'); //调用日历模型类
        $sql = "delete from calendar";
        $result = $model->execSql($sql);
        $sql = "CALL getYearDateFunc(" . date('Y') . ")";
        $result *= $model->execSql($sql);
        JsonResult::success('获取成功', $result)->output();
    }

    /**
     * 获取待处理的任务
     */
    public function getNeedToHandle() {
        $identifyService=new IdentifyService();
        $loginUser = $identifyService->getUser(); //调用服务类的获取登录用户信息
        //获取用户权限方法
        $methods = $identifyService->getUserPermissionsByUid($loginUser['id']);
        $applyCount = 0;
        $vacationCount = 0;
        $supplyCount = 0;

        //检查是否拥有审核相关任务的权限
        foreach ($methods as $method) {
            if ($method == 'room/apply/index') {
                //拥有审核会议室申请的权限,显示待审核任务
                $checkRoom = true;
            }
            if ($method == 'vacation/vacation/index') {
                //拥有审核请假申请的权限,显示待审核任务
                $checkVacation = true;
            }
            if ($method == 'supply/goodsRecord/outHouse') {
                //拥有审核办公用品申请的权限,显示待审核任务
                $checkSupply = true;
            }
        }
        if (isset($checkRoom) && $checkRoom) {
            //调用会议室申请服务类
            $applyService = new ApplyService();
            $applyCount = $applyService->where('status', '0')->count(); //获取会议室申请中的任务
        }

        if (isset($checkVacation) && $checkVacation) {
            //调用休假申请服务类
            $vacationService = new VacationService();
            $vacationCount = $vacationService->where('status', '0')
                ->where('presentCheck', $loginUser['id'])
                ->count(); //获取休假申请中的任务
        }

        if (isset($checkSupply) && $checkSupply) {
            //调用办公用品申领服务类
            $goodsRecordService = new GoodsRecordService();
            $supplyCount = $goodsRecordService->where('status', '0')
                ->count(); //获取办公用品申请中的任务
        }

        $data = [
            'applyCount' => $applyCount + 0,
            'vacationCount' => $vacationCount + 0,
            'supplyCount' => $supplyCount + 0,
        ];
        JsonResult::success('获取成功', $data)->output();
    }
}