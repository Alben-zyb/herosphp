<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-18 下午5:22
 * @function
 */

namespace app\supply\dao;


use herosphp\model\MysqlModel;

class GoodsCategoryDao extends MysqlModel {
    public function __construct() {
        //创建model对象并初始化数据表名称
        parent::__construct('goodsCategory');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
}