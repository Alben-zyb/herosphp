<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午11:33
 * @function  休假申请控制类
 */

namespace app\vacation\action;


use app\admin\service\IdentifyService;
use app\common\service\UserService;
use app\vacation\service\VacationService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use app\vacation\service\VacationTypeService;
use herosphp\utils\JsonResult;

class VacationAction extends Controller {

    protected $vacationService; //休假申请服务类
    protected $entityService; //实体服务类
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
     * 返回休假记录状态视图
     * @param HttpRequest $get
     */
    public function index(HttpRequest $get) {
        //获取前端数据
        $status = $get->getParameter('status');
        $this->assign("status", $status); //休假申请状态
        $this->assign("apiUrl", "/vacation/vacation/");
        $this->setView("vacation");
    }

    /**
     * 根据休假申请id返回申请约详细视图
     * @param HttpRequest $get
     */
    public function indexDetail(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        //渲染到detail页面
        $this->assign('id', $id);
        $this->assign("apiUrl", "/vacation/vacation/");
        $this->setView("process");
    }

    /**
     * 获取休假申请记录信息
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组

        //添加当前审核人字段
        $user=IdentifyService::getUser();//返回一个数组
        if(!$user['isAdmin']){
            $search['superior'] = $user['id'];
            $search['departmentHead'] = $user['id'];
            $search['presentCheck'] = $user['id'];
        }

        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　roomId　会议室id
         * @param　roomName　会议室名称
         * @param　mim　会议室最小容纳人数
         * @param　mam　会议室最大容纳人数
         * @param　timeType　时间类型：创建|更新
         * @param　start　开始时间
         * @param　end　结束时间
         * */

        $data = $this->vacationService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }


    public function getVacationByUid(HttpRequest $get){
        $id = $get->getParameter('id');
        $result=$this->vacationService->fields('id,superior,departmentHead,presentCheck')->where('id',$id)->findone();
        JsonResult::success('',$result)->output();
    }
    /**
     * 获取所有休假类型
     */
    public function getType(){
        $vacationType = VacationTypeService::getListData(); //调用请假类型服务类
        JsonResult::success('获取成功',$vacationType)->output();  //JsonResult数据返回给前端
    }


    /**
     * 删除申请关闭或申请过期的请假
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $result = $this->vacationService->deleteCommit($ids);
        $result->output();//框架返回json数据
    }

    /**
     * 处理休假申请任务,修改申请状态
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {

        $ids = $post->getParameter('ids');  //获取处理数据的id
        $action = $post->getParameter('action');  //获取处理数据的方式
        $refuseReason = $post->getParameter('refuseReason');  //获取处理数据的理由

        switch ($action) {
            //通过申请(上级)
            case 'pass':
                //JsonResult::fail('获取成功')->output();  //JsonResult数据返回给前端
                $result = $this->vacationService->pass($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;

            //拒绝申请(上级)
            case 'refuse':
                $result = $this->vacationService->refuse($ids,$refuseReason);  //修改数据库
                $result->output(); //返回Json数据
                break;

            //取消申请(申请人)
            case 'cancel':
                $result = $this->vacationService->cancel($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;
            default:
                JsonResult::fail('操作失败!')->output();
                break;
        }

    }

}