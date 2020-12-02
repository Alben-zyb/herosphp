<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-19 下午8:17
 * @function
 */

namespace app\supply\service;


use app\api\service\OperatorService;
use app\supply\dao\GoodsRecordDao;
use herosphp\core\Loader;
use herosphp\model\CommonService;
use herosphp\filter\Filter;
use herosphp\utils\JsonResult;

class GoodsRecordService extends CommonService {

    //属性
    protected $modelClassName = GoodsRecordDao::class;
    protected $goodsService;

    //构造方法
    public function __construct() {
        parent::__construct();
        //初始化仓库物品服务类
        $this->goodsService = Loader::service(StorehouseService::class);
    }

    /**
     * 获取物品流水记录
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @param boolean $levelFlag
     * @return JsonResult
     */
    public function getInOutHousePageData($search = array(), $levelFlag = false) {

        $goodsCategoryArr = array(); //存放id=>category数组
        $goodsNoArr = array(); //存放id=>goodsNo数组
        $goodsNameArr = array(); //存放id=>goodsName数组
        //判断是否存在分页
        if (!$search['page']) {
            $search['page'] = 1;
        }
        if (!$search['limit']) {
            $search['limit'] = 10;
        }
        //数据过滤规则

        //分类
        $filterCategory = array(
            'category' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请选择一个整数")
            ));
        //物品名称
        $filterGoodsName = array(
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
        $filterHandler = array(
            'handler' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "操作人数据类型有误！")
            ));

        $filterOperate = array(
            'operate' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));
        $filterStatus = array(
            'status' => array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));

        //前端输入的查询条件

        $searchCategory = array(
            'category' => $search['category'],
        );
        $searchGoodsName = array(

            'goodsName' => $search['goodsName'],
        );
        $searchMin = array(
            'min' => $search['min'],
        );
        $searchMax = array(

            'max' => $search['max'],
        );
        $searchHandler = array(
            'handler' => $search['handler'],
        );

        $searchOperate = array(
            'operate' => $search['operate'],
        );

        //如果搜索的时间类型是领取时间,则设置数据状态为已领取
        if ($search['timeType'] == 'update_time') {
            $search['status'] = '2';
        }
        $searchStatus = array(
            'status' => $search['status'],
        );


        $searchCondition = array();//查询的最终条件

        if (is_numeric($search['id'])) {
            $searchCondition[] = array('goodsId', '=', $search['id']);
        }

        //物品分类条件过滤添加
        if ($result = Filter::loadFromModel($searchCategory, $filterCategory, $error)) {
            //调用物品仓库服务类,查询物品对应id
            $data = $this->goodsService->fields('id')->where('category', $result['category'])->find(); //查询

            //组织查询数据成一维id数组
            $idsArr = array();
            if ($data) {
                //有数据
                foreach ($data as $goods) {
                    $idsArr[] = $goods['id'];
                }
            }
            $searchCondition[] = array('goodsId', 'IN', $idsArr);
        }
        //物品名称条件过滤添加
        if ($result = Filter::loadFromModel($searchGoodsName, $filterGoodsName, $error)) {
            //调用物品仓库服务类,查询物品对应id
            $data = $this->goodsService->fields('id')->where('goodsName', 'LIKE', '%' . $result['goodsName'] . '%')->find(); //查询

            //组织查询数据成一维id数组
            $idsArr = array('0');
            if ($data) {
                //有数据
                foreach ($data as $goods) {
                    $idsArr[] = $goods['id'];
                }
            }
            $searchCondition[] = array('goodsId', 'IN', $idsArr);
        }

        //最小数量条件过滤添加
        if ($result = Filter::loadFromModel($searchMin, $filterMin, $error)) {
            $searchCondition[] = array('number', '>=', $result['min']);
        }

        //最大数量条件过滤添加
        if ($result = Filter::loadFromModel($searchMax, $filterMax, $error)) {
            if ($result['max'] != 0) {
                $searchCondition[] = array('number', '<', $result['max']);
            }

        }
        //操作人(数据关联用户)过滤添加
        if ($result = Filter::loadFromModel($searchHandler, $filterHandler, $error)) {
            if (is_numeric($result['handler'])) {
                $searchCondition[] = array('b.userNo', '=', $result['handler']);
            } else {
                $searchCondition[] = array('b.username', 'LIKE', '%' . $result['handler'] . '%');
            }

        }
        //操作条件(入库/出库)过滤添加
        if ($result = Filter::loadFromModel($searchOperate, $filterOperate, $error)) {
            if ($result['operate'] != -1) {
                $searchCondition[] = array('operate', '=', $result['operate']);

            }
        }
        //状态条件(已入库/已出库:已申领)过滤添加
        if ($search['timeType'] == 'create_time') {
            $result['status'] = '2';
        }
        if ($result = Filter::loadFromModel($searchStatus, $filterStatus, $error)) {
            if ($result['status'] != -1) {
                $searchCondition[] = array('a.status', '=', $result['status']);
            }
        }

        //时间条件，配合时间类型：添加｜更新使用
        if ($search['start']) {
            $searchCondition[] = array('a.' . $search['timeType'], '>', $search['start']);

        }
        if ($search['end']) {
            $searchCondition[] = array('a.' . $search['timeType'], '<', $search['end']);
        }

        //连接查询数量(包含操作人条件)
        $count = $this->alias('a')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.handler=b.id')
            ->whereArr($searchCondition)
            ->count();//获取数据总条数（加入查询条件）

        if ($count < $search['limit']) {
            $search['page'] = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }

        //连接查询(包含操作人信息)
        $data = $this->alias('a')
            ->fields('a.*,b.username as handler')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.handler=b.id')
            ->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->order('update_time desc')
            ->find();//查询数据

        //
        //提取查询结果数据的goodsId,构成一维goodsId数组
        $goodsIdsArr = array('0');
        if ($data) {
            //有数据
            foreach ($data as $goodsRecord) {
                $goodsIdsArr[] = $goodsRecord['goodsId'];
            }
            //调用物品仓库服务类,查询物品对应id的物品数据
            $goods = $this->goodsService->fields('id,category,goodsNo,goodsName')->where('id', 'IN', $goodsIdsArr)->find(); //查询
            if ($goods) {
                foreach ($goods as $item) {
                    $key = $item['id'];
                    $goodsNoArr[$key] = $item['goodsNo'];
                    $goodsCategoryArr[$key] = $item['category'];
                    $goodsNameArr[$key] = $item['goodsName'];
                }
            }
        }


        //查询分类信息
        $category = GoodsCategoryService::getListData(); //调用分类服务类
        $categoryArr = array();
        foreach ($category as $item) {
            $categoryArr[$item['id']] = $item['categoryNo'] . '--' . $item['categoryName'];
        }

        //重新整合数据,将每一条数据的每一个数据项的key换成flow_key,同时构建数据
        foreach ($data as $key => &$item) {
            $item['goodsNo'] = $goodsNoArr[$item['goodsId']];
            $item['category'] = $categoryArr[$goodsCategoryArr[$item['goodsId']]];
            $item['goodsName'] = $goodsNameArr[$item['goodsId']];
            $item['create_time'] = substr($item['create_time'], 0, 16);
            $item['update_time'] = substr($item['update_time'], 0, 16);
            if ($levelFlag) {
                foreach ($item as $key => $value) {
                    $newKey = 'flow_' . $key;
                    $item[$newKey] = $value;
                    unset($item[$key]);
                }
            }
        }

        return JsonResult::layTable($data, $count); //返回JsonResult对象数据
    }

    /**
     * 添加数据(入库/出库)
     * @param $data
     * @return JsonResult
     */
    public function addSubmit($data) {
        //数据过滤
        $filter = array(
            'goodsId' => array(
                Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "物品id数据类型有误！"
                )
            ),
            'handler' => array(
                Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "操作人数据类型有误！")
            ),
            'number' => array(
                Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "物品数量据类型有误！")
            ),
            'operate' => array(
                Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM, array("type" => "操作标记数据类型有误！")
            ),
            'supplier' => array(
                Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "供应商数据类型有误！")
            ),
        );

        //检验数据
        $data = Filter::loadFromModel($data, $filter, $error);
        //校验失败
        if (!$data) {
            return JsonResult::fail('数据类型有误');
        }

        OperatorService::addOperator($data); //添加操作者(系统登录用户)
        if ($data['operate'] == '1') {
            //默认入库成功
            $data['status'] = '2';
        }
        //开启事务
        $this->beginTransaction();
        $this->goodsService->beginTransaction();
        $result = $this->add($data); //添加数据

        //更新商品库存
        if ($data['operate'] == '0') {
            $data['number'] = -$data['number']; //设置为负:出库,减少
        }

        $result *= $this->goodsService->increase('number', $data['number'], $data['goodsId']);

        if (!$result) {
            //添加失败
            //事务回滚
            $this->goodsService->rollback();
            $this->rollback();
            return JsonResult::fail('添加失败');
        }

        //提交事务
        $this->goodsService->commit();
        $this->commit();
        return JsonResult::success('添加成功');
    }

    /**
     * 删除数据(只能删除"拒绝:3"和"取消:4"的申请记录)
     * @param $ids
     * @return JsonResult
     */
    public function deleteCommit($ids) {
        //查询删除(软删除)
        $result = $this->where('id', 'IN', $ids)
            ->where('status', '<', 3)
            ->findOne();

        //status<3,正在处理中,不允许删除
        if ($result) {
            return JsonResult::fail('删除失败(只能删除拒绝或取消的申请)');
        }

        $result = $this->where('id', 'IN', $ids)
            ->where('status', 'IN', [3, 4])
            ->deletes();
        if (!$result) {
            return JsonResult::fail('删除失败');
        }
        return JsonResult::success('删除成功');
    }

    /**
     * 通过办公用品申请
     * @param $ids
     * @return JsonResult
     */
    public function pass($ids) {
        //查询更新
        $result = $this->where('id', 'IN', $ids)->where('status', '!=', '0')->findOne();

        //不是申请中:0状态的数据不允许修改状态为=>待领取:1
        if ($result) {
            return JsonResult::fail('操作失败'); //返回jsonResult对象数据
        }
        $updateData = [
            'status' => '1',  //通过申请=>待领取
        ];
        //添加操作人以及更新时间
        OperatorService::addOperator($updateData);

        $result = $this->where('id', 'IN', $ids)->updates($updateData);
        if (!$result) {
            return JsonResult::fail('操作失败'); //返回jsonResult对象数据
        }
        return JsonResult::success('操作成功'); //返回jsonResult对象数据

    }

    /**
     * 确认已领取办公用品
     * @param $ids
     * @return JsonResult
     */
    public function received($ids) {
        //查询更新
        $result = $this->fields('id')
            ->where('id', 'IN', $ids)
            ->where('status', '!=', '1')
            ->findOne();

        //不是待领取:1状态的数据不允许修改状态为=>已领取:2
        if ($result) {
            return JsonResult::fail('操作失败'); //返回jsonResult对象数据
        }

        //查询数据(goodsId,number)
        $data = $this->fields('goodsId,number')
            ->where('id', 'IN', $ids)
            ->where('status', '=', '1')
            ->find();

        $updateData = [
            'status' => '2',  //待领取=>已领取
        ];

        //开启事务
        $this->beginTransaction();
        $this->goodsService->beginTransaction();

        //添加操作人以及更新时间
        OperatorService::addOperator($updateData);

        $result = $this->where('id', 'IN', $ids)->updates($updateData);

        //更新物品已申领数量
        foreach ($data as $record){
            $result *= $this->goodsService->increase('applyNumber', $record['number'], $record['goodsId']);
        }

        if (!$result) {
            //添加失败
            //事务回滚
            $this->goodsService->rollback();
            $this->rollback();
            return JsonResult::fail('操作失败');
        }

        //提交事务
        $this->goodsService->commit();
        $this->commit();
        return JsonResult::success('操作成功'); //返回jsonResult对象数据

    }

    /**
     * 拒绝办公用品申请(申请中)=>拒绝申请
     * @param $ids ;申请id一维数组
     * @return JsonResult
     */
    public function refuse($ids) {
        //查询更新
        $result = $this->where('id', 'IN', $ids)->where('status', '!=', '0')->findOne();

        //不是申请中:0状态的数据不允许修改状态为=>拒绝申请:3
        if ($result) {
            return JsonResult::fail('操作失败'); //返回jsonResult对象数据
        }

        //查询数据(goodsId,number)
        $data = $this->fields('goodsId,number')
            ->where('id', 'IN', $ids)
            ->where('status', '=', '0')
            ->find();

        $updateData = [
            'status' => '3',  //申请中=>拒绝申请
        ];
        //开启事务
        $this->beginTransaction();
        $this->goodsService->beginTransaction();

        //添加操作人以及更新时间
        OperatorService::addOperator($updateData);

        $result = $this->where('id', 'IN', $ids)->updates($updateData);

        //更新物品剩余库存数量
        foreach ($data as $record){
            $result *= $this->goodsService->increase('number', $record['number'], $record['goodsId']);
        }

        if (!$result) {
            //添加失败
            //事务回滚
            $this->goodsService->rollback();
            $this->rollback();
            return JsonResult::fail('操作失败');
        }

        //提交事务
        $this->goodsService->commit();
        $this->commit();
        return JsonResult::success('操作成功'); //返回jsonResult对象数据
    }

    /**
     * 取消办公用品申请(申请中、待领取)=>取消申请(申请人操作)
     *  1.申请中=>取消申请
     *  2.待申领=>取消申请
     * @param $ids ;申请id一维数组
     * @return JsonResult
     */
    public function cancel($ids) {
        //查询更新
        $result = $this->where('id', 'IN', $ids)->where('status', 'NIN', [0,1])->findOne();

        //不是申请中:0和待申领:1状态的数据不允许修改状态为=>取消申请:4
        if ($result) {
            return JsonResult::fail('操作失败'); //返回jsonResult对象数据
        }

        //查询数据(goodsId,number)
        $data = $this->fields('goodsId,number')
            ->where('id', 'IN', $ids)
            ->where('status', 'IN', [0,1])
            ->find();

        $updateData = [
            'status' => '4',  //申请中=>取消申请
        ];
        //开启事务
        $this->beginTransaction();
        $this->goodsService->beginTransaction();

        //添加操作人以及更新时间
        OperatorService::addOperator($updateData);

        $result = $this->where('id', 'IN', $ids)->updates($updateData);

        //更新物品剩余库存数量
        foreach ($data as $record){
            $result *= $this->goodsService->increase('number', $record['number'], $record['goodsId']);
        }

        if (!$result) {
            //添加失败
            //事务回滚
            $this->goodsService->rollback();
            $this->rollback();
            return JsonResult::fail('操作失败');
        }

        //提交事务
        $this->goodsService->commit();
        $this->commit();
        return JsonResult::success('操作成功'); //返回jsonResult对象数据
    }
}