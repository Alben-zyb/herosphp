<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-06-23
 */

namespace app\common\service;


use app\common\dao\UserDao;
use herosphp\model\CommonService;
use app\common\dao\DepartmentDao;
use herosphp\filter\Filter;
use herosphp\utils\JsonResult;
use herosphp\core\Loader;

class DepartmentService extends CommonService {

    protected $modelClassName = DepartmentDao::class;


    /**
     * (没有被调用)
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param int $page
     * @param int $limit
     * @param array $searchCondition
     * @return JsonResult
     */
    public function getPageData($page = 1, $limit = 10, $searchCondition = array()) {

        //使用服务的内置方法，实质是服务去调用模型方法，做一个封装
        $count = $this->whereArr($searchCondition)->count();//获取数据总条数（加入查询条件）
        if ($count < $limit) {
            $page = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }
        $data = $this->whereArr($searchCondition)->page($page, $limit)->find();
        return JsonResult::layTable($data,$count);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function addData($data) {

        //获取添加的数据
        $parentId = $data['parentId'];
        $departmentName = $data['departmentName'];

        $filterDepartment = array(
            'departmentName'=>array(Filter::DFILTER_STRING, array(1,30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "部门名称长度为1~30个字符","length" => "部门名称长度为1~30个字符")
            )
        );

        $departmentResult = Filter::loadFromModel($data, $filterDepartment, $error);
        if (!$departmentResult) {
            JsonResult::fail($error)->output();
        }

        //判断是否有夫部门
        if ($parentId == ''||$parentId==null) {
            $parentId =0;
        }
        if (!is_numeric($parentId)) {
            JsonResult::fail('添加失败！')->output();//框架返回json数据
        }
        //构建部门编号
        $departmentNo=$this->createDepartmentNo();

        $data = [
            'departmentNo' => trim($departmentNo),
            'departmentName' => trim($departmentName),
            'parentId' => trim($parentId),
            'create_time' => date('Y-m-d H:i'),
            'update_time' => date('Y-m-d H:i'),
        ];

        $result = $this->add($data);//调用add添加数据方法
        if(!is_numeric($result)){
            return false;
        }
        if($parentId!=0){
            $result = $this->update(['isLeaf' => 0], $parentId);//更新
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    /**
     * 根据条件获取数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getDepartmentData($search = array()) {
        //数据过滤规则
        $filterNo = array(
            'No'=>array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "部门编号为数字字符串")
            ));
        $filterName = array(
            'name'=>array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "部门名称为字母、中文")
            ));
        $filterMin = array(
            'min'=>array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));
        $filterMax = array(
            'max'=>array(Filter::DFILTER_NUMERIC, NULL, NULL,
                array("type" => "请输入一个整数")
            ));

        //输入的查询条件

        $searchName = array(
            'name' => $search['departmentName'],
        );
        $searchNo = array(
            'No' => $search['departmentNo'],
        );

        $searchMin = array(
            'min' => $search['memberMin'],
        );
        $searchMax = array(

            'max' => $search['memberMax'],
        );

        $searchCondition = array();//查询的最终条件
        //部门编号条件过滤添加
        if ($result = Filter::loadFromModel($searchNo, $filterNo, $error)) {
            $searchCondition[] = array('departmentNo', 'LIKE', $result['No'] . '%');
        }

        //部门名称条件过滤添加
        if ($result = Filter::loadFromModel($searchName, $filterName, $error)) {
            $searchCondition[] = array('departmentName', 'LIKE', $result['name'] . '%');
        }

        //部门最小人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMin, $filterMin, $error)) {
            $searchCondition[] = array('members', '>=', $result['min']);
        }

        //部门最大人数条件过滤添加
        if ($result = Filter::loadFromModel($searchMax, $filterMax, $error)) {
            if ($result['max'] != 0) {
                $searchCondition[] = array('members', '<', $result['max']);
            }

        }

        //时间条件，配合时间类型：添加｜更新　使用
        if ($search['start']) {
            $searchCondition[] = array($search['timeType'], '>', $search['start']);
        }
        if ($search['end']) {
            $searchCondition[] = array($search['timeType'], '<', $search['end']);
        }
        //使用服务的内置方法
        $count = $this->whereArr($searchCondition)->count();//获取数据总条数（加入查询条件）
        $data = $this->whereArr($searchCondition)->find();

        //给data添加默认展开字段
        foreach ($data as $index=>&$item) {
            if(!$item['isLeaf']){
                $item['open']=true;
            }
        }

        //把用户也添加到部门进行显示
        $userModel=Loader::model(UserDao::class); //使用用户模型

        $users=$userModel->find();
        foreach ($users as $index=>&$user) {
            $user['parentId']=$user['departmentId'];
            $user['departmentName']=$user['username'];
            $user['isLeaf']='2';
            $user['id']='user'.$index;
        }

        $data=array_merge($users,$data);  //合并数组

        return JsonResult::layTable($data,$count);
    }

    public function getDepartmentTree() {

        //使用服务的内置方法
        $data = $this->find();
        //将数据封装成树形json数据

        return $this->listToTree($data);
    }

    /**
     * 查询指定字段数据
     * @param $field
     * @return array
     */
    public function getFieldData($field = array()) {
        return $this->fields($field)->find();
    }


    /**
     * 构建部门编号
     * @return string
     */
    public function createDepartmentNo() {

        //获取最近的部门编号
        $result = $this->fields('departmentNo')->order('id desc')->findOne();

        if ($result) {
            //获取部门编号
            $oldDepartmentNo = $result['departmentNo'];
            $No = $oldDepartmentNo + 1; //部门编号+1
            switch (strlen($No)) {
                case 1:
                    $No = '00' . $No;
                    break;
                case 2:
                    $No = '0' . $No;
                    break;
                case 3:
                    $No = '' . $No;
                    break;
                default:
                    JsonResult::fail('添加失败！部门编号超出范围')->output();//框架返回json数据
                    break;
            }
            return $No;
        } else {
            //不存在，直接返回：前缀+001
            return '001';
        }
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pk
     * @param string $pid parent标记字段
     * @param string $child
     * @param int $root
     * @return array
     */
    function listToTree($list, $pk = 'id', $pid = 'parentId', $child = 'children', $root = 0) {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();

            //过滤掉不需要的数据
            foreach ($list as $key => $item) {
                $list[$key]['name'] = $item['departmentName']; //增加name字段
                $list[$key]['open'] = true; //增加open字段:默认展开
                unset($list[$key]['departmentNo']);
                unset($list[$key]['departmentName']);
                unset($list[$key]['remark']);
                unset($list[$key]['members']);
                unset($list[$key]['isLeaf']);
                unset($list[$key]['level']);
                unset($list[$key]['operator']);
                unset($list[$key]['operatorId']);
                unset($list[$key]['create_time']);
                unset($list[$key]['update_time']);
            }

            //循环引用赋值
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];//父id
                if ($root == $parentId) {
                    //如果父id是根id，引用赋值给$tree
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        //判断是否存在父节点，若存在，将当前节点引用赋值给父节点（父节点创建属性$child，引用赋值）
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

}