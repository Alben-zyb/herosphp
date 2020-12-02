<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-02
 */

namespace app\common\service;


use app\admin\service\IdentifyService;
use app\admin\utils\Lang;
use app\api\service\OperatorService;
use herosphp\model\CommonService;
use herosphp\core\Loader;
use app\common\dao\UserDao;
use app\common\dao\UserRoleDao;
use herosphp\rsa\RSACrypt;
use herosphp\utils\JsonResult;
use herosphp\filter\Filter;


class UserService extends CommonService {

    //属性
    protected $modelClassName = UserDao::class;

    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $searchCondition
     * @return JsonResult
     */
    public function getPageData($searchCondition = array()) {
        //获取条件参数（分页，查询条件等）
        $page = $searchCondition['page'];//页码
        $limit = $searchCondition['limit'];//每页条数
        $departmentId = $searchCondition['departmentId'];//部门id
        $positionId = $searchCondition['positionId'];//岗位id
        $NoName = $searchCondition['NoName'];//用户编号姓名
        $timeType = $searchCondition['timeType'];//时间类型：创建|更新
        $start = $searchCondition['start'];//开始时间
        $end = $searchCondition['end'];//结束时间
        $loginId = $searchCondition['loginId'];//登录用户id,若存在,不查询自身数据

        //数据过滤规则

        $filterName = array(
            'NoName' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "工号或姓名数据类型有误！")
            )
        );

        //前端输入的查询条件
        $searchNoName = array(
            'NoName' => $NoName
        );

        $searchCondition = array();//查询的最终条件
        //部门id条件
        if (is_numeric($departmentId)) {
            $departmentService=Loader::service(DepartmentService::class);  //调用部门服务类
            //递归查找部门以及子部门员工
            $departmentIdList=array();  //存放本部门以及子部门id(一维数组)
            $this->getDepartmentByRecursion($departmentService,$departmentId,$departmentIdList);
            $searchCondition[] = array('a.departmentId', 'IN', $departmentIdList);
        }

        //岗位id条件
        if (is_numeric($positionId)) {
            $searchCondition[] = array('a.positionId', '=', $positionId);
        }


        //工号或姓名条件过滤添加
        if ($result = Filter::loadFromModel($searchNoName, $filterName, $error)) {
            $NoNamePass = $result['NoName'];
            //判断NoName是工号还是姓名
            if (is_numeric($NoNamePass)) {
                $searchCondition[] = array('a.userNo', 'LIKE', $NoNamePass . '%');
            } else {
                $searchCondition[] = array('a.username', 'LIKE', '%' . $NoNamePass . '%');
            }

        }


        //时间条件，配合时间类型：添加｜更新　使用
        if ($start) {
            $searchCondition[] = array('a.' . $timeType, '>', $start);

        }
        if ($end) {
            $searchCondition[] = array('a.' . $timeType, '<', $end);
        }

        //登录用户id条件
        if (is_numeric($loginId)) {
            $searchCondition[] = array('a.id', '!=', $loginId);
        }
        $count = $this->alias('a')->whereArr($searchCondition)->count();//获取数据总条数（加入查询条件）

        if ($count < $limit) {
            $page = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }

        //使用连接查询，查找部门名称
        $data1 = $this->alias('a')
            ->fields('a.*,b.departmentName')
            ->join('department b', MYSQL_JOIN_LEFT)
            ->on('a.departmentId = b.id')
            ->whereArr($searchCondition)
            ->page($page, $limit)
            ->find();

        //使用连接查询，查找岗位名称
        $data2 = $this->alias('a')
            ->fields('c.positionName')
            ->join('position c', MYSQL_JOIN_LEFT)
            ->on('a.positionId = c.id')
            ->whereArr($searchCondition)
            ->page($page, $limit)
            ->find();
        //使用连接查询，查找直接上级名称
        $data3 = $this->alias('a')
            ->fields('d.username as superior')
            ->join('user d', MYSQL_JOIN_LEFT)
            ->on('a.superior = d.id')
            ->whereArr($searchCondition)
            ->page($page, $limit)
            ->find();
        //使用连接查询，查找部门负责人名称
        $data4 = $this->alias('a')
            ->fields('e.username as departmentHead')
            ->join('user e', MYSQL_JOIN_LEFT)
            ->on('a.departmentHead = e.id')
            ->whereArr($searchCondition)
            ->page($page, $limit)
            ->find();
        //循环合并数组
        $data = array();
        foreach ($data1 as $key => $value) {
            $data[] = array_merge($value, $data2[$key], $data3[$key], $data4[$key]);
            $data[$key]['headImg'] = getConfig('headUrl') . $data[$key]['headImg'];
            $data[$key]['create_time'] = substr($data[$key]['create_time'],0,16);
            $data[$key]['update_time'] = substr($data[$key]['update_time'],0,16);
        }

        return JsonResult::layTable($data, $count); //返回JsonResult对象数据
    }

    /**
     * 获取用户下拉列表(登录用户除外)
     * @param bool $except 是否除去本身
     * @return JsonResult
     */
    public static function getUserList($except = true) {
        //是否除去本身
        if ($except) {
            $user = IdentifyService::getUser();
            $data = (new self())->fields('id,userNo,username')->where('id', '!=', $user['id'])->find();
        } else {
            $data = (new self())->fields('id,userNo,username')->find();
        }
        if (!$data) {
            return JsonResult::fail('获取失败', $data);//调用查询数据方法，返回json格式数据，满足layui中table接收的数据类型
        }
        return JsonResult::success('获取成功', $data);
    }


    /**
     * 根据用户id获取用户拥有的角色
     * @param $Uid
     * @return array
     */
    public function getRoleByUid($Uid) {
        //使用模型方法
        $userRoleModel = Loader::model(UserRoleDao::class);//加载用户角色Dao类
        $roleService = new RoleService();//实例化角色服务类

        $roleIds = $userRoleModel->fields('roleId')->where('userId', $Uid)->find();

        $roleIdArr = array(''); //存放id的数组
        //将二维数组转成一维
        foreach ($roleIds as $roleId) {
            $roleIdArr[] = $roleId['roleId'];
        }
        return $roleService->where('id', 'IN', $roleIdArr)->find(); //返回角色信息
    }

    /**
     * 根据用户id获取用户拥有的权限
     * @param $Uid
     * @return array
     */
    public function getUserPermissionByUid($Uid) {

        $roleService = new RoleService();//实例化角色服务类

        $roles = $this->getRoleByUid($Uid); //获取用户所有角色(只获取到角色id)

        $permissions = array(); //存放权限信息
        $permissionArr = array(); //存放权限信息
        //根据角色id循环获取所有权限信息
        foreach ($roles as $role) {
            //先判断该角色是否被禁用
            if ($role['status'] == 1) {
                //有数据,证明状态可用
                $permissionArr[] = $roleService->getPermissionByRid($role['id']); //返回的是一个一维数组,多个数组中可能存在相同权限的字段
            }
        }
        //进行转换,合成一个数组
        for ($i = 0; $i < sizeof($permissionArr); $i++) {
            $permissions = array_merge($permissions, $permissionArr[$i]);
        }
        //去除相同的值
        $permissionArr = array(); //临时存储
        foreach ($permissions as $index => $permission) {
            if (in_array($permission, $permissionArr)) {
                unset($permissions[$index]);
            } else {
                $permissionArr[] = $permission;
            }
        }
        return $permissionArr; //返回唯一的数组
    }


    /**
     * 添加用户
     * @param $data
     * @return JsonResult
     */
    public function addSubmit($data) {

        $departmentService = new DepartmentService();//实例化部门服务类
        $positionService = new PositionService();//实例化岗位服务类

        if ($data['positionId'] == '' || $data['positionId'] == null) {
            $data['positionId'] = 0;
        }


        //添加验证规则
        $filterMap = array(
            'username' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "姓名不能为空.", "length" => "姓名长度必需在1-30之间.")),
            'userNo' => array(Filter::DFILTER_STRING, array(4, 5), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "工号不能为空.", "length" => "工号长度必需在4-5之间.")),
            'phone' => array(Filter::DFILTER_MOBILE, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "请输入正确的手机号码")),
            'password' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "密码格式不正确")),
            'email' => array(Filter::DFILTER_EMAIL, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "请输入正确的企业邮箱")),
            'departmentId' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "部门格式不正确")),
            'positionId' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "岗位格式不正确")),
        );


        //验证过滤
        $data = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$data) {
            JsonResult::fail($error, $error)->output();//框架返回json数据
        }
        //验证成功
        //判断是注册还是后台添加
        if(isset($data['password'])){
            //有密码.注册
            $data['password']=md5($data['password']); //密码md5加密存储
        }else{
            //没密码.后台添加,添加默认密码(123456)
            $data['password']=md5(getConfig('password')); //默认密码密码md5加密存储

        }

        //添加默认头像
        $data['headImg']=getConfig('headImg');

        //添加创建时间和更新时间字段
        $data['create_time']=date('Y-m-d H:i');
        $data['update_time']=date('Y-m-d H:i');

        //先查询该工号和手机号是否是唯一的
        $result = $this->where('userNo', '=', $data['userNo'])->whereOr('phone', '=', $data['phone'])->findOne();
        if ($result) {
            return JsonResult::fail('注册失败！用户已存在！', $result);//框架返回json数据
        }
        $this->beginTransaction(); //user开启事务
        $departmentService->beginTransaction(); //department开启事务
        $result = $this->add($data);//注册

        //失败，返回
        if (!$result) {
            return JsonResult::fail('注册失败！');//框架返回json数据
        }
        //注册成功，给部门(不包括祖先部门,直系第一部门)，岗位人数+1
        $result = $departmentService->increase('members', 1, $data['departmentId']);//调用increase自增方法
        //部门人数增加失败，user事务回退，返回
        if (!$result) {
            $this->rollback();
            return JsonResult::fail('注册失败！');//框架返回json数据
        }
        if ($data['positionId']) {
            $result = $positionService->increase('members', 1, $data['positionId']);//调用increase自增方法
            //岗位人数增加失败，user，department事务回退，返回
            if (!$result) {
                $this->rollback();
                $departmentService->rollback();
                return JsonResult::fail('注册失败！');//框架返回json数据
            }
        }
        $departmentService->commit(); //department事务提交
        $this->commit(); //user事务提交
        //添加角色
        //默认拥有0号角色，即可访问基本页面
        return JsonResult::success('注册成功！', $result);//框架返回json数据
    }

    /**
     * @param $userData (id,username,username,phone,email,oldDepartment,newDepartment,oldPosition,newPosition)
     * @return JsonResult
     */
    public function edit($userData) {
        //获取修改的数据的id
        $id = $userData['id'];

        $departmentService = new DepartmentService();//实例化部门服务类
        $positionService = new PositionService();//实例化岗位服务类

        //旧数据
        $old = $this->findById($id);

        //组织数据源格式
        $checkData = array(
            'username' => $userData['username'],
            'phone' => $userData['phone'],
            'email' => $userData['email'],
            'departmentId' => $userData['department'],
            'positionId' => $userData['position'] ? $userData['position'] : 0,
            'superior' => $userData['superior'] ? $userData['superior'] : 0,
            'departmentHead' => $userData['departmentHead'] ? $userData['departmentHead'] : 0,
        );
        //添加验证规则
        $filterMap = array(
            'username' => array(Filter::DFILTER_STRING, array(1, 30), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "姓名不能为空", "length" => "姓名长度必需在1-30之间.")),
            'phone' => array(Filter::DFILTER_MOBILE, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("require" => "手机号不能为空.", "type" => "请输入正确的手机号码")),
            'email' => array(Filter::DFILTER_EMAIL, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "请输入正确的企业邮箱")),
            'departmentId' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("require" => "部门不能为空", "type" => "部门格式不正确")),
            'positionId' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "岗位格式不正确")),
            'superior' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("require" => "直接上级不能为空", "type" => "直接上级格式不正确")),
            'departmentHead' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("require" => "部门负责人不能为空", "type" => "部门负责人格式不正确")),
        );


        //验证过滤
        $data = Filter::loadFromModel($checkData, $filterMap, $error);

        //验证失败，返回
        if (!$data) {
            return JsonResult::fail('添加失败！数据格式不正确', $error);//框架返回json数据
        }
        //验证成功，组织添加的数据格式
        //添加更新时间字段，并添加操作人信息
        OperatorService::addOperator($data); //引用变量

        //先验证手机号是否是唯一的
        $result = $this->whereArr([['phone', '=', $data['phone']], ['id', '!=', $id]])->findOne();
        if ($result) {
            return JsonResult::fail('修改失败！手机号已存在！', $result);//框架返回json数据
        }

        $this->beginTransaction(); // 开启事务
        $departmentService->beginTransaction();//开启事务
        $positionService->beginTransaction();//开启事务

        $result = $this->update($data, $id);//更新

        //添加成功
        //判断部门是否更新，有更新

        if ($old['departmentId'] != $data['departmentId']) {
            //部门更新了
            $result &= $departmentService->reduce('members', 1, $old['departmentId']);//旧部门人数，调用reduce自减方法
            $result &= $departmentService->increase('members', 1, $data['departmentId']);//新部门人数，调用increase自增方法
        }
        //判断岗位是否更新，有更新
        if ($old['positionId'] != $data['positionId']) {
            if ($old['positionId'] != 0 && $old['positionId'] != '') {
                //判断是否存在旧岗位，存在，旧岗位人数自减
                $result &= $positionService->reduce('members', 1, $old['positionId']);//旧部门人数，调用reduce自减方法
            }
            if ($data['positionId'] != 0 && $data['positionId'] != '') {
                //判断新岗位是否存在，存在，新岗位人数自增
                $result &= $positionService->increase('members', 1, $data['positionId']);//新部门人数，调用increase自增方法
            }
        }
        if (!$result) {
            $departmentService->rollback();  //事务回滚
            $positionService->rollback();
            $this->rollback();
            return JsonResult::fail('修改失败！');//框架返回json数据
        }
        $departmentService->commit();  //提交事务
        $positionService->commit();
        $this->commit();
        return JsonResult::success('修改成功！', $result);
    }

    /**
     * 修改密码
     * @param $userData (id,oldPwd,newPwd)
     * @return JsonResult
     */
    public function editPwd($userData) {
        $rsa=new RSACrypt(); //实例化RSA加密解密类
        //获取修改的数据的id
        $id = $userData['id'];
        $oldPwd= $rsa->decryptByPrivateKey($userData['oldPwd']); //密码(rsa解密)
        $newPwd = $rsa->decryptByPrivateKey($userData['newPwd']);;


        //旧数据
        $oldData = $this->fields('password')->findById($id);

        //旧密码与数据库不相同,不允许修改密码
        if(md5($oldPwd)!==$oldData['password']){
            return JsonResult::fail('旧密码不正确');//框架返回json数据
        }

        $data['password']=md5($newPwd); //md5加密存储
        //添加更新时间字段，并添加操作人信息
        OperatorService::addOperator($data); //引用变量

        $result = $this->update($data, $id);//更新

        if (!$result) {
            return JsonResult::fail('修改失败！');//框架返回json数据
        }
        return JsonResult::success('修改成功！', $result);
    }


    /**
     * 上传图片，返回图片地址
     * @return JsonResult
     */
    public function uploadHeadImg() {
        //接收数据
        $file = $_FILES['file'];  //直接接收

        //获取图片后缀
        $suffix = strtolower(strrchr($file['name'], '.'));

        //图片类型
        $type = ['.jpg', '.jpeg', '.png'];
        if (!in_array($suffix, $type)) {
            return JsonResult::fail(Lang::HEAD_IMG_NOT);
        }
        if ($file['size'] / 1024 > 5120) {
            return JsonResult::fail(Lang::HEAD_IMG_BIG);
        }

        //构建图片路径和图片名称
        $fileName = uniqid('headImg_', false). $suffix; //构建唯一图片名称,加上图片后者
        $uploadPath = APP_ROOT . getConfig('headUrl'); //APP_ROOT:项目根目;上传路径
        // 判断保存的目录是否存在
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
            chmod($uploadPath, 0777);
        }
        $fullPath = $uploadPath . $fileName; //包含文件名的全路径

        //更新操作，删除原来头像图片
        //获取当前登录用户
        $loginUser = IdentifyService::getUser();

        //获取头像图片
        $oldHeadImg = $loginUser['headImg'];
        $defaultHeadImg = getConfig('headImg');  //默认头像名称
        if ($oldHeadImg && !strpos($oldHeadImg, $defaultHeadImg)) {
            //判断是否为默认头像，不是，删除（是，不删除）
            //获取图片路径
            $oldHeadImgUrl = APP_ROOT . $oldHeadImg;
            //文件存在，删除unlink
            if (file_exists($oldHeadImgUrl)) {
                if (!unlink($oldHeadImgUrl)) {
                    return JsonResult::fail(Lang::HEAD_IMG_FAIL);
                }
            }
        }
        //上传
        $res = move_uploaded_file($file['tmp_name'], $fullPath);
        if (!$res) {
            ob_clean();//清除输出缓冲区，否则返回的不是json数据，前端会报错
            return JsonResult::fail(Lang::HEAD_IMG_FAIL);
        }
        //更新用户信息
        $data = ["headImg" => $fileName];//返回成功状态和图片名
        $res=$this->update($data,$loginUser['id']);
        IdentifyService::refreshUser(); //更新当前登录用户信息
        if (!$res) {
            return JsonResult::fail(Lang::HEAD_IMG_FAIL);
        }
        $data['headImg']=getConfig('headUrl').$fileName;
        return JsonResult::success(Lang::HEAD_IMG_SUCCESS, $data);
    }



}