<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-15 上午8:44
 * @function  会议室实体控制类
 */

namespace app\room\action;

use herosphp\core\Controller;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use app\room\service\EntityService;

class EntityAction extends Controller {

    /**
     * 返回会议室实体视图
     */
    public function index() {
        $this->assign("API_URL", "/room/entity/");
        $this->setView("entity/entity");
    }


    /**
     * 返回添加视图
     */
    public function add() {
        //返回添加视图
        $this->assign("API_URL", "/room/entity/");
        $this->setView("entity/add");
    }


    public function edit(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        //渲染到edit页面
        $this->assign('id', $id);
        $this->assign("API_URL", "/room/entity/");
        $this->setView("entity/edit");
    }


    /**
     * 获取会议室实体信息
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　roomName　会议室名称
         * @param　device　会议室设备情况
         * @param　mim　会议室最小容纳人数
         * @param　mam　会议室最大容纳人数
         * @param　timeType　时间类型：创建|更新
         * @param　start　开始时间
         * @param　end　结束时间
         * */

        $entityService = new EntityService();//实例化会议室实体服务类
        $data = $entityService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 添加会议室实体
     * @param HttpRequest $post
     */
    public function addSubmit(HttpRequest $post) {

        //获取添加的数据
        $addData = $post->getParameters();

        $entityService = new EntityService();//实例化会议室实体服务类
        $result = $entityService->addData($addData);//服务类添加方法
        if ($result['code']=='001') {
            JsonResult::fail($result['msg'], $result)->output();
        }
        JsonResult::success($result['msg'], $result)->output();
    }


    /**
     * 更新数据
     * @param HttpRequest $post
     */
    public function editSubmit(HttpRequest $post) {

        //获取修改的数据
        $editData = $post->getParameters();

        $entityService = new EntityService();//实例化会议室实体服务类
        $result = $entityService->editData($editData);//服务类修改方法
        if ($result['code']=='001') {
            JsonResult::fail($result['msg'], $result)->output();
        }
        JsonResult::success($result['msg'], $result)->output();
    }

    /**
     * 修改会议室状态
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {
        //获取前端数据
        $id = $post->getParameter('id');
        $status = $post->getParameter('status');
        $entityService = new EntityService();//实例化会议室实体服务类

        $result = $entityService->set('status', $status, $id);
        if (!$result) {
            JsonResult::fail('修改失败！', $result)->output();//框架返回json数据
        }
        JsonResult::success('修改成功！', $result)->output();//框架返回json数据
    }

    /**
     * 根据id获取会议室实体信息
     * @param HttpRequest $post
     */
    public function getEntityById(HttpRequest $post) {
        //获取前端数据
        $id = $post->getParameter('id');
        $entityService = new EntityService();//实例化会议室服务类

        $result = $entityService->findById($id);
        if ($result) {
            JsonResult::success('获取成功！', $result)->output();//框架返回json数据
        }
    }

    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $entityService = new EntityService();//实例化会议室实体服务类
        $entityService->beginTransaction(); //开启事务
        //删除会议室实体信息
        $result = $entityService->where('id', 'IN', $ids)->deletes();
        if (!$result) {
            //删除失败，回滚操作
            $entityService->rollback();
            JsonResult::fail('删除失败！')->output();//框架返回json数据
        }
        $entityService->commit(); //提交事务
        JsonResult::success('删除成功！', $result)->output();//框架返回json数据
    }

}
