<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-27 下午4:20
 * @function
 */

namespace app\admin\service;


use app\admin\utils\Captcha;
use app\admin\utils\Lang;
use app\api\service\MailerService;
use app\api\service\SMSService;
use app\common\service\UserService;
use herosphp\cache\utils\RedisUtils;
use herosphp\filter\Filter;
use herosphp\rsa\RSACrypt;
use herosphp\utils\JsonResult;

class IdentifyService extends UserService {

    /**
     * 获取当前登录用户信息
     */
    public static function getUser() {
        if (!isset($_SESSION['user'])) {
            return false;
        }
        return $_SESSION['user'];
    }

    /**
     * 设置当前登录用户信息
     * @param $user
     */
    public static function setUser($user) {
        $user['headImg'] = getConfig('headUrl') . $user['headImg'];
        $_SESSION['user'] = $user;
    }

    /**
     * 更新当前登录用户信息
     */
    public static function refreshUser() {

        if (isset($_SESSION['user'])) {
            $Uid = $_SESSION['user']['id'];
            $user = (new self())->fields('id,headImg,userNo,username,phone,email,status,isAdmin,departmentId,positionId,superior,departmentHead')
                ->findById($Uid);
            self::setUser($user);
        }
    }

    /**
     * 退出当前登录用户信息
     */
    public static function unsetUser() {

        if (isset($_SESSION['user'])) {
            //清除redis
            $redis=RedisUtils::getInstance();
            $redis->del($_SESSION['user']['phone']);
            //清除session
            unset($_SESSION['user']);
        }
    }

