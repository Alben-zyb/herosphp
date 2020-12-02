<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-06-28
 */

namespace app\common\action;


use app\common\service\PositionService;
use herosphp\core\Controller;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use app\common\service\DepartmentService;
use herosphp\filter\Filter;

class PositionAction extends Controller {


    /**
     * 返回职位视图
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {
        $departmentId = $request->getParameter('departmentId');
        $this->assign('departmentId', $departmentId);
        $this->setView("position/position");
    }

    /**
     * 获取数据(包含条件获取)
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $searchCondition = $request->getParameters();
        $positionService = new PositionService();//实例化岗位服务类
        $data = $positionService->getPageData($searchCondition);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 获取岗位数据
     * @param HttpRequest $request
     */
    public function getPositionByDepartment(HttpRequest $request) {
        //接收数据
        $departmentId = $request->getParameter('departmentId');
        $positionService = new PositionService();//实例化岗位服务类
        $positionService->getPositionByDepartment($departmentId)->output();
    }

    /**
     * 获取部门数据
     */
    public function getDepartments() {
        $departmentService = new DepartmentService();//实例化部门服务类
        $data = $departmentService->getFieldData('id,departmentNo,departmentName');//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型
        JsonResult::success('获取成功！', $data)->output();
    }

    /**
     * 添加数据
     * @param HttpRequest $post
     */
    public function add(HttpRequest $post) {

        //获取添加的数据
        $parentId = $post->getParameter('parentDepartment');
        $positionName = $post->getParameter('addPositionName');

        if (!is_numeric($parentId)) {
            JsonResult::fail("不是正确格式的部门编号！")->output();
        }

        $filterPositionName = array(
            'positionName' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "岗位名称长度为1~30个字符", "length" => "岗位名称长度为1~30个字符")
            )
        );
        $data = [
            'positionName' => $positionName
        ];
        $positionResult = Filter::loadFromModel($data, $filterPositionName, $error);
        if (!$positionResult) {
            JsonResult::fail($error)->output();
        }

        //构建岗位编号
        $positionNo = $this->createPositionNo($parentId);


        $data = [
            'departmentId' => trim($parentId),
            'positionNo' => trim($positionNo),
            'positionName' => trim($positionName),
            'members' => 0,
            'create_time' => date('Y-m-d H:i'),
            'update_time' => date('Y-m-d H:i')
        ];
        $positionService = new PositionService();//实例化岗位服务类
        $result = $positionService->add($data);//调用add添加数据方法
        JsonResult::success('添加成功！', $result)->output();//框架返回json数据
    }

    /**
     * 删除数据
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        //JsonResult::success('添加成功！', $ids)->output();//框架返回json数据
        $positionService = new PositionService();//实例化岗位服务类
        $result = $positionService->where('id', 'IN', $ids)->deletes();
        if (!$result) {
            JsonResult::fail('删除失败！')->output();//框架返回json数据
        }

        JsonResult::success('删除成功！', $result)->output();//框架返回json数据
    }

    public function editView(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        $parentId = $get->getParameter('departmentId');
        $positionName = $get->getParameter('positionName');

        //渲染到edit页面
        $this->assign('id', $id);
        $this->assign('parentId', $parentId);
        $this->assign('positionName', $positionName);
        $this->setView("position/edit");
    }

    /**
     * 编辑数据
     * @param HttpRequest $post
     */
    public function updateData(HttpRequest $post) {

        //获取修改数据的id
        $id = $post->getParameter('id');
        $newDepartmentId = $post->getParameter('newParentId');
        $oldDepartmentId = $post->getParameter('oldParentId');
        $positionName = $post->getParameter('editPositionName');

        $positionService = new PositionService();//实例化岗位服务类

        //验证departmentId数据格式
        if (!is_numeric($newDepartmentId)) {
            JsonResult::fail("修改失败！")->output();
        }

        //验证岗位名称是否合法
        if ($positionName) {
            //正则表达式验证数据合法性（1~20位中文或1~20位中文加1~20位英文数字字符）
            $matchName = "/^(([\xe4-\xe9][\x80-\xbf]{2}){0,20}$)|(([\xe4-\xe9][\x80-\xbf]{2}){0,20}[a-zA-Z0-9]{1,20}$)/";

            if (!preg_match($matchName, $positionName)) {
                JsonResult::fail("不是正确格式的岗位名称！")->output();
            }
        }

        //组织更新数据
        //判断部门是否更新
        if ($oldDepartmentId == $newDepartmentId) {
            //没有更新
            $updateData = [
                'positionName' => $positionName,
                'update_time' => date('Y-m-d H:i')
            ];
        } else {
            //部门更新了
            //构建岗位编号
            $positionNo = $this->createPositionNo($newDepartmentId);
            $updateData = [
                'departmentId' => $newDepartmentId,
                'positionNo' => $positionNo,
                'positionName' => $positionName,
                'update_time' => date('Y-m-d H:i')
            ];
        }

        $result = $positionService->update($updateData, $id);//更新
        if (!$result) {
            JsonResult::fail('修改失败！')->output();//框架返回json数据
        }

        JsonResult::success('修改成功！', $result)->output();//框架返回json数据
    }

    /**
     * 根据部门id创建新的岗位编号
     * @param $parentDepartmentId
     * @return string
     */
    public function createPositionNo($parentDepartmentId) {
        $positionService = new PositionService();//实例化岗位服务类
        $result = $positionService->fields('positionNo')->where('departmentId', '=', $parentDepartmentId)->order('positionNo desc')->findOne();
        if ($result) {
            //部门下已存在岗位
            $oldPositionNo = $result['positionNo'];
            $departmentNo = substr($oldPositionNo, 0, 3);
            $No = substr($oldPositionNo, 3, 2) + 1;
            if (strlen($No) == 1) {
                $No = '0' . $No;
            } elseif (strlen($No) == 3) {
                JsonResult::fail('添加失败！岗位编号超出范围')->output();//框架返回json数据
            }
            $positionNo = $departmentNo . $No;
        } else {
            //部门下不存在岗位
            $departmentService = new DepartmentService();//实例化部门服务类
            $result = $departmentService->fields('departmentNo')->findById($parentDepartmentId);
            $positionNo = $result['departmentNo'] . '01';
        }
        return $positionNo;
    }


}
