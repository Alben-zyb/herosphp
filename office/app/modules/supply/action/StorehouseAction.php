<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-18 下午5:05
 * @function
 */

namespace app\supply\action;


use app\common\service\UserService;
use app\supply\service\GoodsCategoryService;
use app\supply\service\GoodsRecordService;
use app\supply\service\StorehouseService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

class StorehouseAction extends Controller {

    protected $categoryService; //物品分类服务类
    protected $storehouseService; //仓库物品服务类
    protected $recordService; //仓库物品流水服务类

    /**
     * 构造方法
     * VacationAction constructor.
     */
    public function __construct() {
        parent::__construct();
        //初始化当前服务类
        $this->categoryService = Loader::service(GoodsCategoryService::class);
        $this->storehouseService = Loader::service(StorehouseService::class);
        $this->recordService = Loader::service(GoodsRecordService::class);
    }

    /**
     * 返回仓库物品视图
     */
    public function index() {
        $this->assign("apiUrl", "/supply/storehouse/");
        $this->assign("inoutHouseUrl", "/supply/goodsRecord/");
        $this->setView("storehouse/storehouse");
    }

    /**
     * 返回添加物品数据视图
     */
    public function add() {
        $this->assign("apiUrl", "/supply/storehouse/");
        $this->setView("storehouse/add");
    }

    /**
     * 获取所有用户，用于下拉选择采购员
     */
    public function getUserList() {
        $result=UserService::getUserList(false);
        $result->output();
    }

    /**
     * 返回修改物品数据视图
     * @param HttpRequest $get
     */
    public function edit(HttpRequest $get) {
        $id = $get->getParameter('id');
        $this->assign("id", $id);
        $this->assign("apiUrl", "/supply/storehouse/");
        $this->setView("storehouse/edit");
    }

    public function test() {
        $this->assign("apiUrl", "/supply/storehouse/");
        $this->setView("storehouse/test");
    }


    /**
     * 获取仓库记录信息
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组

        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　category　分类
         * @param　goodsNo　物品编号
         * @param　goodsName　物品名称
         * @param　mim　物品最小数量
         * @param　mam　物品最大数量
         * @param　timeType　时间类型：创建|更新
         * @param　start　开始时间
         * @param　end　结束时间
         * */

        $data = $this->storehouseService->getPageData($search,'admin');//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 根据id获取仓库记录信息
     * @param HttpRequest $get
     */
    public function getDataById(HttpRequest $get) {

        //获取物品id
        $id = $get->getParameter('id');
        $data = $this->storehouseService->getDataById($id);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }



    public function getVacationByUid(HttpRequest $get) {
        $id = $get->getParameter('id');
        $result = $this->vacationService->fields('id,superior,departmentHead,presentCheck')->where('id', $id)->findone();
        JsonResult::success('', $result)->output();
    }

    /**
     * 获取所有物品分类
     */
    public function getCategory() {
        $category = GoodsCategoryService::getListData(); //调用物品分类服务类
        JsonResult::success('获取成功', $category)->output();  //JsonResult数据返回给前端
    }

    /**
     * 根据分类id获取新的物品编号
     * @param HttpRequest $get
     */
    public function getNewGoodsNo(HttpRequest $get) {
        $categoryId = $get->getParameter('category');
        //获取物品中是categoryId分类的物品编号(最新编号)
        $newGoodsNo = $this->storehouseService->createNewGoodsNo($categoryId);
        JsonResult::success('获取成功', $newGoodsNo)->output();  //JsonResult数据返回给前端
    }

    /**
     * 添加物品数据
     * @param HttpRequest $post
     */
    public function addData(HttpRequest $post) {

        $data = $post->getParameters();  //获取添加的数据

        $result = $this->storehouseService->addSubmit($data);  //更新数据库
        $result->output(); //返回Json数据

    }


    /**
     * 修改物品数据
     * @param HttpRequest $post
     */
    public function editData(HttpRequest $post) {

        $data = $post->getParameters();  //获取更新的数据

        $result = $this->storehouseService->editSubmit($data);  //修改数据库
        $result->output(); //返回Json数据

    }

    /**
     * 删除物品信息
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {
        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $result = $this->storehouseService->deleteCommit($ids);
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
                $result = $this->vacationService->refuse($ids, $refuseReason);  //修改数据库
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