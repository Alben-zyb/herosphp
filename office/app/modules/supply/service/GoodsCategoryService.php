<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-18 下午5:25
 * @function
 */

namespace app\supply\service;


use app\api\service\OperatorService;
use app\supply\dao\GoodsCategoryDao;
use herosphp\model\CommonService;
use herosphp\filter\Filter;
use herosphp\utils\JsonResult;

class GoodsCategoryService extends CommonService {
    //属性
    protected $modelClassName = GoodsCategoryDao::class;

    /**
     * 获取物品分类数据(用于下拉选择)
     * @return array
     */
    public static function getListData() {
        $category = (new self())->fields('id,categoryNo,categoryName')->find();
        return $category;
    }

    /**
     * 根据分类id获取分类数据
     * @param $id
     * @return array
     */
    public static function getDataById($id) {
        $category = (new self())->fields('id,categoryNo,categoryName')->findById($id);
        return $category;
    }

    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getPageData($search = array()) {

        //数据过滤规则
        $filterTypeNo = array(
            'categoryNo' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类编号数据类型有误！")
            )
        );
        $filterTypeName = array(
            'categoryName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类名称数据类型有误！")
            )
        );

        //前端输入的查询条件
        $searchTypeNo = array(
            'categoryNo' => $search['categoryNo']
        );

        $searchTypeName = array(
            'categoryName' => $search['categoryName']
        );


        $searchCondition = array();//查询的最终条件

        //物品分类编号过滤添加
        if ($result = Filter::loadFromModel($searchTypeNo, $filterTypeNo, $error)) {
            $searchCondition[] = array('categoryNo', '=', $result['categoryNo']);
        }

        //物品分类名称过滤添加
        if ($result = Filter::loadFromModel($searchTypeName, $filterTypeName, $error)) {
            $searchCondition[] = array('categoryName', 'LIKE', '%' . $result['categoryName'] . '%');
        }


        $count = $this->whereArr($searchCondition)
            ->count();//获取数据总条数（加入查询条件）

        if ($count < $search['limit']) {
            $search['page'] = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }
        //查询休假申请详细信息(包含连接查询到的申请人信息)
        $data = $this->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->find();

        return JsonResult::layTable($data, $count); //返回JsonResult对象数据
    }

    /**
     * 添加数据
     * @param $data
     * @return JsonResult
     */
    public function addSubmit($data) {

        //数据过滤规则
        $filter = array(
            'categoryNo' => array(Filter::DFILTER_REGEXP, '/^[A-Z]$/', Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类编号数据类型有误！")
            ),
            'categoryName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类名称数据类型有误！")
            )
        );
        $data = Filter::loadFromModel($data, $filter, $error);
        if (!$data) {
            return JsonResult::fail('数据类型有误');
        }
        //若是修改编号,先查询typeNo是否已存在
        if(isset($data['categoryNo'])){
            $result=$this->where('categoryNo',$data['categoryNo'])->findOne();
            if($result){
                //已存在,返回
                return JsonResult::fail('添加失败,编号已存在');
            }
        }

        OperatorService::addOperator($data);
        $result = $this->add($data);
        if (!$result) {
            return JsonResult::fail('添加失败');
        }
        return JsonResult::success('添加成功');
    }

    /**
     * 更新数据
     * @param $data
     * @return JsonResult
     */
    public function editSubmit($data) {

        //数据过滤规则
        $filter = array(
            'categoryNo' => array(Filter::DFILTER_REGEXP, '/^[A-Z]$/', Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类编号数据类型有误！")
            ),
            'categoryName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "物品分类名称数据类型有误！")
            )
        );
        $data = Filter::loadFromModel($data, $filter, $error);

        if (!$data) {
            return JsonResult::fail('数据类型有误');
        }
        //若是修改编号,先查询typeNo是否已存在
        if(isset($data['categoryNo'])){
            $result=$this->where('categoryNo',$data['categoryNo'])->findOne();
            if($result){
                //已存在,返回
                return JsonResult::fail('修改失败,编号已存在');
            }
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
        //查询删除
        $result = $this->where('id', 'IN', $ids)->deletes();
        if (!$result) {
            return JsonResult::fail('删除失败');
        }
        return JsonResult::success('删除成功');
    }
}