<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午11:33
 * @function  休假申请控制类
 */

namespace app\vacation\action;


use app\admin\service\IdentifyService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use app\vacation\service\VacationTypeService;
use herosphp\utils\JsonResult;

class VacationTypeAction extends Controller {

    protected $vacationTypeService; //休假申请服务类

    /**
     * 构造方法
     * VacationAction constructor.
     */
    public function __construct() {
        parent::__construct();
        //初始化当前服务类
        $this->vacationTypeService = Loader::service(VacationTypeService::class);
    }

    /**
     * 返回休假记录状态视图
     */
    public function index() {
        //获取前端数据
        $this->assign("apiUrl", "/vacation/vacationType/");
        $this->setView("vacationType");
    }

    /**
     * 获取休假申请记录信息
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组

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

        $data = $this->vacationTypeService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }


    /**
     * 获取所有休假类型(用于下拉选择)
     */
    public function getType(){
        $vacationType = VacationTypeService::getListData(); //调用请假类型服务类
        JsonResult::success('获取成功',$vacationType)->output();  //JsonResult数据返回给前端
    }

    /**
     * 添加请假类型数据
     * @param HttpRequest $post
     */
    public function addData(HttpRequest $post) {
        $data = $post->getParameters();  //获取更新的数据
        $result = $this->vacationTypeService->addSubmit($data);
        $result->output();//框架返回json数据
    }
    /**
     * 更新请假类型数据
     * @param HttpRequest $post
     */
    public function updateData(HttpRequest $post) {

        $data = $post->getParameters();  //获取更新的数据
        $result = $this->vacationTypeService->updateSubmit($data);
        $result->output();//框架返回json数据
    }


    /**
     * 删除请假类型数据
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $result = $this->vacationTypeService->deleteCommit($ids);
        $result->output();//框架返回json数据
    }


}