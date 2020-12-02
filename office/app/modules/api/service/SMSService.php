<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-03 上午9:19
 * @function  短信服务接口类
 */

namespace app\api\service;


class SMSService {

    //静态属性,静态调用
    static public $SMS; //短信验证码对象

    /**
     * 构造方法
     * Mailer constructor.
     */
    public function __construct() {
        //初始化本地服务器信息
        self::$SMS = array();
    }

    /**
     * 发送短信
     * @param $recipients
     * @param $message
     * @return bool
     */
    static public function sendSMS($recipients, $message) {

        new self(); //实例化本身
        self::$SMS[] = $recipients;
        self::$SMS[] = $message;
        if(true){
            return true;
        }
        return false;

    }
}