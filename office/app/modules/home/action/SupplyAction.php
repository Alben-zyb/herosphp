<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午11:12
 * @function
 */

namespace app\home\action;


use app\admin\service\IdentifyService;
use app\supply\service\GoodsCategoryService;
use app\supply\service\StorehouseService;
use app\supply\service\GoodsRecordService;
use herosphp\core\Controller;
use herosphp\utils\JsonResult;
use herosphp\http\HttpRequest;
use herosphp\core\Loader;

class SupplyAction extends Controller {

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
     * 返回办公用品视图
     */
    public function index() {
        $this->assign('apiUrl', '/home/supply/');
        $this->setView('supply/supply');
    }

    /**
     * 返回物品申领视图
     * @param HttpRequest $get
     */
    public function outHouse(HttpRequest $get) {
        $id = $get->getParameter('id');
        $this->assign("id", $id);
        $this->assign("apiUrl", "/home/supply/");
        $this->setView("supply/outHouse");
    }

    /**
     * 返回物品申领记录视图
     * @param HttpRequest $get
     */
    public function history(HttpRequest $get) {
        $id = $get->getParameter('id');
        $this->assign("id", $id);
        $this->assign("apiUrl", "/home/supply/");
        $this->setView("supply/history");
    }

    /**
     * 获取出库记录
     * @param HttpRequest $request
     */
    public function getOutHouseData(HttpRequest $request) {
        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        $loginUser=IdentifyService::getUser();

        $search['operate'] = '0'; //0:出库
        $search['handler'] = $loginUser['userNo']; //设置当前登录用户为操作人

        //$search:条件参数（分页，查询条件等）

        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　goodsNo　物品编号
         * @param　goodsName　物品名称
         * @param　mim　物品最小范围
         * @param　mam　物品最大范围
         * @param　timeType　时间类型：创建|更新
         * @param　start　开始时间
         * @param　end　结束时间
         * */

        $data = $this->recordService->getInOutHousePageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }


    /**
     * 获取所有物品分类
     */
    public function getCategory() {
        $category = GoodsCategoryService::getListData(); //调用物品分类服务类
        JsonResult::success('获取成功', $category)->output();  //JsonResult数据返回给前端
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

        $data = $this->storehouseService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

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


    /**
     * 添加物品申领记录(出库)
     * @param HttpRequest $post
     */
    public function addData(HttpRequest $post) {
        $data = $post->getParameters();  //获取更新的数据
        $data['operate']='0'; //出库
        $result = $this->recordService->addSubmit($data);
        $result->output();//框架返回json数据
    }
    /**
     * 修改物品申请状态
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {
        $ids = $post->getParameter('ids');  //获取处理数据的id
        $action = $post->getParameter('action');  //获取处理数据的方式

        switch ($action) {

            //取消申请(申请人)
            case 'cancel':
                $result = $this->recordService->cancel($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;
            default:
                JsonResult::fail('操作失败!')->output();
                break;
        }
    }
}