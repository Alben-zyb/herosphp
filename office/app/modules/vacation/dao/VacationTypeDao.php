<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-07 下午7:43
 * @function
 */

namespace app\vacation\dao;


use herosphp\model\MysqlModel;

class VacationTypeDao extends MysqlModel {

    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('vacationType');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}