    /**
     * 登录验证
     * @param $loginData
     * @return JsonResult
     */
    public function loginCheck($loginData) {

        $rsa = new RSACrypt(); //实例化RSA加密解密类
        $captcha=$loginData['captcha']; //验证码
        $phone = $rsa->decryptByPrivateKey($loginData['phone']);; //手机号(解密)
        $password = $rsa->decryptByPrivateKey($loginData['password']);; //密码(解密)

        //验证过滤
        $loginData = [
            'phone' => $phone,
            'password' => $password,
        ];
        //登录信息验证规则
        $filterMap = array(
            'phone' => array(Filter::DFILTER_MOBILE, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "请输入正确的手机号码")),
            'password' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM, array("require" => "密码不能为空"))
        );


        //检查验证码是否正确
        if (!Captcha::checkCaptcha($captcha)) {
            return JsonResult::fail('验证码错误！请重新登录');
        }

        //检验数据格式
        $loginData = Filter::loadFromModel($loginData, $filterMap, $error);
        if (!$loginData) {
            return JsonResult::fail(Lang::LOGIN_FAIL);
        }

        //数据登录验证
        $checkData=[
            ['phone','=',$phone],
            ['password','=',md5($password)],
        ];
        $result = $this->fields('id,headImg,userNo,username,phone,email,status,isAdmin,departmentId,positionId,superior,departmentHead')
            ->whereArr($checkData)->find(); //登录验证
        $user = $result[0];

        if ($user) {
            if ($user['status'] == '0') {
                //status=0,禁用
                return JsonResult::fail(Lang::USER_FORBID, '/admin/identify/login');
            }
            //登录成功,保存用户信息到session
            $methods = $this->getUserPermissionsByUid($user['id']);
            $user['methods'] = $methods; //增加methods路由路径字段(数组)
            $this->setUser($user);//保存当前登录用户到session
            //set cookie
            $token=md5($user['id']);
            setcookie('token',$token,time()+7*24*3600);
            //存redis
            $redis=RedisUtils::getInstance(); //获取redis实例
            $redis->set($user['phone'],$token);
            return JsonResult::success(Lang::LOGIN_SUCCESS, '/home/index/index'); //成功，返回跳转路径

        } else {
            return JsonResult::fail(Lang::LOGIN_FAIL, '/admin/identify/login');
        }
    }

    /**
     * 自动登录
     * @return JsonResult
     */
    public function autoLoginCheck(){
        $redis=RedisUtils::getInstance(); //获取redis实例
        $token=$_COOKIE['token'];
        $phone=$_COOKIE['phone'];
        $redisToken=$redis->get($phone);
        if($redisToken==''||$redisToken==null){
            return JsonResult::fail('自动登录失败');
        }
        if($token==''||$token==null){
            return JsonResult::fail('自动登录失败');
        }
        if($redisToken==$token){
            $result = $this->fields('id,headImg,userNo,username,phone,email,status,isAdmin,departmentId,positionId,superior,departmentHead')
                ->where('phone',$phone)->find(); //登录验证
            $user = $result[0];
            self::setUser($user);
            return JsonResult::success('验证成功','/home/index/index');
        }
        return JsonResult::fail('自动登录失败');
    }

    /**
     * 注册验证
     * @param $registerData
     * @return JsonResult
     */
    public function registerCheck($registerData) {

        $rsa = new RSACrypt(); //实例化RSA加密解密类
        $phone = $rsa->decryptByPrivateKey($registerData['phone']);; //手机号(解密)
        $password = $rsa->decryptByPrivateKey($registerData['password']);; //密码(解密)

        //检查验证码是否正确
        if (!Captcha::checkPhoneCode($registerData['phoneCode'])) {
            return JsonResult::fail('验证码错误！请重新注册');
        }


        //验证过滤
        $checkData = [
            'phone' => $phone,
            'password' => $password,
        ];
        //注册信息验证规则
        $filterMap = array(
            'phone' => array(Filter::DFILTER_MOBILE, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "请输入正确的手机号码")),
            'password' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM, array("require" => "密码不能为空"))
        );

        //验证过滤
        $result = Filter::loadFromModel($checkData, $filterMap, $error);

        if (!$result) {
            //验证失败
            return JsonResult::fail(Lang::REGISTER_FAIL);
        }
        //组织数据源格式
        $data = array(
            'username' => $registerData['username'],
            'userNo' => $registerData['userNo'],
            'phone' => $result['phone'],
            'password' => $result['password'],
            'email' => $registerData['email'],
            'departmentId' => $registerData['department'],
            'positionId' => $registerData['position'],
        );
        return $this->addSubmit($data);
    }

    /**
     * 根据用户id获取权限信息
     * @param $Uid
     * @return array
     */
    public static function getUserPermissionsByUid($Uid) {
        $methods = array(); //存放方法的路由路径
        $result = (new self())->getUserPermissionByUid($Uid);
        //只提取method字段
        foreach ($result as $value) {
            $methods[] = $value['method'];
        }
        return $methods; //返回结果
    }

    /**
     * 发送手机验证码(注册/重置密码)
     * @param $phone
     * @return JsonResult
     */
    public function sentPhoneCode($phone) {
        $rsa = new RSACrypt(); //实例化RSA加密解密类
        $phone = $rsa->decryptByPrivateKey($phone);; //手机号(解密)
        $phoneCode = Captcha::getPhoneCode();
        $email = getConfig('email'); //默认验证邮箱
        //查询是否存在用户邮箱
        $user = $this->fields('email')->where('phone', $phone)->findOne();
        if ($user && $user['email']) {
            $email = $user['email'];
        }
        //调用短信接口发送验证码
        SMSService::sendSMS($phone, $phoneCode);

        //调用邮件接口发送验证码
        $result = MailerService::sendEmail($email, '验证码信息', '你的验证码为:<span style="color: #1ece6d">' . $phoneCode . '</span>,不要告诉任何人哦!');
        if (!$result) {
            return JsonResult::fail('发送失败', [$email, $phone]);

        }

        return JsonResult::success('发送成功', [$email, $phone]);
    }


    /**
     * 忘记密码(重置密码)
     * @param $resetPwdData
     * @return JsonResult
     */
    public function resetPassword($resetPwdData) {
        //检查验证码是否正确
        if (!Captcha::checkPhoneCode($resetPwdData['phoneCode'])) {
            return JsonResult::fail('验证码错误');
        }

        $rsa = new RSACrypt(); //实例化RSA加密解密类
        $phone = $rsa->decryptByPrivateKey($resetPwdData['phone']);; //手机号(解密)
        $password = $rsa->decryptByPrivateKey($resetPwdData['password']);; //密码(解密)

        //验证过滤
        $checkData = [
            'phone' => $phone,
            'password' => $password,
        ];
        //注册信息验证规则
        $filterMap = array(
            'phone' => array(Filter::DFILTER_MOBILE, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "请输入正确的手机号码")),
            'password' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM, array("require" => "密码不能为空"))
        );

        //验证过滤
        $result = Filter::loadFromModel($checkData, $filterMap, $error);

        if (!$result) {
            //验证失败
            return JsonResult::fail(Lang::MD_PASS_FAIL);
        }
        $result = $this->where('phone', $result['phone'])->sets('password', md5($result['password']));
        if (!$result) {
            return JsonResult::fail('密码重置失败');
        }
        return JsonResult::success('密码重置成功');
    }
}