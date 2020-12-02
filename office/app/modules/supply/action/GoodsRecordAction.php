<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-18 下午5:05
 * @function
 */

namespace app\supply\action;


use app\supply\service\GoodsCategoryService;
use app\supply\service\GoodsRecordService;
use herosphp\core\Controller;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use herosphp\core\Loader;

class GoodsRecordAction extends Controller {

    protected $recordService; //物品出入库记录服务类

    /**
     * 构造方法
     * VacationAction constructor.
     */
    public function __construct() {
        parent::__construct();
        //初始化当前服务类
        $this->recordService = Loader::service(GoodsRecordService::class);
    }

    /**
     * 返回物品出入库视图
     * @param HttpRequest $get
     */
    public function outHouseIndex(HttpRequest $get) {
        //获取前端数据
        $status = $get->getParameter('status');
        $this->assign("status", $status); //申请状态
        $this->assign("apiUrl", "/supply/goodsRecord/");
        $this->setView("goodsRecord/goodsRecord");
    }

    /**
     * 获取入库记录
     * @param HttpRequest $request
     */
    public function getIntHouseData(HttpRequest $request) {
        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        $search['operate'] = '1'; //1:入库
        $this->getData($search);
    }

    /**
     * 获取出库记录
     * @param HttpRequest $request
     */
    public function getOutHouseData(HttpRequest $request) {
        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        $search['operate'] = '0'; //0:出库
        $this->getData($search);
    }

    /**
     * 获取物品流水记录
     * @param HttpRequest $request
     */
    public function IndexGetInOutHouse(HttpRequest $request) {

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
        $search['status']='2'; //已完成(已入库或已申领)
        $data = $this->recordService->getInOutHousePageData($search,true);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 获取物品出入库信息
     * @param array $searchCondition
     */
    public function getData(array $searchCondition) {

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

        $data = $this->recordService->getInOutHousePageData($searchCondition);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

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
     * 返回物品入库视图
     * @param HttpRequest $get
     */
    public function inHouse(HttpRequest $get) {

        $id = $get->getParameter('id');
        $this->assign("id", $id);
        $this->assign("apiUrl", "/supply/storehouse/");
        $this->assign("inoutHouseUrl", "/supply/goodsRecord/");
        $this->setView("storehouse/inHouse");
    }

    /**
     * 添加物品入库记录
     * @param HttpRequest $post
     */
    public function inHouseSubmit(HttpRequest $post) {
        $data = $post->getParameters();  //获取更新的数据
        $data['operate'] = '1'; //入库
        $this->addData($data);
    }

    /**
     * 物品出库操作(修改物品申请状态)
     * @param HttpRequest $post
     */
    public function outHouseSubmit(HttpRequest $post) {
        $ids = $post->getParameter('ids');  //获取处理数据的id
        $action = $post->getParameter('action');  //获取处理数据的方式

        switch ($action) {
            //通过申请(行政)
            case 'pass':
                $result = $this->recordService->pass($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;

            //拒绝申请(行政)
            case 'refuse':
                $result = $this->recordService->refuse($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;

            //确认已领取(行政)
            case 'received':
                $result = $this->recordService->received($ids);  //修改数据库
                $result->output(); //返回Json数据
                break;

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

    /**
     * 添加物品流水记录(入库出库)
     * @param $data
     */
    protected function addData($data) {
        $result = $this->recordService->addSubmit($data);
        $result->output();//框架返回json数据
    }


    /**
     * 删除物品申请记录(只能删除"拒绝:3"和"取消:4"的申请记录)
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $result = $this->recordService->deleteCommit($ids);
        $result->output();//框架返回json数据
    }
}