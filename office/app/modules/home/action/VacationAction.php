<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午10:27
 * @function  休假申请控制器
 */

namespace app\home\action;


use app\admin\service\IdentifyService;
use app\api\dateLeave\LeaveManage;
use app\common\service\UserService;
use app\vacation\service\VacationService;
use app\vacation\service\VacationTypeService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

class VacationAction extends Controller {
    protected $vacationService; //预约服务类
    protected $userService; //用户服务类

    /**
     * 构造方法
     * VacationAction constructor.
     */
    public function __construct() {
        parent::__construct();
        //初始化当前服务类
        $this->vacationService = Loader::service(VacationService::class);
        $this->userService = Loader::service(UserService::class);
    }
    /**
     * 返回会议室预约服务视图
     */
    public function index() {
        $this->assign("apiUrl", "/home/vacation/");
        $this->setView("vacation/vacation");
    }

    /**
     * 获取所有休假类型
     */
    public function getType(){
        $vacationType = VacationTypeService::getListData(); //调用请假类型服务类
        JsonResult::success('获取成功',$vacationType)->output();  //JsonResult数据返回给前端
    }

    /**
     * 返回选择请假申请人视图
     */
    public function user() {
        $this->assign("url_getPositionByDepartment", "/common/position/getPositionByDepartment");
        $this->assign("url_getDepartmentTree", "/common/department/getDepartmentTree");
        $this->assign("url_getData", "/common/user/getData");
        $this->setView("vacation/user");
    }


    /**
     * 获取休假历史记录详细信息
     * @param HttpRequest $request
     */
    public function getHistory(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        //添加申请人字段
        $user=IdentifyService::getUser();
        $search['applicant'] = $user['userNo'];//添加申请人查询字段(只查询自己的记录)
        $search['agent'] = $user['id'];//添加代理人查询字段(可以查询自己代理的记录)
        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　roomId　会议室id
         * @param　device　会议室设备情况
         * @param　mim　会议室最小容纳人数
         * @param　mam　会议室最大容纳人数
         * @param　date　日期
         * @param　start　开始使用时间
         * @param　finish　结束使用时间
         * */

        $data = $this->vacationService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }
    /**
     * 获取所有用户，用于下拉选择请假申请人
     */
    public function getUser() {
        $data = $this->userService->find();
        if (!$data) {
            JsonResult::fail('获取失败', $data)->output();//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型
        }
        JsonResult::success('获取成功', $data)->output();
    }

    /**
     * 添加会议预约记录
     * @param HttpRequest $post
     */
    public function addSubmit(HttpRequest $post) {
        $data = $post->getParameters();
        $result = $this->vacationService->addSubmit($data);
        $result->output(); //输出json对象
    }


   /**
     * 获取请假时长（/天）
     * @param HttpRequest $post
     */
    public function getLeaveDays(HttpRequest $post) {
        $data = $post->getParameters();
        $result = LeaveManage::calculationTime($data['start'],$data['end']);
        JsonResult::success('获取成功',$result)->output(); //输出json对象
    }




    /**
     * 撤销申请(申请中、申请成功的预约)
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {

        $id = $post->getParameter('id');  //获取处理数据的id
        $action = $post->getParameter('action');  //获取处理数据的方式

        switch ($action) {
            //取消申请(申请中,申请通过)
            case 'cancel':
                $result = $this->vacationService->cancel($id,false);  //修改数据库
                $result->output();
                break;
            default:
                JsonResult::fail('操作失败!')->output();
                break;
        }

    }
}