<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-19 下午8:14
 * @function
 */

namespace app\supply\dao;


use herosphp\model\MysqlModel;

class GoodsRecordDao extends MysqlModel {
    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('goodsRecord');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}