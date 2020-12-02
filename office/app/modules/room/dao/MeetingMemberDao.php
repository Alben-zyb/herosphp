<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-21 上午11:48
 * @function  参会人员Dao
 */

namespace app\room\dao;


use herosphp\model\MysqlModel;

class MeetingMemberDao extends MysqlModel {
    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('meetingMember');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}