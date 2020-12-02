<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-21 下午6:01
 * @function
 */

namespace app\home\action;


use app\admin\service\IdentifyService;
use app\common\service\DepartmentService;
use app\common\service\UserService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

class IndexAction extends Controller {

    /**
     * 返回前台视图
     */
    public function index() {
        $this->assign("apiUrl", "/home/index/");
        $this->setView("index");
    }

    /**
     * 返回个人中心视图
     */
    public function userInfo() {
        $this->assign("apiUrl", "/home/index/");
        $this->setView("userInfo");
    }

    /**
     * 返回个人修改密码视图
     */
    public function editPwdView() {
        $this->assign("apiUrl", "/home/index/");
        $this->assign('public_key',file_get_contents(getConfig('rsa_public_key'))); //传公钥
        $this->setView("editPwd");
    }

    /**
     * 获取所有用户，用于下拉选择部门负责人、直接上级
     */
    public function getUserList() {
        $result=UserService::getUserList();
        $result->output();
    }

    /**
     * 获取当前登录用户信息
     */
    public function getUserInfo() {
        $user = IdentifyService::getUser();
        if (!$user) {
            JsonResult::fail('获取失败', $user)->output();//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型
        }
        JsonResult::success('获取成功', $user)->output();
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
     * 获取所有部门，初始化部门显示
     */
    public function getDepartment() {
        $departmentService = Loader::service(DepartmentService::class); //调用部门服务类
        $department = $departmentService->fields('id,departmentName')->find();
        if (!$department) {
            JsonResult::fail('获取失败', $department)->output();//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型
        }
        JsonResult::success('获取成功', $department)->output();
    }

    /**
     * 修改个人数据
     * @param HttpRequest $post
     */
    public function editData(HttpRequest $post) {
        $user = $post->getParameters();

        $userService = Loader::service(UserService::class); //调用用户服务类
        $result = $userService->edit($user);

        if($result->getCode()=='000'){
            IdentifyService::refreshUser(); //更新当前登录用户信息
        }
        $result->output();//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型

    }
    /**
     * 修改个人密码
     * @param HttpRequest $post
     */
    public function editPassword(HttpRequest $post) {
        $user = $post->getParameters();

        $userService = Loader::service(UserService::class); //调用用户服务类
        $result = $userService->editPwd($user);
        $result->output();//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型

    }

    public function uploadHeadImg(HttpRequest $post){
        $imgData=$post->getParameters();
        $userService=Loader::service(UserService::class); //调用用户服务类
        $result=$userService->uploadHeadImg($imgData);
        $result->output();
    }

}