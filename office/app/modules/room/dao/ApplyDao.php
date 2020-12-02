<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-20 下午5:21
 * @function  会议室申请dao
 */

namespace app\room\dao;


use herosphp\model\MysqlModel;

class ApplyDao extends MysqlModel {

    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('roomApply');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}