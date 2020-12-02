<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-07 上午11:18
 * @function
 */

namespace app\admin\action;


use app\api\dateLeave\LeaveManage;
use herosphp\core\Controller;
use herosphp\http\HttpRequest;
use herosphp\rsa\RSACrypt;
use herosphp\utils\JsonResult;

class TestAction extends Controller {

    public function index() {
        $this->assign('public_key', file_get_contents(getConfig('rsa_public_key')));
        $this->setView('test');
    }

    public function testRsa(HttpRequest $post) {
        $password = $post->getParameter('password');
        $rsa = new RSACrypt();

        $decode = $rsa->decryptByPrivateKey($password);

        JsonResult::success($decode, $password)->output();
    }

    public function testLeave() {

        $var[] = "1、正常工作日请假：" . LeaveManage::calculationTime("2020-09-29 12:30", "2020-10-11 17:30", 1);
        //$var[] = "2、带调休请假：" . LeaveManage::calculationTime("2018-12-27 08:30", "2018-12-29 17:30", 1);
        //$var[] = "3、法定节假日请假：" . LeaveManage::calculationTime("2018-09-24 08:30", "2018-09-26 17:30", 1);
        //$var[] = "4、跨年请假：" . LeaveManage::calculationTime("2018-12-29 08:30", "2019-01-02 17:30", 1);

        JsonResult::success('', $var)->output();
    }

}