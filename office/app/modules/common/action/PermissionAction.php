<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-04
 */

namespace app\common\action;

use app\common\service\PermissionService;
use herosphp\core\Controller;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use herosphp\filter\Filter;

class PermissionAction extends Controller {

    /**
     * 返回权限视图
     */
    public function index() {
        $this->assign('apiUrl', '/common/permission/');
        $this->setView("permission/permission");
    }


    /**
     * 获取数据(包含条件获取)
     * @param HttpRequest $request
     */
    public function getTableData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $searchCondition = $request->getParameters();//返回一个数组
        /*
         * @param　page　页码
         * @param　limit　每页条数
         * @param　roleName　角色名称
         * @param　detail　角色描述
         * @param　status　角色状态
         * @param　timeType　时间类型：创建|更新
         * @param　start　开始时间
         * @param　end　结束时间
         * */

        $permissionService = new PermissionService();//实例化权限服务类
        $data = $permissionService->getPageData($searchCondition);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }


    /**
     * 根据权限id获取权限信息
     * @param HttpRequest $get
     */
    public function getPermissionById(HttpRequest $get){
        //接收数据
        $id=$get->getParameter('id');
        $permissionService=new PermissionService(); //初始化权限服务类
        $result=$permissionService->fields('id','permissionName','method')->findById($id);
        if(!$result){
            JsonResult::fail('获取失败！',$result)->output();
        }
        JsonResult::success('获取成功！',$result)->output();
    }
    /**
     * 获取模块
     */
    public function getModule() {

        $data = getConfig('modules');//获取模块配置信息

        if (!$data) {
            JsonResult::fail('获取失败！',$data)->output();//框架加工json格式数据才能返回
        }
        JsonResult::success('获取成功！', $data)->output();//框架加工json格式数据才能返回

    }

    /**
     * 根据模块获取控制器
     * @param HttpRequest $get
     */
        //获取参数
    public function getControllerByModule(HttpRequest $get) {
        $module = $get->getParameter('module');
        $result = getConfig($module . '_controller');//获取模块下控制器配置信息

        if (!$result) {
            JsonResult::fail($result)->output();//框架加工数据才能返回
        }
        $data = array();
        foreach ($result as $index => $value) {
            $data[$module . '/' . $index] = $value;
        }
        JsonResult::success('获取成功！', $data)->output();//框架加工数据才能返回
    }

    /**
     * 根据控制器获取方法
     * @param HttpRequest $get
     */
    public function getMethodByController(HttpRequest $get) {
        //获取参数
        $controller = $prefix = $get->getParameter('controller');
        $controller = str_replace('/', '_', $controller);
        $result = getConfig($controller . '_method');//获取控制器下方法配置信息

        if (!$result) {
            JsonResult::fail($result)->output();//框架加工数据才能返回
        }
        $data = array();
        foreach ($result as $index => $value) {
            $data[$prefix . '/' . $index] = $value;
        }
        JsonResult::success('获取成功！', $data)->output();//框架加工数据才能返回
    }

    /**
     * 添加数据
     * @param HttpRequest $post
     */
    public function add(HttpRequest $post) {
        //获取提交的参数
        $data = $post->getParameters();
        $permissionService = new PermissionService(); //实例化权限服务类

        //添加验证规则
        $filterMap = array(
            'permissionName' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "权限名称不能为空.", "length" => "权限名称必需在1-30之间.")),
        );

        //验证过滤
        $data = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$data) {
            JsonResult::fail($error, $data)->output();//框架返回json数据
        }
        //查询该权限是否已存在
        $result=$permissionService->where('method',$data['method'])->find();
        if($result){
            JsonResult::fail('添加失败，该权限已存在！', $result)->output();
        }
        $result = $permissionService->addData($data); //服务类添加方法
        if (!$result) {
            JsonResult::fail('添加失败！', $result)->output();
        }
        JsonResult::success('添加成功！', $result)->output();

    }

    /**
     * 删除数据
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {
        //获取提交的参数
        $ids = $post->getParameter('ids');
        $permissionService = new PermissionService(); //实例化权限服务类
        $result = $permissionService->deleteData($ids); //服务类添加方法
        if (!$result) {
            JsonResult::fail('删除失败！', $result)->output();
        }
        JsonResult::success('删除成功！', $result)->output();
    }

    /**
     * 返回修改视图
     * @param HttpRequest $get
     */
    public function edit(HttpRequest $get) {
        //获取提交的参数
        $id = $get->getParameter('id');
        $this->assign('id', $id);
        $this->assign('apiUrl', '/common/permission/');
        $this->setView("permission/edit");
    }

    public function editData(HttpRequest $post){
        //获取更新数据
        $data=$post->getParameters();
        $id=$data['id']; //取出id
        unset($data['id']); //更新操作不修改id，剔除id
        $permissionService = new PermissionService(); //实例化权限服务类


        //添加验证规则
        $filterMap = array(
            'permissionName' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "权限名称不能为空.", "length" => "权限名称必需在1-30之间.")),
        );

        //验证过滤
        $data = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$data) {
            JsonResult::fail($error, $data)->output();//框架返回json数据
        }

        //查询该权限是否已存在
        $check=[
            ['id','!=',$id],
            ['method','=',$data['method']],
        ];
        $result=$permissionService->whereArr($check)->find();
        if($result){
            JsonResult::fail('修改失败，该权限已存在！', $result)->output();
        }

        $result = $permissionService->update($data,$id); //服务类更新方法
        if (!$result) {
            JsonResult::fail('更新失败！', $result)->output();
        }

        JsonResult::success('更新成功！', '')->output();
    }

}