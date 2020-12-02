<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-06-23
 * @detail 该类提供操作部门的相关方法
 */

namespace app\common\action;

use app\admin\service\IdentifyService;
use app\common\service\PositionService;
use herosphp\core\Controller;

use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use herosphp\filter\Filter;
use app\common\service\DepartmentService;

class DepartmentAction extends Controller {

    /**
     * 返回部门视图
     */
    public function index() {
        $this->setView("/department/department");
    }


    /**
     * 返回编辑视图
     * @param HttpRequest $get
     */

    public function edit(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        $parentId = $get->getParameter('parentId');
        $departmentName = $get->getParameter('departmentName');

        //渲染到edit页面
        $this->assign('id', $id);
        $this->assign('parentId', $parentId);
        $this->assign('departmentName', $departmentName);
        $this->setView("department/edit");
    }

    /**
     * 获取数据(包含条件获取)
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（查询条件）
        $searchData = $request->getParameters();//departmentName部门名称,departmentNo部门编号,timeType时间类型：创建|更新,start开始时间,end结束时间,memberMin<部门人数<memberMax
        $departmentService = new DepartmentService();//实例化部门服务类
        $data = $departmentService->getDepartmentData($searchData);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型
        $data->output();//输出
    }

    /**
     * 获取部门树型结构
     */
    public function getDepartmentTree() {

        $departmentService = new DepartmentService();//实例化部门服务类

        $data = $departmentService->getDepartmentTree();//获取数据

        JsonResult::layTable($data)->output();//输出
    }

    /**
     * 添加数据
     * @param HttpRequest $post
     */
    public function add(HttpRequest $post) {
        //获取添加的数据
        $parentId = $post->getParameter('parentDepartment');
        $departmentName = $post->getParameter('addDepartmentName');
        $departmentService = new DepartmentService();//实例化部门服务类
        $data = [
            'parentId' => $parentId,
            'departmentName' => $departmentName,
        ];
        $result = $departmentService->addData($data);
        if (!$result) {
            JsonResult::fail('添加失败！', $result)->output();//框架返回json数据
        }
        JsonResult::success('添加成功！', $result)->output();//框架返回json数据
    }


    /**
     * 根据id删除部门
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $id = $post->getParameter('departmentId');

        $departmentService = new DepartmentService();//实例化部门服务类
        $positionService = new PositionService();//实例化岗位服务类
        //查询该部门下是否有子部门，存在子部门，不允许删除
        $result = $departmentService->fields('id')->where('parentId', '=', $id)->findOne();
        if ($result) {
            JsonResult::fail('不允许删除！该部门下存在子部门！')->output();//框架返回json数据
        }

        //查询该部门下是否有岗位，部门下存在岗位不允许删除
        $result = $positionService->fields('id')->where('departmentId', '=', $id)->findOne();
        if ($result) {
            JsonResult::fail('不允许删除！该部门下已存在岗位！')->output();//框架返回json数据
        }
        $result = $departmentService->fields('parentId')->findById($id);//查找当前删除的部门的父id
        $parentId = $result['parentId'];//父id

        $result = $departmentService->delete($id);//删除当前部门
        if (!$result) {
            JsonResult::fail('删除失败！')->output();//框架返回json数据
        }

        $result = $departmentService->where('parentId', '=', $parentId)->findOne();//查询刚才删除的部门的父部门是否还存在子部门
        if (!$result) {
            //没有子部门了，更新父部门为叶子节点
            $departmentService->update(['isLeaf' => 1], $parentId);//更新
        }
        JsonResult::success('删除成功！', $result)->output();//框架返回json数据
    }

    /**
     * 更新数据
     * @param HttpRequest $post
     */
    public function editData(HttpRequest $post) {


        //获取修改数据的id
        $id = $post->getParameter('id');
        $oldParentId = $post->getParameter('oldParentId');
        $newParentId = $post->getParameter('newParentId');
        $departmentName = $post->getParameter('editDepartmentName');

        $departmentService = new DepartmentService();//实例化部门服务类


        //验证$newParentId数据格式
        if (!is_numeric($newParentId)) {
            JsonResult::fail("修改失败！")->output();
        }

        //验证部门名称
        $filterDepartment = array(
            'departmentName' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "部门名称长度为1~30个字符", "length" => "部门名称长度为1~30个字符")
            )
        );
        $data = [
            'departmentName' => $departmentName
        ];
        $departmentResult = Filter::loadFromModel($data, $filterDepartment, $error);
        if (!$departmentResult) {
            JsonResult::fail($error)->output();
        }

        //组织更新数据
        $updateData = [
            'departmentName' => $departmentName,
            'operator' => IdentifyService::getUser()['username'],
            'operatorId' => IdentifyService::getUser()['id'],
            'update_time' => date('Y-m-d H:i')
        ];
        //判断部门是否更新
        if ($oldParentId != $newParentId&&$newParentId!=$id) {
            //部门更新了
            $updateData['parentId']=$newParentId;
        }
        if ($oldParentId == 0&&$oldParentId!=$newParentId) {
            //修改的部门是顶级部门，则需要把其下的子部门的父id统一设置为0，即成为新的顶级部门
            JsonResult::fail('修改失败！顶级部门不允许修改！')->output();//框架返回json数据
        }
        //父部门不能修改成为子部门的子部门
        //查询判断修改的当前部门有没有子部门
        $result=$departmentService->fields('id')->where('parentId',$id)->find();
        foreach ($result as $item) {
            if($item['id']==$newParentId){
                JsonResult::fail('修改失败！父部门不能移动到子部门下成为子部门！')->output();//框架返回json数据
            }
        }

        $result = $departmentService->update($updateData, $id);//更新
        if (!$result) {
            JsonResult::fail('修改失败！')->output();//框架返回json数据
        }

        JsonResult::success('修改成功！', $result)->output();//框架返回json数据
    }


}