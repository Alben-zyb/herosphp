<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-19 上午10:07
 * @function
 */

namespace app\supply\service;


use app\api\service\OperatorService;
use app\supply\dao\StorehouseDao;
use herosphp\model\CommonService;
use herosphp\filter\Filter;
use herosphp\utils\JsonResult;

class StorehouseService extends CommonService {

    //属性
    protected $modelClassName = StorehouseDao::class;

    /**
     * 获取出库物品信息
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @param string $position (前台/后台)
     * @return JsonResult
     */
    public function getPageData($search = array(),$position='home') {

        //判断是否存在分页
        if (!$search['page']) {
            $search['page'] = 1;
        }
        if (!$search['limit']) {
            $search['limit'] = 10;
        }
        //判断前台后台
        if($position=='home'){
            $order='goodsNo asc';
        }else{
            $order='applyNumber desc';
        }
        //数据过滤规则

        $filterCategory = array(
            'category' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类数据类型有误！")
            ));
        $filterRoomName = array(
            'goodsName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品名称数据类型有误！")
            ));

        $filterMin = array(
            'min' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));
        $filterMax = array(
            'max' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));
        //前端输入的查询条件
        $searchCategory = array(
            'category' => $search['category']
        );
        $searchRoomName = array(
            'goodsName' => $search['goodsName']
        );

        $searchMin = array(
            'min' => $search['min'],
        );
        $searchMax = array(

            'max' => $search['max'],
        );


        $searchCondition = array();//查询的最终条件

        //物品编号条件过滤添加
        if ($result = Filter::loadFromModel($searchCategory, $filterCategory, $error)) {
            $searchCondition[] = array('category', '=', $result['category']);
        }
        //物品名称条件过滤添加
        if ($result = Filter::loadFromModel($searchRoomName, $filterRoomName, $error)) {
            $searchCondition[] = array('goodsName', 'LIKE', '%' . $result['goodsName'] . '%');
        }


        //最小数量条件过滤添加
        if ($result = Filter::loadFromModel($searchMin, $filterMin, $error)) {
            $minPass = $result['min'];
            $searchCondition[] = array('number', '>=', $minPass);
        }

        //最大数量条件过滤添加
        if ($result = Filter::loadFromModel($searchMax, $filterMax, $error)) {
            $maxPass = $result['max'];
            if ($maxPass != 0) {
                $searchCondition[] = array('number', '<', $maxPass);
            }

        }

        //时间条件，配合时间类型：添加｜更新　使用
        if ($search['start']) {
            $searchCondition[] = array($search['timeType'], '>', $search['start']);

        }
        if ($search['end']) {
            $searchCondition[] = array($search['timeType'], '<', $search['end']);
        }
        //添加软删除标记
        $searchCondition[] = array('delete_time', 'NULL');

        //连接查询数量(包含采购员条件)
        $count = $this->whereArr($searchCondition)
            ->count();//获取数据总条数（加入查询条件）

        if ($count < $search['limit']) {
            $search['page'] = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }

        //添加软删除标记


        //连接查询(包含采购员信息)
        $data = $this->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->order($order)
            ->find();//查询数据

        //查询分类信息
        $category = GoodsCategoryService::getListData(); //调用分类服务类
        $categoryArr = array();
        foreach ($category as $item) {
            $categoryArr[$item['id']] = $item['categoryNo'] . '--' . $item['categoryName'];
        }

        //循环合并数组
        foreach ($data as $key => &$value) {
            $value['category'] = $categoryArr[$value['category']];
            $value['create_time'] = substr($value['create_time'], 0, 16);
            $value['update_time'] = substr($value['update_time'], 0, 16);
            //有出入库记录,添加二级表格标记
            if($value['applyNumber']||$value['number']){
                $value['haveChild'] = 'true';
            }else{
                $value['haveChild'] = 'false';
            }
        }

        return JsonResult::layTable($data, $count); //返回JsonResult对象数据
    }


    /**
     * 根据id返回物品信息
     * @param $id
     * @return JsonResult
     */
    public function getDataById($id) {
        if (is_numeric($id)) {
            $result = $this->findById($id);
            if ($result) {
                return JsonResult::success('获取成功', $result);
            }
        }
        return JsonResult::fail('获取失败');
    }

    /**
     * 根据物品分类编号(categoryNo)构建新的物品编号
     * @param $categoryId
     * @return string
     */
    public function createNewGoodsNo($categoryId) {
        //查找分类id对应的分类编号
        $category = GoodsCategoryService::getDataById($categoryId);
        $goods = $this->fields('goodsNo')
            ->where('goodsNo', 'LIKE', $category['categoryNo'] . '%')
            ->order('goodsNo desc')
            ->findOne(); //调用物品服务类

        if ($goods) {
            //分类下已存在物品
            $oldGoodsNo = $goods['goodsNo'];
            $newGoodsNum = substr($oldGoodsNo, 1, 3) + 1;
            switch (strlen($newGoodsNum)) {
                case 1:
                    $newGoodsNum = '00' . $newGoodsNum;
                    break;
                case 2:
                    $newGoodsNum = '0' . $newGoodsNum;
                    break;
                case 3:
                    $newGoodsNum = '' . $newGoodsNum;
                    break;
                default:
                    JsonResult::fail('添加失败！物品编号超出范围')->output();//框架返回json数据
                    break;
            }
            $newGoodsNo = $category['categoryNo'] . $newGoodsNum;
        } else {
            //分类下不存在物品
            $newGoodsNo = $category['categoryNo'] . '001';
        }
        return $newGoodsNo;
    }


    /**
     * 添加数据
     * @param $data
     * @return JsonResult
     */
    public function addSubmit($data) {

        //数据过滤
        $filter = array(
            'category' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品编号数据类型有误！")
            ),
            'goodsNo' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品编号数据类型有误！")
            ),
            'goodsName' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品名称据类型有误！")
            ),
            'unit' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品单位数据类型有误！")
            ),
        );

        //检验数据
        $data = Filter::loadFromModel($data, $filter, $error);
        //校验失败
        if (!$data) {
            return JsonResult::fail('数据类型有误');
        }

        OperatorService::addOperator($data);
        $result = $this->add($data);
        if (!$result) {
            return JsonResult::fail('添加失败');
        }
        return JsonResult::success('添加成功');
    }

    /**
     * 修改数据
     * @param $data
     * @return JsonResult
     */
    public function editSubmit($data) {

        //数据过滤
        $filter = array(
            'id' => array(
                Filter::DFILTER_NUMERIC, NULL, NULL, array("type" => "物品id数据类型有误！")
            ),
            'category' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品编号数据类型有误！")
            ),
            'goodsNo' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品编号据类型有误！")
            ),
            'goodsName' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品名称据类型有误！")
            ),
            'unit' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品单位数据类型有误！")
            ),
        );

        //检验数据
        $data = Filter::loadFromModel($data, $filter, $error);
        //校验失败
        if (!$data) {
            return JsonResult::fail('数据类型有误');
        }

        OperatorService::addOperator($data);
        $result = $this->update($data, $data['id']);
        if (!$result) {
            return JsonResult::fail('修改失败');
        }
        return JsonResult::success('修改成功');
    }

    /**
     * 删除数据
     * @param $ids
     * @return JsonResult
     */
    public function deleteCommit($ids) {
        //查询删除(软删除)
        $result = $this->where('id', 'IN', $ids)
            ->where('number', '>', '0')
            ->findOne();
        if ($result) {
            return JsonResult::fail('删除失败(只能删除库存为0的物品)');
        }
        //软删除(设置删除时间)
        $result = $this->where('id', 'IN', $ids)
            ->where('number', '0')
            ->sets('delete_time', date('Y-m-d H:i'));
        if (!$result) {
            return JsonResult::fail('删除失败');
        }
        return JsonResult::success('删除成功');
    }
}