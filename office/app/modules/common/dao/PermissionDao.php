<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-04
 */

namespace app\common\dao;


use herosphp\model\MysqlModel;

class PermissionDao extends MysqlModel {

    public function __construct() {

        //创建model对象并初始化数据表名称
        parent::__construct('permission');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';

    }

}