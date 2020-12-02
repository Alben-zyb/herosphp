<?php
/**
 * * ---------------------------------------------------------------------
 * 应用程序默认生命周期监听器
 * @author yangjian102621@gmail.com
 * ---------------------------------------------------------------------
 * Copyright (c) 2013-now http://www.r9it.com All rights reserved.
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * ---------------------------------------------------------------------
 */

namespace app;

use app\admin\service\IdentifyService;
use herosphp\core\WebApplication;
use herosphp\http\HttpRequest;
use herosphp\listener\IWebAplicationListener;
use herosphp\listener\WebApplicationListenerMatcher;
use herosphp\session\Session;

class DefaultWebappListener extends WebApplicationListenerMatcher implements IWebAplicationListener {

    /**
     * 请求初始化之前
     * @return mixed
     */
    public function beforeRequestInit() {
        //开启session
        Session::start();
        //设置跳过监听的uri, 比如登录页面，注册页面等
        //$this->skipUrl("/user/**"); //跳过用户模块下所有请求
        $this->skipUrl("/admin/identify/**"); //跳过登录控制器所有请求
        $this->skipUrl("/admin/test/**"); //跳过admin模块的测试控制器
        $this->skipUrl("/**/**/welcome"); //跳过任意模块、任意控制器中的welcome方法
        $this->skipUrl("/**/**/test"); //跳过任意模块、任意控制器中的test方法


        $user = IdentifyService::getUser(); //调用服务类的获取登录用户信息

        //用户是否已完善信息
        if ($user['superior']=='0'||$user['departmentHead']=='0') {
            //如果未完善信息，只跳过/home/index/下的方法
            $this->skipUrl("/home/index/**"); //跳过home/index下所有请求
        }
        if($user){
            $this->skipUrl("/home/**"); //跳过home模块下所有请求
            $this->skipUrl("/**/**/get**"); //跳过任意模块、任意控制器中的get**方法
        }

        // TODO: Implement beforeRequestInit() method.
    }

    /**
     * action 方法调用之前
     * @param HttpRequest $request
     * @return mixed
     */
    public function beforeActionInvoke(HttpRequest $request) {
        //获取访问的路由
        $module = $request->getModule(); //获取访问模块
        $controller = $request->getAction(); //获取访问控制器
        $method = $request->getMethod(); //获取访问方法

        $requestUrl = $module . '/' . $controller . '/' . $method;

        $user = IdentifyService::getUser(); //调用服务类的获取登录用户信息
        if(!$user){
            location('/admin/identify/login');
        }
        //用户是否已完善信息
        if ($user['superior']=='0'||$user['departmentHead']=='0') {
            location('/home/index/index');
        }
        //是否是管理员,超级管理员,返回,拥有所有权限
        if ($user['isAdmin']=='1') {
            return;
        }
        //获取用户权限方法
        $methods = IdentifyService::getUserPermissionsByUid($user['id']);

        foreach ($methods as $url) {
            $match = '/^'; //正则模式匹配表达式
            $urlArr = explode('/', $url);//将$url拆分成模块(支持多级模块)，控制器，方法

            //循环添加匹配
            for ($i = 0; $i < sizeof($urlArr); $i++) {
                if ($i==(sizeof($urlArr)-1)) {
                    //最后一个，即方法，匹配前缀（例如：拥有add权限，也即拥有了add*权限，‘*’为通配符）
                    $match .= $urlArr[$i] . '[a-zA-Z1-9]*\/';
                } else {
                    //匹配指定的/模块/控制器
                    $match .= $urlArr[$i] . '\/';
                }

            }
            $match = substr($match, 0, -2); //去除末尾多余的转义/:'\/'
            $match .= '/'; //添加模式匹配表达式结束符
            if (preg_match($match, $requestUrl)) {
                return;
            }
        }

        die('您没有权限!');
    }

    /**
     * 响应发送之前
     * @param HttpRequest $request
     * @param String $actionInstance
     * @return mixed
     */
    public function beforeSendResponse(HttpRequest $request, $actionInstance) {
        $webApp = WebApplication::getInstance();
        //注册当前app的配置信息
        $actionInstance->assign('appConfigs', $webApp->getConfigs());
        $actionInstance->assign('params', $webApp->getHttpRequest()->getParameters());

        //注册当前登录用户信息
        $user = IdentifyService::getUser(); //调用服务类的获取登录用户信息
        //检查是否拥有审核相关任务的权限
        $isAdmin=false;
        foreach ($user['methods'] as $method) {
            if ($method == 'admin/index/index') {
                //拥有后台权限
                $isAdmin = true;
                break; //退出循环
            }
        }
        if($user['isAdmin']){
            $isAdmin = true;
        }
        $loginUser['id']=$user['id'];
        $loginUser['headImg']=$user['headImg'];
        $loginUser['userNo']=$user['userNo'];
        $loginUser['username']=$user['username'];
        $loginUser['isAdmin']=$isAdmin;
        $loginUser['superior']=$user['superior'];
        $loginUser['departmentHead']=$user['departmentHead'];
        $actionInstance->assign('loginUser', $loginUser);

        //注册路由信息
        $actionInstance->assign('public_key',file_get_contents(getConfig('rsa_public_key'))); //传公钥
        $actionInstance->assign('login_url','/admin/identify/loginCheck'); //登录验证路径
        $actionInstance->assign('captcha_url','/admin/identify/getCaptcha'); //验证码路径
    }

    /**
     * 响应发送之后
     * @param String $actionInstance
     * @return mixed
     */
    public function afterSendResponse($actionInstance) {
        // TODO: Implement afterSendResponse() method.

    }

}
