<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午11:37
 * @function  休假申请记录Dao
 */

namespace app\vacation\dao;


use herosphp\model\MysqlModel;

class VacationDao extends MysqlModel {

    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('vacation');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}