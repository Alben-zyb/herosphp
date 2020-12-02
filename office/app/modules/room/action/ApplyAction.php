<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-15 下午7:26
 * @function  会议室申请
 */

namespace app\room\action;


use app\common\service\UserService;
use app\room\service\ApplyService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use app\room\service\EntityService;

class ApplyAction extends Controller {

    protected $applyService; //预约服务类
    protected $entityService; //实体服务类
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
        $this->userService = Loader::service(UserService::class);
    }

    /**
     * 返回会议室申请状态视图
     * @param HttpRequest $get
     */
    public function index(HttpRequest $get) {
        //获取前端数据
        $status = $get->getParameter('status');
        $this->assign("status", $status); //会议室申请状态
        $this->assign("apiUrl", "/room/apply/");
        $this->setView("apply/apply");
    }

    /**
     * 根据预约id返回会议室预约详细视图
     * @param HttpRequest $get
     */
    public function indexDetail(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        //渲染到detail页面
        $this->assign('id', $id);
        $this->assign("apiUrl", "/room/apply/");
        $this->setView("apply/detail");
    }


    /**
     * 返回添加视图
     */
    public function add() {
        //返回添加视图
        $this->assign("apiUrl", "/room/entity/");
        $this->setView("entity/add");
    }


    public function edit(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        //渲染到edit页面
        $this->assign('id', $id);
        $this->assign("apiUrl", "/room/entity/");
        $this->setView("entity/edit");
    }


    /**
     * 获取会议室预约信息
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　roomId　会议室id
         * @param　applicant　申请人
         * @param　status　申请状态
         * @param　date　预约日期
         * @param　timeType　时间类型：开始|结束
         * @param　start　开始时间
         * @param　end　结束时间
         * */

        $data = $this->applyService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 获取所有会议室，用于下拉选择会议室
     */
    public function getRoom() {
        $data = $this->entityService->fields('id,roomNo,roomName')->find();
        if (!$data) {
            JsonResult::fail('', $data)->output();//框架加工数据才能返回
        }
        JsonResult::success('', $data)->output();//框架加工json格式数据才能返回
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
     * 删除申请关闭或申请过期的预约
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $result = $this->applyService->deleteCommit($ids);
        $result->output();//框架返回json数据
    }

    /**
     * 处理会议室申请任务,修改会议室预约状态
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {

        $ids = $post->getParameter('ids');  //获取处理数据的id
        $action = $post->getParameter('action');  //获取处理数据的方式

        switch ($action) {
            //通过申请
            case 'pass':
                $result = $this->applyService->pass($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;

            //撤销申请=>申请关闭
            case 'close':
                $result = $this->applyService->close($ids,true);  //修改数据库
                $result->output(); //返回Json数据
                break;
            default:
                JsonResult::fail('操作失败!')->output();
                break;
        }

    }

}
