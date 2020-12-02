<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-18 下午5:06
 * @function
 */

namespace app\supply\action;


use herosphp\core\Controller;
use herosphp\core\Loader;
use app\supply\service\GoodsCategoryService;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

class GoodsCategoryAction extends Controller {

    protected $categoryService; //物品分类服务类

    /**
     * 构造方法
     * VacationAction constructor.
     */
    public function __construct() {
        parent::__construct();
        //初始化当前服务类
        $this->categoryService = Loader::service(GoodsCategoryService::class);
    }

    /**
     * 返回物品分类视图
     * @param HttpRequest $get
     */
    public function index(HttpRequest $get) {
        $this->assign("apiUrl", "/supply/goodsCategory/");
        $this->setView("goodsCategory/goodsCategory");
    }

    /**
     * 获取物品分类信息
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

        $data = $this->categoryService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }


    /**
     * 获取所有物品分类(用于下拉选择)
     */
    public function getType(){
        $vacationType = GoodsCategoryService::getListData(); //调用物品分类服务类
        JsonResult::success('获取成功',$vacationType)->output();  //JsonResult数据返回给前端
    }

    /**
     * 添加物品分类数据
     * @param HttpRequest $post
     */
    public function addData(HttpRequest $post) {
        $data = $post->getParameters();  //获取更新的数据
        $result = $this->categoryService->addSubmit($data);
        $result->output();//框架返回json数据
    }
    /**
     * 更新物品分类数据
     * @param HttpRequest $post
     */
    public function editData(HttpRequest $post) {

        $data = $post->getParameters();  //获取更新的数据
        $result = $this->categoryService->editSubmit($data);
        $result->output();//框架返回json数据
    }


    /**
     * 删除物品分类数据
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $result = $this->categoryService->deleteCommit($ids);
        $result->output();//框架返回json数据
    }

}