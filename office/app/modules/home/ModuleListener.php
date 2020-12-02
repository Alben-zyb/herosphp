<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-21 下午6:09
 * @function  当前模块请求的生命周期监听器
 */

namespace app\home;


use herosphp\http\HttpRequest;
use herosphp\listener\IWebAplicationListener;
use herosphp\listener\WebApplicationListenerMatcher;

class ModuleListener extends WebApplicationListenerMatcher implements IWebAplicationListener {

    /**
     * action 方法调用之前
     * @param HttpRequest $request
     * @return mixed
     */
    public function beforeActionInvoke(HttpRequest $request) {
        //权限认证的代码可以写在这里
        //die("您没有权限。");
        //获取访问的路由
        /*$module=$request->getModule(); //获取访问模块
        $controller=$request->getAction(); //获取访问控制器
        $method=$request->getMethod(); //获取访问方法

        Session::start(); //开启session
        $requestUrl=$module+'/'+$controller+'/'+$method;
        $user=$_SESSION['user'];
        foreach ($user['methods'] as $item) {
            if($requestUrl==$item){
                return;
            }
        }
        die('您没有权限!');*/
    }

    /**
     * 响应发送之前
     * @param HttpRequest $request
     * @param $actionInstance
     * @return mixed
     */
    public function beforeSendResponse(HttpRequest $request, $actionInstance) {

    }

    /**
     * 响应发送之后
     * @param $actionInstance
     * @return mixed
     */
    public function afterSendResponse($actionInstance) {
        // TODO: Implement afterSendResponse() method.
    }

}
