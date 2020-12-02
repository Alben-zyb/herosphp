<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-31 上午11:35
 * @function  休假申请记录服务类
 */

namespace app\vacation\service;


use app\admin\service\IdentifyService;
use app\api\service\MailerService;
use app\api\service\OperatorService;
use app\api\service\SMSService;
use app\common\service\UserService;
use app\vacation\dao\VacationDao;
use herosphp\core\Loader;
use herosphp\model\CommonService;
use herosphp\filter\Filter;
use herosphp\utils\JsonResult;

class VacationService extends CommonService {

    //属性
    protected $modelClassName = VacationDao::class;

    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getPageData($search = array()) {

        //数据过滤规则
        //前端输入的查询条件
        $filterApplicant = array(
            'applicant' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "工号或姓名数据类型有误！")
            )
        );

        $filterType = array(
            'type' => array(Filter::DFILTER_NUMERIC, NULL, NULL, array("type" => "请假类型数据类型有误！")
            ));

        $filterStatus = array(
            'status' => array(Filter::DFILTER_NUMERIC, NULL, NULL, array("type" => "申请状态数据类型有误！")
            ));

        //前端输入的查询条件

        $searchApplicant = array(
            'applicant' => $search['applicant']
        );

        $searchType = array(
            'type' => $search['type']
        );
        $searchStatus = array(
            'status' => $search['status']
        );

        $searchCondition = array();//查询的最终条件

        //请假类型过滤添加
        if ($result = Filter::loadFromModel($searchType, $filterType, $error)) {
            if ($result['type'] != '-1') {
                $searchCondition[] = array('a.type', '=', $result['type']);
            }
        }
        //申请状态条件过滤添加
        if ($result = Filter::loadFromModel($searchStatus, $filterStatus, $error)) {
            if ($result['status'] != '-1') {
                $searchCondition[] = array('a.status', '=', $result['status']);
            }
        }

        //日期条件

        //请假天数
        if (is_numeric($search['dayMin'])) {
            $searchCondition[] = array('days', '>=', $search['dayMin']);

        }
        if (is_numeric($search['dayMax'])) {
            $searchCondition[] = array('days', '<', $search['dayMax']);

        }
        //时间条件，配合时间类型：开始使用｜结束使用
        if ($search['start']) {
            $searchCondition[] = array($search['timeType'], '>', $search['start']);

        }
        if ($search['end']) {
            $searchCondition[] = array($search['timeType'], '<', $search['end']);
        }


        //两种情况:
        // 1.直接上级可以查看整个休假申请审核过程
        // 2.部门负责人只能查看已经通过直接上级审核的休假申请审核过程(即当前审核人已设置为部门负责人)
        //情况1:

        //添加当前审核人
        $presentCheckCondition = array('1', '=', '1');
        if ($search['presentCheck']) {
            $presentCheckCondition = array('presentCheck', '=', $search['presentCheck']);
        }

        //添加直接上级审核人,但当前审核人不是直接上级
        $superiorCondition = array('1', '=', '1');
        if ($search['superior']) {
            $superiorCondition = array('a.superior', '=', $search['superior']);
        }

        //申请人条件过滤添加
        $orApplicant=array('1','=','1');
        if ($result = Filter::loadFromModel($searchApplicant, $filterApplicant, $error)) {
            if (is_numeric($result['applicant'])) {
                $orApplicant = array('b.userNo', '=', $result['applicant']);
            } else {
                $orApplicant = array('b.username', 'LIKE', '%' . $result['applicant'] . '%');
            }
        }
        //代理人条件
        $orAgent=array('1','=','1');
        if ($search['agent']) {
            $orAgent = array('a.agent', '=', $search['agent']);
        }

        //先进行一个更新操作,根据当前时间设置休假申请状态是否过期
        $this->setOverdue();


        //$that=$this;
        $count = $this->alias('a')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.applicant = b.id')
            ->where('1', '1')
            ->where(function () use ($presentCheckCondition, $superiorCondition) {
                $this->where($presentCheckCondition)->whereOr($superiorCondition);
            })
            ->whereArr($searchCondition)
            ->where(function () use ($orApplicant, $orAgent) {
                $this->where($orApplicant)->whereOr($orAgent);
            })
            ->count();//获取数据总条数（加入查询条件）

        if ($count < $search['limit']) {
            $search['page'] = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }
        //查询休假申请详细信息(包含连接查询到的申请人信息)
        $data = $this->alias('a')
            ->fields('a.*,b.userNo,b.username')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.applicant = b.id')
            ->where('1', '1')
            ->where(function () use ($presentCheckCondition, $superiorCondition) {
                $this->where($presentCheckCondition)->whereOr($superiorCondition);
            })
            ->whereArr($searchCondition)
            ->where(function () use ($orApplicant, $orAgent) {
                $this->where($orApplicant)->whereOr($orAgent);
            })
            ->page($search['page'], $search['limit'])
            ->order('a.start desc')
            ->find();

        //使用连接查询，查找代理人
        $agentData = $this->alias('a')
            ->fields('c.id,c.userNo,c.username')
            ->join('user c', MYSQL_JOIN_LEFT)
            ->on('a.agent = c.id')
            ->where('1', '1')
            ->where(function () use ($presentCheckCondition, $superiorCondition) {
                $this->where($presentCheckCondition)->whereOr($superiorCondition);
            })
            ->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->order('a.start desc')
            ->find();

        //使用连接查询，查找直接上级
        $superiorData = $this->alias('a')
            ->fields('c.id,c.userNo,c.username')
            ->join('user c', MYSQL_JOIN_LEFT)
            ->on('a.superior = c.id')
            ->where('1', '1')
            ->where(function () use ($presentCheckCondition, $superiorCondition) {
                $this->where($presentCheckCondition)->whereOr($superiorCondition);
            })
            ->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->order('a.start desc')
            ->find();
        //使用连接查询，查找部门负责人
        $departmentHeadData = $this->alias('a')
            ->fields('c.id,c.userNo,c.username')
            ->join('user c', MYSQL_JOIN_LEFT)
            ->on('a.departmentHead = c.id')
            ->where('1', '1')
            ->where(function () use ($presentCheckCondition, $superiorCondition) {
                $this->where($presentCheckCondition)->whereOr($superiorCondition);
            })
            ->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->order('a.start desc')
            ->find();


        $agentArr = array();
        //将代理人数据格式组织为:(工号--姓名)格式
        foreach ($agentData as $item) {
            $agentArr[$item['id']] = $item['userNo'] . '--' . $item['username'];
        }

        $superiorArr = array();
        //将直接上级数据格式组织为:(姓名)格式
        foreach ($superiorData as $item) {
            $superiorArr[$item['id']] = $item['username'];
        }

        $departmentHeadArr = array();
        //将部门负责人数据格式组织为:(姓名)格式
        foreach ($departmentHeadData as $item) {
            $departmentHeadArr[$item['id']] = $item['username'];
        }

        //查询请假类型
        $vacationType = VacationTypeService::getListData(); //调用请假类型服务类
        $vacationTypeArr = array();
        foreach ($vacationType as $item) {
            $vacationTypeArr[$item['id']] = $item['typeName'];
        }

        $loginUser = IdentifyService::getUser();
        //循环合并数组
        foreach ($data as $key => &$value) {
            $value['type'] = $vacationTypeArr[$value['type']];
            $value['agent'] = $agentArr[$value['agent']];
            $value['superiorName'] = $superiorArr[$value['superior']];
            $value['departmentHeadName'] = $departmentHeadArr[$value['departmentHead']];

            //添加我的审核结果字段显示
            if ($value['superior'] == $loginUser['id']) {
                //如果当前审核人为直接上级,添加当前审核人的审核状态为直接上级审核状态
                $value['myCheck'] = $value['superiorCheckStatus'];
            } elseif ($value['departmentHead'] == $loginUser['id']) {
                //如果当前审核人为部门负责人,添加当前审核人的审核状态为部门负责人审核状态
                $value['myCheck'] = $value['departmentHeadCheckStatus'];
            }
        }

        return JsonResult::layTable($data, $count); //返回JsonResult对象数据
    }

    /**
     * 根据当前时间设置休假申请状态是否过期
     */
    public function setOverdue() {
        //获取当前时间有误
        $datetime = date('Y-m-d H:i:s'); //当前日期+时间

        //情况:1
        //申请中:0=>申请过期:4
        //设置申请过期(申请到期没有通过申请)
        $this->whereArr(array(
            ['start', '<', $datetime],
            ['status', '=', 0],
        ))->sets('status', 4);
    }

    /**
     * 添加
     * @param $data
     * @return JsonResult
     */
    public function addSubmit($data) {

        //添加验证规则
        $filterMap = array(
            'type' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "请假类型数据格式错误")),
            'applicant' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "申请人数据格式错误")),
            'reason' => array(Filter::DFILTER_STRING, array(1, 500), Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "申请原因数据格式错误", "length" => "申请原因长度为1~500字符")),
            'start' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "请假开始时间不能为空")),
            'end' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("require" => "请假结束时间不能为空")),
            'days' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "请假时长数据格式错误")),
            'agent' => array(Filter::DFILTER_NUMERIC, NULL, Filter::DFILTER_SANITIZE_TRIM,
                array("type" => "代理人人数据格式错误")),
        );

        //验证过滤
        $result = Filter::loadFromModel($data, $filterMap, $error);

        //验证失败，返回
        if (!$result) {
            return JsonResult::fail('数据类型有误！', $error);// 返回JsonResult对象数据
        }
        $datetime = date('Y-m-d H:i:s'); //当前日期+时间

        //控制请假时间只能是未来时间
        if ($result['start'] <= $datetime || $result['end'] <= $datetime) {
            return JsonResult::fail('申请失败,时间段有误！', $error);// 返回JsonResult对象数据
        }
        //控制结束时间大于开始时间
        if ($result['start'] >= $result['end']) {
            return JsonResult::fail('申请失败,时间段有误！', $error);// 返回JsonResult对象数据
        }
        //验证通过
        //添加申请人的直接上级和部门负责人字段
        $userService = Loader::service(UserService::class); //调用用户服务类
        $applicant = $userService->findById($result['applicant']);

        $data['superior'] = $applicant['superior'];//添加申请人直接上级字段
        $data['departmentHead'] = $applicant['departmentHead']; //添加申请人部门负责人字段
        $data['presentCheck'] = $applicant['superior'];//添加当前审核人字段


        //添加操作者
        OperatorService::addOperator($data);

        $result = $this->add($data); //成功返回插入的自增id

        //添加预约记录失败
        if (!$result) {
            return JsonResult::fail('请假申请添加失败！', $result);// 返回JsonResult对象数据
        }

        //发邮件通知直接上级审核

        $superior = $userService->findById($data['superior']);
        //邮件主题
        $subject = '您有一个新事务需要处理';

        //邮件主体
        $body = '<h3>您有一个新事务需要处理</h3>';
        $body .= '<h3>请登录盟大协同办公系统,处理相关事务!</h3>';

        //发送邮件
        $result *= MailerService::sendEmail([$superior['email']], $subject, $body);

        //发送短信
        $message = '您有一个新事务需要处理!请登录盟大协同办公系统,处理相关事务!';
        $result *= SMSService::sendSMS([$superior['phone']], $message);


        return JsonResult::success('请假申请添加成功！', $result); //返回JsonResult对象数据
    }

    /**
     * 删除申请关闭或申请过期的休假申请
     * @param $ids
     * @return JsonResult
     */
    public function deleteCommit($ids) {

        //只能删除申请取消的记录
        $result = $this->fields('id')
            ->whereArr(array(
                ['id', 'IN', $ids],
                ['status', '=', '3'], //取消申请的记录
            ))
            ->find();
        if (!$result) {
            //没有记录
            return JsonResult::fail('没有可删除的记录');//框架返回JsonResult对象数据
        }

        $deleteIds = array();
        foreach ($result as $item) {
            //将选中的数据组织成一维id数组
            $deleteIds[] = $item['id'];
        }
        //删除记录
        $this->beginTransaction(); //开启事务
        $result = $this->where('id', 'IN', $deleteIds)->deletes();
        if (!$result) {
            $this->rollback(); //回滚事务
            return JsonResult::fail('删除失败！');//框架返回JsonResult对象数据
        }

        $this->commit(); //提交事务
        return JsonResult::success('删除成功！');//框架返回JsonResult对象数据
    }


    /**
     * 通过休假申请
     * @param $ids
     * @return JsonResult
     */
    public function pass($ids) {

        //当前操作人
        $user = IdentifyService::getUser(); //调用服务类的获取登录用户信息
        //查询休假申请详细信息
        $vacations = $this->where('id', 'IN', $ids)->find();

        $this->beginTransaction();  //开启事务
        $result = 1;
        $departmentHeadIds = array(); //存放部门负责人id
        $applicantIds = array(); //存放申请人id
        foreach ($vacations as $vacation) {
            //当前用户不是直接上级,也不是部门负责人,没有权限审核
            if ($vacation['superior'] != $user['id'] && $vacation['departmentHead'] != $user['id']) {
                return JsonResult::fail('您没有权限');
            }
            //当前审核人是直接上级,但是直接上级和部门不责任不是同一人
            if ($vacation['superior'] == $vacation['presentCheck']
                && $vacation['superior'] != $vacation['departmentHead']
                && $vacation['presentCheck'] == $user['id']
                && $vacation['status'] == '0') {

                //组织更新数据
                //当前审核人为直接上级:status字段保持不变,将当前审核人设置为部门负责人,直接上级审核结果设置为1:同意,结果反馈设置为:已同意
                $updateData = [
                    'presentCheck' => $vacation['departmentHead'],//当前审核人设置为部门负责人
                    'superiorCheckStatus' => '1', //直接上级审核结果设置为1:同意
                    'superiorCheckTime' => date('Y-m-d H:i'), //添加直接上级审核时间
                ];
                //添加操作人以及更新时间
                OperatorService::addOperator($updateData);

                $result *= $this->update($updateData, $vacation['id']);
                //发邮件通知部门负责人审核
                $departmentHeadIds[] = $vacation['departmentHead'];

            }
            //当前审核人是部门负责人,且操作人是当前审核人
            if ($vacation['departmentHead'] == $vacation['presentCheck']
                && $vacation['presentCheck'] == $user['id']
                && $vacation['status'] == '0') {

                //组织更新数据
                //当前审核人为部门负责人:status字段设置为1:通过,
                //当前审核人保持不变
                //直接上级审核结果设置为1:同意,
                //设置部门负责人审核结果为1:同意,
                //结果反馈设置为:已同意
                $updateData = [
                    'departmentHeadCheckStatus' => '1', //直接上级审核结果设置为1:同意
                    'departmentHeadCheckTime' => date('Y-m-d H;i'), //添加部门负责人审核时间
                    'status' => '1',   //字段设置为1:通过
                ];
                //直接上级和部门负责人是同一人
                if ($vacation['superior'] == $vacation['departmentHead']) {
                    //添加直接上级审核信息
                    $updateData['superiorCheckStatus'] = '1';//直接上级审核结果设置为1:同意
                    $updateData['superiorCheckTime'] = date('Y-m-d H:i');//添加直接上级审核时间
                }
                //添加操作人以及更新时间
                OperatorService::addOperator($updateData);

                $result *= $this->update($updateData, $vacation['id']);

                //发邮件通知申请人请假申请已通过
                $applicantIds[] = $vacation['applicant'];
            }
            if (!$result) {
                $this->rollback();  //回滚操作
                return JsonResult::fail('通过:操作失败'); //返回jsonResult对象数据
            }
        }


        //统一发送邮件,短信通知
        $userService = Loader::service(UserService::class); //调用用户服务类
        //部门负责人不为空
        if (sizeof($departmentHeadIds)) {
            $departmentHead = $userService->fields('email,phone')->where('id', 'IN', $departmentHeadIds)->find();
            $departmentHeadEmailArr = array();
            $departmentHeadPhoneArr = array();

            //循环将部门负责人邮箱,号码组织为一维数组
            foreach ($departmentHead as $user) {
                //邮件接收人
                $departmentHeadEmailArr[] = $user['email']; //接收人(部门负责人企业邮箱)

                //短信接收人
                $departmentHeadPhoneArr[] = $user['phone']; //接收人(部门负责人手机号)

            }
            //非空,发送邮件,短信给部门负责人
            if (sizeof($departmentHeadEmailArr) && sizeof($departmentHeadPhoneArr)) {
                //邮件主题
                $subject = '您有一个新事务需要处理';

                //邮件主体
                $body = '<h3>您有一个新事务需要处理</h3>';
                $body .= '<h3>请登录盟大协同办公系统,处理相关事务!</h3>';

                //发送邮件
                $result *= MailerService::sendEmail($departmentHeadEmailArr, $subject, $body);

                //发送短信
                $message = '您有一个新事务需要处理!请登录盟大协同办公系统,处理相关事务!';
                $result *= SMSService::sendSMS($departmentHeadPhoneArr, $message);
            }
        }

        if (sizeof($applicantIds)) {
            $applicant = $userService->fields('email,phone')->where('id', 'IN', $applicantIds)->find();
            $applicantEmailArr = array();
            $applicantPhoneArr = array();
            //循环将申请人邮箱,号码组织为一维数组
            foreach ($applicant as $user) {
                //邮件接收人
                $applicantEmailArr[] = $user['email']; //接收人(部门申请人企业邮箱)

                //短信接收人
                $applicantPhoneArr[] = $user['phone']; //接收人(部门申请人手机号)

            }
            //非空,发送邮件,短信给申请人
            if (sizeof($applicantEmailArr) && sizeof($applicantPhoneArr)) {
                //邮件主题
                $subject = '您的请假申请已通过!';

                //邮件主体
                $body = '<h3>您的请假申请已通过</h3>';
                $body .= '<h3>登录盟大协同办公系统查看详细信息!</h3>';

                //发送邮件
                $result *= MailerService::sendEmail($applicantEmailArr, $subject, $body);

                //发送短信
                $message = '您的请假申请已通过!登录盟大协同办公系统查看详细信息!';
                $result *= SMSService::sendSMS($applicantPhoneArr, $message);

            }
        }

        if (!$result) {
            $this->rollback();  //回滚操作
            return JsonResult::fail('操作失败'); //返回jsonResult对象数据
        }

        $this->commit();  //提交事务
        return JsonResult::success('操作成功'); //返回jsonResult对象数据

    }

    /**
     * 拒绝休假申请(申请中、申请成功的申请)=>拒绝申请
     *  上级发起拒绝申请(发送拒绝申请邮件)
     *  1.申请中=>拒绝申请
     *  2.申请通过=>拒绝申请
     * @param $ids ;申请id一维数组
     * @param $refuseReason ;拒绝申请理由
     * @return JsonResult
     */
    public function refuse($ids, $refuseReason) {
        $result = 1;
        //当前操作人
        $user = IdentifyService::getUser(); //调用服务类的获取登录用户信息
        $userService = Loader::service(UserService::class); //调用用户服务类
        $this->beginTransaction();  //开启事务

        //获取休假申请信息
        $vacation = $this->fields('id,applicant,superior,superiorCheckStatus,departmentHead,departmentHeadCheckStatus,presentCheck')
            ->where('id', 'IN', $ids)
            ->where('status', 'IN', [0, 1])
            ->find();  //服务类查找

        //当前用户不是直接上级,也不是部门负责人,没有权限审核
        if ($vacation['superior'] != $user['id'] && $vacation['departmentHead'] != $user['id']) {
            return JsonResult::fail('您没有权限');
        }
        //1.申请中(状态码:0)=>拒绝申请(状态码:2)
        //2.申请通过(状态码:1)=>拒绝申请(状态码:2)
        if ($vacation) {
            $vacationIdsArr = array();
            $vacationApplicantArr = array();
            foreach ($vacation as $item) {
                $vacationIdsArr[] = $item['id'];  //将数据提取为id一维数组
                $vacationApplicantArr[] = $item['applicant'];  //将数据提取为applicant为一维数组

                //如果当前操作人为直接上级且直接上级和部门负责人不是同一人
                //(数据字段的当前审核人可以不为直接上级),设置直接上级审核结果为2:拒绝
                if ($item['superior'] != $item['departmentHead']
                    && $item['superior'] == $user['id']) {

                    //组织更新数据
                    //当前审核人为直接上级:
                    //直接上级审核结果设置为2:拒绝
                    //status字段设置为2:拒绝
                    //当前审核人设置为直接上级
                    //直接上级审核结果设置为2:拒绝
                    //结果反馈设置为:$refuseReason
                    $updateData = [
                        'presentCheck' => $item['superior'],//当前审核人设置为直接上级
                        'superiorCheckStatus' => '2', //直接上级审核结果设置为2:拒绝
                        'status' => '2', //最终结果设置为2:拒绝
                        'superiorCheckTime' => date('Y-m-d H:i'), //添加直接上级审核时间
                        'refuseReason' => $refuseReason,  //结果反馈设置为:$refuseReason
                    ];
                    //添加操作人以及更新时间
                    OperatorService::addOperator($updateData);

                    $result *= $this->update($updateData, $item['id']);

                }

                //如果当前操作人为部门负责人,且数据字段的当前审核人为部门负责人
                //设置部门负责人审核结果为2:拒绝
                if ($item['presentCheck'] == $user['id'] && $item['departmentHead'] == $user['id']) {

                    //组织更新数据
                    //当前审核人为部门负责人:
                    //部门负责人审核结果设置为2:拒绝
                    //status字段设置为2:拒绝
                    //当前审核人设置为部门负责人
                    //部门负责人审核结果设置为2:拒绝
                    //结果反馈设置为:$refuseReason
                    $updateData = [
                        'presentCheck' => $item['departmentHead'],//当前审核人设置为部门负责人
                        'departmentHeadCheckStatus' => '2', //部门负责人审核结果设置为2:拒绝
                        'status' => '2', //最终结果设置为2:拒绝
                        'departmentHeadCheckTime' => date('Y-m-d H:i'), //添加部门负责人审核时间
                        'refuseReason' => $refuseReason,  //结果反馈设置为:$refuseReason
                    ];
                    //添加操作人以及更新时间
                    OperatorService::addOperator($updateData);

                    $result *= $this->update($updateData, $item['id']);
                }

            }

            //发送邮件,短信通知
            $applicant = $userService->fields('email,phone')->where('id', 'IN', $vacationApplicantArr)->find();
            $applicantEmailArr = array();
            $applicantPhoneArr = array();
            //循环将申请人邮箱,号码组织为一维数组
            foreach ($applicant as $user) {
                //邮件接收人
                $applicantEmailArr[] = $user['email']; //接收人(申请人企业邮箱)
                //短信接收人
                $applicantPhoneArr[] = $user['phone']; //接收人(申请人手机号)
            }

            //邮件主题
            $subject = '您的请假申请已被拒绝!';

            //邮件主体
            $body = '<h3 style="color:red;">您的请假申请已被拒绝</h3>';
            $body .= '<h3>登录盟大协同办公系统查看详细信息!</h3>';

            //发送邮件
            $result *= MailerService::sendEmail($applicantEmailArr, $subject, $body);

            //发送短信
            $message = '您的请假申请已被拒绝!登录盟大协同办公系统查看详细信息!';
            $result *= SMSService::sendSMS($applicantPhoneArr, $message);

            if (!$result) {
                $this->rollback();  //回滚操作
                return JsonResult::fail('操作失败'); //返回jsonResult对象数据
            }

        }
        $this->commit();  //提交事务
        return JsonResult::success('操作成功'); //返回jsonResult对象数据
    }

    /**
     * 取消休假申请(申请中、申请成功的申请)=>取消申请(申请人操作)
     *  1.申请中=>取消申请
     *  2.申请通过=>取消申请(发送取消申请邮件给直接上级(和部门负责人(已审核)))
     * @param $id ;申请id一维数组
     * @return JsonResult
     */
    public function cancel($id) {
        $result = 1; //操作成功判断标志
        //当前操作人
        $user = IdentifyService::getUser(); //调用服务类的获取登录用户信息
        $userService = Loader::service(UserService::class); //调用用户服务类
        $this->beginTransaction();  //开启事务

        //获取休假申请信息
        $vacation = $this->alias('a')
            ->fields('a.id,a.applicant,a.agent,b.username,a.superior,a.superiorCheckStatus,a.departmentHeadCheckStatus,a.departmentHead,a.status')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.applicant = b.id')
            ->where('a.id', $id)
            ->findOne();  //服务类查找

        if ($vacation) {
            //当前用户是申请人,或者是代理人,才有权限取消申请
            if ($vacation['applicant'] == $user['id'] || $vacation['agent'] == $user['id']) {
                //组织更新数据
                //status字段设置为3:取消
                $updateData = [
                    'status' => '3', //最终结果设置为2:取消
                ];
                //添加操作人以及更新时间
                OperatorService::addOperator($updateData);

                $result *= $this->update($updateData, $id);

                //发送邮件,短信通知(直接上级和部门负责人)
                //superiorCheckStatus=0:不发送邮件
                //superiorCheckStatus=1&&status=0:发送给直接上级
                //departmentHeadCheckStatus=1:发送给直接上级+部门负责人
                if ($vacation['superiorCheckStatus'] == '1' && $vacation['status'] == '0') {
                    $applicantIds = array($vacation['superior']);
                } elseif ($vacation['departmentHeadCheckStatus'] == '1') {
                    $applicantIds = array($vacation['superior'], $vacation['departmentHead']);
                } else {
                    $applicantIds = array();
                }

                //接收人不为空
                if (sizeof($applicantIds)) {
                    $applicant = $userService->fields('email,phone')
                        ->where('id', 'IN', $applicantIds)
                        ->find();
                    $applicantEmailArr = array();
                    $applicantPhoneArr = array();
                    //循环将申请人邮箱,号码组织为一维数组
                    foreach ($applicant as $user) {
                        //邮件接收人
                        $applicantEmailArr[] = $user['email']; //接收人(申请人企业邮箱)
                        //短信接收人
                        $applicantPhoneArr[] = $user['phone']; //接收人(申请人手机号)
                    }

                    //邮件主题
                    $subject = $vacation['username'] . '>>的请假申请已取消!';

                    //邮件主体
                    $body = '<h3><span style="color:orange;">' . $vacation['username'] . '</span>的请假申请已取消</h3>';
                    $body .= '<h3>登录盟大协同办公系统查看详细信息!</h3>';

                    //发送邮件
                    $result *= MailerService::sendEmail($applicantEmailArr, $subject, $body);

                    //发送短信
                    $message = $vacation['username'] . '>>的请假申请已被取消!登录盟大协同办公系统查看详细信息!';
                    $result *= SMSService::sendSMS($applicantPhoneArr, $message);
                }

                if (!$result) {
                    $this->rollback();  //回滚操作
                    return JsonResult::fail('操作失败'); //返回jsonResult对象数据
                }

            }
        } else {
            //当前用户不是申请人,也不是代理人,没有权限取消申请
            return JsonResult::fail('您没有权限');
        }
        //1.申请中(状态码:0)=>取消申请(状态码:3)
        //2.申请通过(状态码:1)=>取消申请(状态码:3)


        $this->commit();  //提交事务
        return JsonResult::success('操作成功'); //返回jsonResult对象数据
    }


    /**
     * 创建短信内容
     * @param $basicMessage
     * @param $id
     * @return string
     */
    public function createSMSMessage($basicMessage, $id) {

        //短信内容
        $applyInformation = $this->findById($id); //获取预约信息

        $basicMessage .= '，<h3>会议主题:《' . $applyInformation['theme'] . '》</h3>，请注意查收邮件！';

        return $basicMessage;
    }


}