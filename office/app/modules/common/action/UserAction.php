<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-01
 */

namespace app\common\action;


use app\common\service\PositionService;
use app\common\service\RoleService;
use app\common\service\UserService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use app\common\service\DepartmentService;
use herosphp\filter\Filter;
use app\common\dao\UserRoleDao;

class UserAction extends Controller {

    /**
     * 返回用户视图
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {
        $positionId = $request->getParameter('positionId');
        $this->assign('positionId', $positionId);
        $this->assign('apiUrl', '/common/user/');
        $this->setView("user/user");
    }


    /**
     * 返回编辑视图
     */
    public function add() {
        //返回编辑视图
        $this->setView("user/add");
    }


    /**
     * 返回编辑视图
     * @param HttpRequest $get
     */
    public function edit(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        //渲染到edit页面
        $this->assign('id', $id);
        $this->setView("user/edit");
    }

    /**
     * 返回角色视图
     * @param HttpRequest $get
     */
    public function role(HttpRequest $get) {
        //获取前端数据
        $id = $get->getParameter('id');
        $userNo = $get->getParameter('userNo');
        $username = $get->getParameter('username');
        //渲染到role页面
        $this->assign('id', $id);
        $this->assign('userNo', $userNo);
        $this->assign('username', $username);
        $this->assign('apiUrl', '/common/user/');
        $this->setView("user/role");
    }

    /**
     * 获取数据(包含条件获取)
     * @param HttpRequest $request
     */
    public function getData(HttpRequest $request) {

        //获取条件参数（分页，查询条件等）
        $searchCondition = $request->getParameters();
        $userService = new UserService();//实例化用户服务类
        $data = $userService->getPageData($searchCondition);//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型

        $data->output();//输出返回
    }

    /**
     * 递归查询部门以及子部门下的员工
     * @param $departmentService
     * @param $departmentId
     * @param $departmentIdList
     * @return bool
     */
    public function getDepartmentByRecursion($departmentService,$departmentId,&$departmentIdList){
        $departments=$departmentService->fields('id')->where('parentId',$departmentId)->find();

        $departmentIdList[]=$departmentId; //先把自身添加到部门列表

        //递归出口,不再有子部门
        if(!$departments){
            return true;
        }
        foreach ($departments as $department) {
            $departmentIdList[]=$department['id'];
            //自身调用
            $this->getDepartmentByRecursion($departmentService,$department['id'],$departmentIdList);
        }
    }
    /**
     * 获取用户下拉列表
     */
    public function getUserList() {
        $result = UserService::getUserList(false);
        $result->output();
    }

    /**
     * 添加数据
     * @param HttpRequest $post
     */
    public function addData(HttpRequest $post) {

        $addData=$post->getParameters();

        $userService = new UserService();//实例化用户服务类


        //组织数据源格式
        $data = array(
            'username' => $addData['username'],
            'userNo' => $addData['userNo'],
            'phone' => $addData['phone'],
            'email' => $addData['email'],
            'departmentId' => $addData['department'],
            'positionId' => $addData['position'],
        );

        $userService->addSubmit($data)->output();
    }

    /**
     * 修改数据
     * @param HttpRequest $post
     */
    public function editData(HttpRequest $post) {
        $userData = $post->getParameters();
        $userService = new UserService();//实例化用户服务类
        $result = $userService->edit($userData);
        $result->output();//框架返回json数据
    }

    /**
     * 修改用户状态
     * @param HttpRequest $post
     */
    public function editStatus(HttpRequest $post) {
        //获取前端数据
        $id = $post->getParameter('id');
        $status = $post->getParameter('status');
        $userService = new UserService();//实例化用户服务类

        $result = $userService->set('status', $status, $id);
        if (!$result) {
            JsonResult::fail('修改失败！', $result)->output();//框架返回json数据
        }
        JsonResult::success('修改成功！', $result)->output();//框架返回json数据
    }


    /**
     * 根据id获取用户信息
     * @param HttpRequest $post
     */
    public function getUserById(HttpRequest $post) {
        //获取前端数据
        $id = $post->getParameter('id');
        $userService = new UserService();//实例化用户服务类

        $result = $userService->findById($id);
        if ($result) {
            JsonResult::success('获取成功！', $result)->output();//框架返回json数据
        }
    }


