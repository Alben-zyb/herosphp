<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-12 下午2:38
 * @function
 */

namespace app\admin\dao;


use herosphp\model\MysqlModel;

class CalendarDao extends MysqlModel {
    public function __construct() {

        //创建model对象并初始化数据表名称
        parent::__construct('calendar');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'repDate';
    }
}