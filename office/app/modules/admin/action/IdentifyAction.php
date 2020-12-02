<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-11 上午11:25
 * @function  身份验证类(注册登录)
 */

namespace app\admin\action;


use app\admin\service\IdentifyService;
use app\common\service\DepartmentService;
use app\common\service\PositionService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use app\admin\utils\Captcha;


class IdentifyAction extends Controller
{

    protected $identifyService; //当前控制器的服务类
    protected $user; //当前登录用户

    /**
     * 构造方法
     * IdentifyAction constructor.
     */
    public function __construct()
    {
        parent::__construct(); //执行父类构造方法

        $this->identifyService = Loader::service(IdentifyService::class); //身份验证服务类
        $this->user = $this->identifyService->getUser();  //获取当前登录用户
    }


    /**
     * 登录视图
     * @author ZhengYiBin<zhengyb@pvc123.com>
     * @date   2020-09-04 下午1:36
     */
    public function login()
    {
        //判断用户是否已登录,若已登录,跳转
        if ($this->identifyService->getUser()) {
            location('/home/index/index');//跳转前台界面
        }
        $this->assign('public_key', file_get_contents(getConfig('rsa_public_key'))); //传公钥
        $this->assign('login_url', '/admin/identify/loginCheck'); //登录验证路径
        $this->assign('register_url', '/admin/identify/register'); //注册路径
        $this->assign('captcha_url', '/admin/identify/getCaptcha'); //验证码路径
        $this->assign('forget_url', '/admin/identify/forget'); //忘记密码路径
        $this->assign('autoLogin_url', '/admin/identify/autoLogin'); //自动登录
        $this->setView("identify/login");

    }


    /**
     * 注册方法:返回注册视图
     * 登录手机号:phone
     * 密码:password
     * 验证码:captcha
     * 记住登录:remember
     *
     * @author ZhengYiBin<zhengyb@pvc123.com>
     * @date   2020-09-04 下午1:36
     */
    public function register()
    {
        $this->assign('public_key', file_get_contents(getConfig('rsa_public_key'))); //传公钥
        $this->assign('toLogin_url', '/admin/identify/login'); //登录路径
        $this->assign('registerCheck_url', '/admin/identify/registerCheck'); //注册验证路径
        $this->assign('phoneCode_url', '/admin/identify/sentPhoneCode'); //验证码路径
        $this->assign('department_url', '/admin/identify/getDepartment'); //部门获取路径
        $this->assign('position_url', '/admin/identify/getPositionByDepartment'); //岗位获取路径
        $this->setView("identify/register");

    }

    /**
     * 登录验证
     * @param HttpRequest $request
     * 登录手机号:phone
     * 密码:password
     * 验证码:captcha
     * 记住登录:remember
     * @return void
     *
     * @author ZhengYiBin<zhengyb@pvc123.com>
     * @date   2020-09-04 下午1:47
     */
    public function loginCheck(HttpRequest $request)
    {

        $loginData = $request->getParameters(); //登录信息
        $this->identifyService->loginCheck($loginData)->output(); //验证输出结果

    }

    /**
     * 自动登录验证
     * token
     * 记住登录:remember
     * @return void
     *
     * @author ZhengYiBin<zhengyb@pvc123.com>
     * @date   2020-09-04 下午1:52
     */
    public function autoLogin()
    {
        $this->identifyService->autoLoginCheck()->output(); //验证输出结果
    }

    /**
     * 注册验证
     * @param HttpRequest $request
     * 注册手机号:phone
     * 密码:password
     * 验证码:phoneCode
     * @return void
     *
     * @author ZhengYiBin<zhengyb@pvc123.com>
     * @date   2020-09-04 下午2:06
     */
    public function registerCheck(HttpRequest $request)
    {

        $registerData = $request->getParameters(); //注册信息

        $this->identifyService->registerCheck($registerData)->output(); //输出
    }


    /**
     * 返回忘记密码找回界面
     */
    public function forget()
    {
        $this->assign('toLogin_url', '/admin/identify/login'); //登录路径
        $this->assign('phoneCode_url', '/admin/identify/sentPhoneCode'); //验证码路径
        $this->assign('checkUserPhone_url', '/admin/identify/checkUserPhone'); //验证用户手机号
        $this->assign('resetPassword_url', '/admin/identify/resetPassword'); //重置密码
        $this->setView("identify/forget");
    }

    /**
     * 验证用户手机号是否存在(即已注册)
     * @param HttpRequest $request
     */
    public function checkUserPhone(HttpRequest $request)
    {
        $phone = $request->getParameter('phone'); //获取参数
        $result = $this->identifyService->where('phone', $phone)->findOne();
        if (!$result) {
            JsonResult::fail('用户手机号不存在')->output();
        }
        JsonResult::success('验证成功')->output();
    }

    /**
     * 重置密码
     * @param HttpRequest $request
     */
    public function resetPassword(HttpRequest $request)
    {
        $resetPwdData = $request->getParameters(); //重置密码信息
        $this->identifyService->resetPassword($resetPwdData)->output();
    }

    /**
     * 获取当前登录用户信息
     */
    public function getUser()
    {
        $user = $this->identifyService->getUser();
        JsonResult::success('获取成功!', $user)->output();
    }


    /**
     * 生成并返回验证码图片(登录)
     */
    public function getCaptcha()
    {
        return Captcha::getCaptcha();
    }

    /**
     * 发送手机验证码(注册/重置密码)
     * @param HttpRequest $request
     */
    public function sentPhoneCode(HttpRequest $request)
    {
        $data = $request->getParameters(); //手机号信息
        $phone = $data['phone'];
        $this->identifyService->sentPhoneCode($phone)->output(); //发送手机验证码

    }

    /**
     * 根据部门id获取岗位信息
     * @param HttpRequest $request
     */

    public function getPositionByDepartment(HttpRequest $request)
    {
        //接收数据
        $departmentId = $request->getParameter('departmentId');
        $positionService = new PositionService();//实例化岗位服务类
        $positionService->getPositionByDepartment($departmentId)->output();
    }

    /**
     * 获取部门数据信息
     */
    public function getDepartment()
    {

        $departmentService = new DepartmentService();//实例化部门服务类

        $data = $departmentService->getDepartmentTree();//获取数据

        JsonResult::layTable($data)->output();//输出
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->identifyService->unsetUser();//退出当前登录用户
        location("/admin/identify/login");
    }
}