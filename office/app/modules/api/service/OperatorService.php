<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-05 上午8:59
 * @function  操作者服务接口
 */

namespace app\api\service;


use app\admin\service\IdentifyService;

class OperatorService extends IdentifyService {

    /**
     * 静态方法，往源数据里添加当前登录用户（操作者）
     * @param $data
     */
    public static function addOperator(&$data){
        $loginUser=(new self())::getUser();
        $data['operatorId']=$loginUser['id'];
        $data['operator']=$loginUser['username'];
        $data['update_time']=date('Y-m-d H:i');
    }
}