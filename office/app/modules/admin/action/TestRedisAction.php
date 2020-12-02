<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-26 上午8:52
 * @function
 */

namespace app\admin\action;


use herosphp\utils\JsonResult;

class TestRedisAction {
    protected $redis;
    public function __construct() {
        $this->redis=new \Redis();
        $this->redis->connect('127.0.0.1',6379);
    }

    public function set(){

        JsonResult::success($this->redis->set('name','klc'))->output();

    }

    public function get(){
        JsonResult::success($this->redis->get('name'))->output();
    }
}