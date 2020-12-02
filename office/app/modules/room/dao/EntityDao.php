<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-15 下午1:35
 * @function  会议室实体
 */

namespace app\room\dao;


use herosphp\model\MysqlModel;

class EntityDao extends MysqlModel {

    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('room');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}