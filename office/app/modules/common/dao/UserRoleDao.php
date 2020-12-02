<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-07
 */

namespace app\common\dao;


use herosphp\model\MysqlModel;

class UserRoleDao extends MysqlModel {

    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('user_role');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}