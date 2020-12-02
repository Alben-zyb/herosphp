<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-03
 */

namespace app\common\action;


use app\common\service\PermissionService;
use herosphp\core\Controller;
use app\common\service\RoleService;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use herosphp\filter\Filter;
use herosphp\core\Loader;
use app\common\dao\RolePermissionDao;

class RoleAction extends Controller {

    /**
     * 返回角色视图
     */
    public function index() {
        $this->setView("role/role");
    }

    /**
     * 获取数据(包含条件获取)
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $search = $request->getParameters();//返回一个数组
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

        $roleService = new RoleService();//实例化岗位服务类
        $data = $roleService->getPageData($search);//调用查询数据方法，返回jsonResult对象数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }


    /**
     * 返回添加视图
     */
    public function add() {
        $this->assign('API_URL', '/common/role/');
        //返回编辑视图
        $this->setView("role/add");
    }

    /**
     * 添加数据
     * @param HttpRequest $post
     */
    public function addData(HttpRequest $post) {

        //获取添加的角色数据
        $data = $post->getParameters();
        $permissionIds = $data['permissionIds'];

        $roleService = new RoleService();//实例化角色服务类
        $rolePermissionModel = Loader::model(RolePermissionDao::class);//加载角色权限模型类

        //添加验证规则
        $filterMap = array(
            'roleName' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "角色名称不能为空.", "length" => "角色名称必需在1-30之间.")),
            'detail' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "角色描述不能为空.", "length" => "角色描述必需在1-60之间.")),
        );

        //验证过滤
        $result = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$result) {
            JsonResult::fail($error, $result)->output();//框架返回json数据
        }

        //先将角色添加到role表
        $id = $roleService->add($result);
        //添加失败，返回
        if (!$id) {
            JsonResult::fail('添加失败！')->output();//框架返回json数据
        }

        //循环添加权限
        foreach ($permissionIds as $permissionId) {
            $data = [
                'roleId' => $id,
                'permissionId' => $permissionId
            ];
            $result = $rolePermissionModel->add($data);
        }
        if (!$result) {
            JsonResult::fail('添加失败！')->output();//框架返回json数据
        }
        JsonResult::success('添加成功！', $result)->output();//框架返回json数据
    }


    /**
     * 返回编辑视图
     * @param HttpRequest $get
     */
    public function edit(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        $roleName = $get->getParameter('roleName');
        $detail = $get->getParameter('detail');
        //渲染到edit页面
        $this->assign('API_URL', '/common/role/');
        $this->assign('id', $id);
        $this->assign('roleName', $roleName);
        $this->assign('detail', $detail);
        $this->setView("role/edit");
    }

    /**
     * 修改数据
     * @param HttpRequest $post
     */
    public function updateData(HttpRequest $post) {

        //获取角色更新数据
        $data = $post->getParameters();
        $id = $data['id']; //角色id
        $permissionIds = $data['permissionIds']; //拥有权限的id
        unset($data['id']); //从data数据中剔除id
        unset($data['permissionIds']);//从data数据中剔除permissionIds
        $roleService = new RoleService();//实例化角色服务类
        $rolePermissionModel = Loader::model(RolePermissionDao::class);//加载角色权限模型类

        //添加验证规则
        $filterMap = array(
            'roleName' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "角色名称不能为空.", "length" => "角色名称必需在1-30之间.")),
            'detail' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "角色描述不能为空.", "length" => "角色描述必需在1-60之间.")),
        );

        //验证过滤
        $result = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$result) {
            JsonResult::fail($error, $result)->output();//框架返回json数据
        }

        $roleService->beginTransaction(); //开启事务
        $rolePermissionModel->beginTransaction();

        //更新角色信息
        $data['update_time'] = date('Y-m-d H:i'); //添加更新时间字段
        $result = $result && $roleService->update($data, $id);

        //更新角色权限信息
        //先删除角色原有权限
        //查询更新
        $oldPermission = $rolePermissionModel->fields('id,roleId,permissionId')->where('roleId', '=', $id)->find();

        //遍历更新的权限id
        foreach ($permissionIds as $key => $permissionId) {
            //遍历旧权限id
            foreach ($oldPermission as $oldKey => $old) {
                //如果更新权限与旧权限相同,不更新,最终查找出新旧不同的数据
                if ($old['permissionId'] == $permissionId) {
                    unset($permissionIds[$key]);  //剔除相同的权限
                    unset($oldPermission[$oldKey]);
                    break;
                }
            }
        }
        //删除旧的权限
        $remainId=array();
        foreach ($oldPermission as $old) {
            $remainId[]=$old['id'];
        }
        if(sizeof($remainId)){
            $result = $result && $rolePermissionModel->where('id','IN',$remainId)->deletes();
        }

        //循环添加新的权限
        foreach ($permissionIds as $permissionId) {
            $data = [
                'roleId' => $id,
                'permissionId' => $permissionId
            ];
            $result = $result && $rolePermissionModel->add($data);
        }
        if (!$result) {
            $rolePermissionModel->rollback();  //事务回滚
            $roleService->rollback();
            JsonResult::fail('更新失败！')->output();//框架返回json数据
        }
        $rolePermissionModel->commit();  //提交事务
        $roleService->commit();
        JsonResult::success('更新成功！', $result)->output();//框架返回json数据
    }


    /**
     * 修改角色状态
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {
        //获取前端数据
        $id = $post->getParameter('id');
        $status = $post->getParameter('status');
        $roleService = new RoleService();//实例化角色服务类

        $result = $roleService->set('status', $status, $id);
        if (!$result) {
            JsonResult::fail('修改失败！', $result)->output();//框架返回json数据
        }
        JsonResult::success('修改成功！', $result)->output();//框架返回json数据
    }


    /**
     * 删除数据
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $roleService = new RoleService();//实例化角色服务类
        $rolePermissionModel = Loader::model(RolePermissionDao::class);//加载角色权限模型类

        $roleService->beginTransaction();//开启角色事务
        $rolePermissionModel->beginTransaction();//开启角色权限事务


        //删除角色
        $result = $roleService->where('id', 'IN', $ids)->deletes();
        if (!$result) {
            $roleService->rollback();//删除失败，回滚操作
            JsonResult::fail('删除失败！')->output();//框架返回json数据
        }
        //循环删除角色拥有的权限
        foreach ($ids as $id) {
            //查询删除
            $result = $rolePermissionModel->where('roleId', $id)->findOne();
            if (!$result) {
                break; //不存在数据，退出该轮循环
            }
            //删除
            $result = $rolePermissionModel->where('roleId', $id)->deletes();
            if (!$result) {
                $roleService->rollback();//删除失败，回滚操作
                $rolePermissionModel->rollback(); //删除失败，回滚操作
                JsonResult::fail('删除失败！数据可能已不存在!')->output();//框架返回json数据
            }
        }
        $roleService->commit(); //提交事务
        $rolePermissionModel->commit();//提交事务
        JsonResult::success('删除成功！', $result)->output();//框架返回json数据
    }


    /**
     * 获取所有权限
     */
    public function getPermission() {
        $permissionService = new PermissionService();//实例化权限服务类
        $result = $permissionService->fields('id,permissionName,module,method')->order('method desc')->find();
        if (!$result) {
            JsonResult::fail('获取失败！')->output();//框架返回json数据
        }
        $data = $this->dataGroup($result, 'module');//根据module字段将数据分组
        //根据控制器分组进行二次分组
        foreach ($data as $key => $value) {
            $data[$key] = $this->dataGroup($value, 'controller');
        }
        if (!$result) {
            JsonResult::fail('获取失败！')->output();//框架返回json数据
        }
        JsonResult::success('获取成功！', $data)->output();//框架返回json数据
    }

    /**
     * 获取角色拥有的权限
     * @param HttpRequest $post
     */
    public function getRolePermission(HttpRequest $post) {
        //获取角色id
        $id = $post->getParameter('id');
        //使用模型方法
        $rolePermissionModel = Loader::model(RolePermissionDao::class);//加载角色权限模型类
        $result = $rolePermissionModel->fields('permissionId')->where('roleId', $id)->find();
        JsonResult::success('获取成功！', $result)->output();//框架返回json数据
    }

    /**
     * 根据某字段值将数据分组
     * @param array $dataArr
     * @param string $keyStr
     * @return array
     */
    protected function dataGroup(array $dataArr, string $keyStr) {
        $newArr = array();
        foreach ($dataArr as $key => $value) {
            $controller = (explode('/', $value['method']))[1];
            $value['controller'] = $controller;
            $newArr[$value[$keyStr]][] = $value;
        }
        return $newArr;
    }

}