<?php
/**
 * 应用程序生命周期匹配器
 * ---------------------------------------------------------------------
 * @author yangjian<yangjian102621@gmail.com>
 * @since 2013-05 v1.0.0
 */

namespace herosphp\listener;

use herosphp\core\Loader;
use herosphp\http\HttpRequest;

abstract class WebApplicationListenerMatcher implements IWebAplicationListener {

    /**
     * 跳过监听的 URL
     * @var array
     */
    static protected $SKIP_URLS = [];

    /**
     * 请求初始化之前
     * @return mixed
     */
    public function beforeRequestInit() {
        // TODO: Implement beforeRequestInit() method.
    }

    /**
     * action 方法调用之前
     * @return mixed
     */
    public function beforeActionInvoke(HttpRequest $request) {
        // TODO: Implement beforeActionInvoke() method.
    }

    /**
     * 在控制器的住方法调用之后无论如何也会调用的，比如在控制器调用之后直接die掉，
     * 返回json视图，这样 beforeSendResponse()这个监听器是无法捕获的
     * @param HttpRequest $request
     * @param \herosphp\core\Controller $actionInstance
     * @return mixed
     */
    public function actionInvokeFinally(HttpRequest $request, $actionInstance) {
        // TODO: Implement beforeActionInvoke() method.
    }

    /**
     * 响应发送之前
     * @return mixed
     */
    public function beforeSendResponse(HttpRequest $request, $actionInstance) {
        // TODO: Implement beforeSendResponse() method.
    }

    /**
     * 响应发送之后
     * @return mixed
     */
    public function afterSendResponse($actionInstance) {
        // TODO: Implement afterSendResponse() method.
    }

    /**
     * 检测某个请求是否被监听
     * @param HttpRequest $request
     * @return bool
     */
    /*public function isListening(HttpRequest $request) {

        $uri = "/{$request->getModule()}/{$request->getAction()}/{$request->getMethod()}";
        foreach ( self::$SKIP_URLS as $value ) {
            if ($value == "*") { //所有请求路径都不监听
                return false;
            }
            if (strpos($value, "**") === false) {
                //不包含 ** 通配符，若url在SKIP_URLS里，返回false，允许访问；若若url不在SKIP_URLS里，true，不允许访问
                return !in_array($uri, self::$SKIP_URLS);
            } else { //包含 ** 通配符
                $pos = strpos($value, "**");
                $prefix = substr($value, 0, $pos);
                return (strpos($uri, $prefix) === false);
            }
        }
        return true;
    }*/

    /**
     * 检测某个请求是否被监听
     * @param HttpRequest $request
     * @return bool
     * 我的修改
     */
    public function isListening(HttpRequest $request) {

        $uri = "/{$request->getModule()}/{$request->getAction()}/{$request->getMethod()}"; //请求的路由

        //循环查找跳过的路由
        foreach (self::$SKIP_URLS ?? [] as $SKIP_URL) {
            $match = '/^'; //正则模式匹配表达式
            $skipUrlArr = explode('/', $SKIP_URL);//将跳过skip_url拆分成模块(支持多级模块)，控制器，方法

            //循环添加匹配
            for ($i = 0; $i < sizeof($skipUrlArr); $i++) {
                if ($skipUrlArr[$i] == '**') {
                    //若是通配符，可匹配大小写数字组成的任意的/模块/控制器/方法
                    $match .= '[a-zA-Z1-9]+\/';
                } elseif (preg_match('/^[a-zA-Z1-9]+[\*]{2}$/', $skipUrlArr[$i])) {
                    $match .= substr($skipUrlArr[$i], 0, -2) . '[a-zA-Z1-9]+\/';
                } else {
                    //若不是通配符，只能匹配指定的/模块/控制器/方法
                    $match .= $skipUrlArr[$i] . '\/';
                }

            }
            $match = substr($match, 0, -2); //去除末尾多余的转义/:'\/'
            $match .= '/'; //添加模式匹配表达式结束符
            if (preg_match($match, $uri)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 把地址加入到忽略列表
     * @param $url
     */
    public function skipUrl($url) {
        self::$SKIP_URLS[] = $url;
    }
}
