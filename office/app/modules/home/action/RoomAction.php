<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-22 下午3:46
 * @function  前台会议室预约控制类
 */

namespace app\home\action;


use app\admin\service\IdentifyService;
use app\home\service\RoomService;
use app\room\service\ApplyService;
use herosphp\core\Controller;
use app\room\service\EntityService;
use app\common\service\UserService;
use herosphp\core\Loader;
use herosphp\utils\JsonResult;
use herosphp\http\HttpRequest;

class RoomAction extends Controller {
    protected $applyService; //预约服务类
    protected $entityService; //实体服务类
    protected $roomService; //会议室预约服务类
    protected $userService; //用户服务类

    /**
     * 构造方法
     * VacationAction constructor.
     */
    public function __construct() {
        parent::__construct();
        //初始化当前服务类
        $this->applyService = Loader::service(ApplyService::class);
        $this->entityService = Loader::service(EntityService::class);
        $this->roomService = Loader::service(RoomService::class);
        $this->userService = Loader::service(UserService::class);
    }
    /**
     * 返回会议室预约服务视图
     */
    public function index() {
        $this->assign("apiUrl", "/home/room/");
        $this->setView("room/room");
    }

    /**
     * 返回会议室预约记录视图
     */
    public function history() {
        $this->assign("apiUrl", "/home/room/");
        $this->setView("room/history");
    }

    public function test() {
        $this->assign("apiUrl", "/home/room/");
        $this->setView("room/test");
    }

    /**
     * 返回选择参会人员视图
     */
    public function user() {
        $this->assign("url_getPositionByDepartment", "/common/position/getPositionByDepartment");
        $this->assign("url_getDepartmentTree", "/common/department/getDepartmentTree");
        $this->assign("url_getData", "/common/user/getData");
        $this->assign("login_id", IdentifyService::getUser()['id']);
        $this->setView("room/user");
    }


    /**
     * 获取所有会议室
     * @param HttpRequest $get
     */
    public function getRoom(HttpRequest $get) {
        $id = $get->getParameter('id'); //获取差值条件id
        if ($id) {
            $data = $this->entityService->findById($id);
        } else {
            $data = $this->entityService->find();
        }
        if (!$data) {
            JsonResult::fail('', $data)->output();//框架加工数据才能返回
        }
        JsonResult::success('', $data)->output();//框架加工json格式数据才能返回
    }

    /**
     * 获取会议室预约详细信息
     * @param HttpRequest $request
     */
    public function getRoomDetail(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
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

        $roomService = new RoomService();//实例化会议室预约服务类
        $data = $roomService->getRoomDetail($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 获取会议室预约记录详细信息
     * @param HttpRequest $request
     */
    public function getHistory(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        //添加申请人字段
        $user=IdentifyService::getUser();
        $search['applicant'] = $user['userNo'];//返回一个数组
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

        $data = $this->applyService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }
    /**
     * 获取所有用户，用于下拉选择参会人员
     */
    public function getUser() {
        $result=UserService::getUserList();
        $result->output();
    }

    /**
     * 添加会议预约记录
     * @param HttpRequest $post
     */
    public function addSubmit(HttpRequest $post) {
        $data = $post->getParameters();
        $result = $this->roomService->addSubmit($data);
        $result->output(); //输出json对象
    }

    /**
     * 获取预约的详细信息
     * @param HttpRequest $get
     */
    public function indexDetail(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        //渲染到detail页面
        $this->assign('id', $id);
        $this->assign("apiUrl", "/home/room/");
        $this->setView("room/detail");
    }

    /**
     * 根据会议室实体id获取会议室详细信息
     * @param HttpRequest $get
     */
    public function getRoomByRid(HttpRequest $get) {
        $id = $get->getParameter('id');
        $result = $this->applyService->getRoomByRid($id);
        $result->output();//框架加工json格式数据才能返回
    }

    /**
     * 根据会议室预约id获取会议室预约详细信息
     * @param HttpRequest $get
     */
    public function getApplyByAid(HttpRequest $get) {
        $id = $get->getParameter('id');
        $result = $this->applyService->getApplyByAid($id);
        $result->output();//框架加工json格式数据才能返回
    }

    /**
     * 根据用户id获取会议室申请人详细信息
     * @param HttpRequest $get
     */
    public function getApplicantByUid(HttpRequest $get) {
        $id = $get->getParameter('id');
        $result = $this->applyService->getApplicantByUid($id);
        $result->output();//框架加工json格式数据才能返回
    }

    /**
     * 根据会议室预约id获取参会人员详细信息
     * @param HttpRequest $get
     */
    public function getMeetingMemberByAid(HttpRequest $get) {
        $id = $get->getParameter('id');
        $result = $this->applyService->getMeetingMemberByAid($id);
        $result->output();//框架加工json格式数据才能返回
    }

    /**
     * 撤销申请(申请中、申请成功的预约)
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {

        $ids = $post->getParameter('ids');  //获取处理数据的id
        $action = $post->getParameter('action');  //获取处理数据的方式

        switch ($action) {
            //撤销通过申请
            case 'close':
                $result = $this->applyService->close($ids,false);  //修改数据库
                $result->output();
                break;
            default:
                break;
        }
        JsonResult::fail('修改失败!')->output();

    }



}