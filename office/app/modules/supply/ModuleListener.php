<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午11:20
 * @function  当前模块请求的生命周期监听器
 */

namespace app\supply;

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
        //die('您没有权限!');
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
