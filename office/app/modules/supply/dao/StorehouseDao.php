<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-19 上午10:08
 * @function
 */

namespace app\supply\dao;


use herosphp\model\MysqlModel;

class StorehouseDao extends MysqlModel {

    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('goods');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}