    /**
     * 删除数据
     * @param HttpRequest $post
     */
    public function delete(HttpRequest $post) {

        //获取删除数据的id
        $ids = $post->getParameter('ids');

        $userService = new UserService();//实例化用户服务类
        $departmentService = new DepartmentService();//实例化部门服务类
        $positionService = new PositionService();//实例化岗位服务类
        $userRoleModel = Loader::model(UserRoleDao::class);//加载用户角色模型类


        $userService->beginTransaction();//开启事务
        $userRoleModel->beginTransaction();//开启事务
        $departmentService->beginTransaction();//开启事务
        $positionService->beginTransaction();//开启事务

        //关联部门，岗位，角色信息
        $result = 1;
        foreach ($ids as $id) {
            $user = $userService->fields('departmentId,positionId')->findById($id);
            //部门人数-1
            $result &= $departmentService->reduce('members', 1, $user['departmentId']);//部门人数，调用reduce自减方法
            //岗位人数-1
            if ($user['positionId'] != 0) {
                $result &= $positionService->reduce('members', 1, $user['positionId']);//岗位人数，调用reduce自减方法
            }
            //查询删除原有角色
            $role = $userRoleModel->where('userId', $id)->findOne();
            if ($role) {
                $result &= $userRoleModel->where('userId', $id)->deletes();
            }
        }

        //删除用户信息
        $result &= $userService->where('id', 'IN', $ids)->deletes();
        if (!$result) {
            //删除失败，回滚操作
            $departmentService->rollback();
            $positionService->rollback();
            $userRoleModel->rollback();
            $userService->rollback();
            JsonResult::fail('删除失败！')->output();//框架返回json数据
        }
        $positionService->commit(); //提交事务
        $departmentService->commit();
        $userRoleModel->commit();
        $userService->commit();
        JsonResult::success('删除成功！', $result)->output();//框架返回json数据
    }


    /**
     * 根据部门id获取新的岗位编号
     * @param $parentDepartmentId
     * @return string
     */
    public function getPositionNo($parentDepartmentId) {
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

    /**
     * 获取所有角色
     */
    public function getRole() {
        $roleService = new RoleService();//实例化角色服务类
        $result = $roleService->fields('id,roleName,detail')->find();
        if (!$result) {
            JsonResult::fail('获取失败！')->output();//框架返回json数据
        }
        JsonResult::success('获取成功！', $result)->output();//框架返回json数据
    }

    /**
     * 获取用户拥有的角色
     * @param HttpRequest $get
     */
    public function getUserRole(HttpRequest $get) {
        //获取用户id
        $id = $get->getParameter('id');
        $userService = new UserService();//实例化用户服务类
        $result = $userService->getRoleByUid($id);
        JsonResult::success('获取成功！', $result)->output();//框架返回json数据
    }

    /**
     * 更新用户角色
     * @param HttpRequest $post
     */
    public function updateUserRole(HttpRequest $post) {
        //获取角色更新数据
        $data = $post->getParameters();

        $id = $data['id'];
        $roleIds = $data['roleIds'];
        $result = 1;
        //使用模型方法
        $userRoleModel = Loader::model(UserRoleDao::class);//加载用户角色模型类


        //更新角色
        //先删除原有角色
        //查询更新
        $oldUserRole = $userRoleModel->fields('id,userId,roleId')->where('userId', '=', $id)->find();

        //遍历更新的角色id
        foreach ($roleIds as $key => $roleId) {
            //遍历旧角色id
            foreach ($oldUserRole as $oldKey => $old) {
                //如果更新角色与旧角色相同,不更新,最终查找出新旧不同的数据
                if ($old['roleId'] == $roleId) {
                    unset($roleIds[$key]);  //剔除相同的角色
                    unset($oldUserRole[$oldKey]);
                    break;
                }
            }
        }
        //删除旧的角色
        $remainId = array();
        foreach ($oldUserRole as $old) {
            $remainId[] = $old['id'];
        }
        if (sizeof($remainId)) {
            $result = $result && $userRoleModel->where('id', 'IN', $remainId)->deletes();  //删除就的角色
        }

        //循环添加新的角色
        foreach ($roleIds as $roleId) {
            $data = [
                'userId' => $id,
                'roleId' => $roleId
            ];
            $result = $result && $userRoleModel->add($data);
        }
        if (!$result) {
            $userRoleModel->rollback();  //事务回滚
            JsonResult::fail('更新失败！')->output();//框架返回json数据
        }

        $userRoleModel->commit();  //提交事务
        JsonResult::success('更新成功！', $result)->output();//框架返回json数据
    }


}
