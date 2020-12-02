<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-20 下午5:27
 * @function  会议室申请服务类
 */

namespace app\room\service;


use app\api\service\SMSService;
use app\common\dao\UserDao;
use app\room\dao\ApplyDao;
use app\room\dao\EntityDao;
use app\room\dao\MeetingMemberDao;
use herosphp\model\CommonService;
use herosphp\filter\Filter;
use herosphp\core\Loader;
use herosphp\utils\JsonResult;
use app\api\service\MailerService;


class ApplyService extends CommonService {

    //属性
    protected $modelClassName = ApplyDao::class;

    /**
     * 根据页码、每页显示条数、查询条件查询分页数据，返回一个和layui前端框架的table组件相对应的数据格式（二维数组）
     * @param array $search
     * @return JsonResult
     */
    public function getPageData($search = array()) {

        //数据过滤规则

        $filterRoomId = array(
            'roomId' => array(Filter::DFILTER_NUMERIC, NULL, NULL, array("type" => "会议室id数据类型有误！")
            ));
        $filterApplicant = array(
            'applicant' => array(Filter::DFILTER_STRING, NULL, Filter::DFILTER_SANITIZE_TRIM | Filter::DFILTER_SANITIZE_SCRIPT | Filter::DFILTER_SANITIZE_HTML | Filter::DFILTER_MAGIC_QUOTES,
                array("type" => "会议室申请人数据类型有误！")
            ));
        $filterStatus = array(
            'status' => array(Filter::DFILTER_NUMERIC, NULL, NULL, array("type" => "申请状态数据类型有误！")
            ));

        //前端输入的查询条件
        $searchRoomId = array(
            'roomId' => $search['roomId']
        );
        $searchApplicant = array(
            'applicant' => $search['applicant']
        );
        $searchStatus = array(
            'status' => $search['status']
        );

        $searchCondition = array();//查询的最终条件


        //会议室id条件过滤添加
        if ($result = Filter::loadFromModel($searchRoomId, $filterRoomId, $error)) {
            $searchCondition[] = array('a.roomId', '=', $result['roomId']);
        }
        //申请人条件过滤添加
        if ($result = Filter::loadFromModel($searchApplicant, $filterApplicant, $error)) {
            if (is_numeric($result['applicant'])) {
                $searchCondition[] = array('b.userNo', '=',  $result['applicant'] );
            } else {
                $searchCondition[] = array('b.username', 'LIKE', '%' . $result['applicant'] . '%');
            }
        }
        //设备情况描述条件过滤添加
        if ($result = Filter::loadFromModel($searchStatus, $filterStatus, $error)) {
            if ($result['status'] != '-1') {
                $searchCondition[] = array('a.status', '=', $result['status']);
            }
        }

        //日期条件
        if ($search['dateStart']) {
            $searchCondition[] = array('date', '>=', $search['dateStart']);

        }
        if ($search['dateEnd']) {
            $searchCondition[] = array('date', '<', $search['dateEnd']);

        }
        //时间条件，配合时间类型：开始使用｜结束使用
        if ($search['start']) {
            $searchCondition[] = array($search['timeType'], '>', $search['start']);

        }
        if ($search['finish']) {
            $searchCondition[] = array($search['timeType'], '<', $search['finish']);
        }

        //先进行一个更新操作,根据当前时间更新会议室状态
        $this->updateBaseOnTime();

        //使用模型方法(调用连接查询方法)
        $applyModel = Loader::model(ApplyDao::class);
        $count = $applyModel->alias('a')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.applicant = b.id')
            ->whereArr($searchCondition)
            ->count();//获取数据总条数（加入查询条件）
        if ($count < $search['limit']) {
            $search['page'] = 1; //添加条件，防止在非第一页搜索时，出现没有数据的情况
        }
        //使用连接查询，查找申请人工号姓名
        $data1 = $applyModel->alias('a')
            ->fields('a.id, a.roomId,b.userNo,b.username,a.theme,a.status,a.date,a.start,a.finish,a.create_time,a.update_time')
            ->join('user b', MYSQL_JOIN_LEFT)
            ->on('a.applicant = b.id')
            ->whereArr($searchCondition)
            ->page($search['page'], $search['limit'])
            ->order('a.date desc')
            ->find();

        //提取查询到的数据中的roomId
        $entityService = new EntityService(); //实例化会议室实体服务类
        $data2 = array();
        foreach ($data1 as $item) {
            $temp = $entityService->fields('roomNo,roomName')->where('id', '=', $item['roomId'])->find();
            $data2[] = $temp[0];
        }

        //循环合并数组
        $data = array();
        foreach ($data1 as $key => $value) {
            $data[$key] = array_merge($value, $data2[$key]);
            $data[$key]['start'] = substr($data[$key]['start'],0,5);
            $data[$key]['finish'] = substr($data[$key]['finish'],0,5);
        }

        return JsonResult::layTable($data, $count); //返回JsonResult对象数据
    }

    /**
     * 根据当前时间更新会议室状态
     */
    public function updateBaseOnTime() {
        //获取当前时间有误
        $datetime = date('Y-m-d H:i:s'); //当前日期+时间
        $date = substr($datetime, 0, 10);  //截取当前日期
        $time = substr($datetime, 11);  //截取当前时间

        //情况:1
        //申请中:0=>申请过期:4
        //设置申请过期(到期没有通过申请)
        $this->whereArr(array(
            ['date', '<', $date],
            ['status', '=', 0],
        ))->sets('status', 4);
        $this->whereArr(array(
            ['date', '=', $date],
            ['start', '<=', $time],
            ['status', '=', 0],
        ))->sets('status', 4);

        //情况:2
        //申请通过:1=>正在使用:2
        //设置正在使用
        $this->whereArr(array(
            ['date', '=', $date],
            ['start', '<=', $time],
            ['finish', '>', $time],
            ['status', '=', 1],
        ))->sets('status', 2);


        //情况:3
        //正在使用:2=>已使用:3
        //设置已使用
        $this->whereArr(array(
            ['date', '<', $date],
            ['status', 'IN', ['1', '2']],
        ))->sets('status', 3);
        $this->whereArr(array(
            ['date', '=', $date],
            ['finish', '<=', $time],
            ['status', 'IN', ['1', '2']],
        ))->sets('status', 3);

    }


    /**
     * 根据会议室实体id获取会议室详细信息
     * @param $id
     * @return  JsonResult
     */
    public function getRoomByRid($id) {
        $entityService = Loader::service(EntityService::class);  //调用会议室实体服务类
        $data = $entityService->findById($id);
        if (!$data) {
            return JsonResult::fail('', $data);//返回JsonResult对象
        }
        return JsonResult::success('', $data);//返回JsonResult对象
    }

    /**
     * 根据会议室预约id获取会议室预约详细信息
     * @param $id
     * @return  JsonResult
     */
    public function getApplyByAid($id) {
        $data = $this->findById($id);
        if (!$data) {
            return JsonResult::fail('', $data);//返回JsonResult对象
        }
        return JsonResult::success('', $data);//返回JsonResult对象
    }

    /**
     * 根据用户id获取会议室申请人详细信息
     * @param $id
     * @return  JsonResult
     */
    public function getApplicantByUid($id) {
        $userModel = Loader::model(UserDao::class);  //调用用户数据连接模型

        $data = $userModel->alias('a')
            ->fields('a.id,a.userNo,a.username,b.departmentName,a.phone,a.email')
            ->join('department b', MYSQL_JOIN_LEFT)
            ->on('a.departmentId=b.id')
            ->where('a.id', $id)
            ->find();
        if (!$data) {
            return JsonResult::fail('', $data['0']);//返回JsonResult对象
        }
        return JsonResult::success('', $data['0']);//返回JsonResult对象
    }

    /**
     * 根据会议室预约id获取参会人员详细信息
     * @param $id
     * @return  JsonResult
     */
    public function getMeetingMemberByAid($id) {
        if (!is_numeric($id)) {
            JsonResult::layTable('')->output();//框架加工json格式数据才能返回
        }
        $userModel = Loader::model(UserDao::class);//调用UserDao用户模型
        $meetingMemberModel = Loader::model(MeetingMemberDao::class);//调用MeetingMember参会人员模型
        $userIds = array(); //存放参会人员id（一维数组）
        $result = $meetingMemberModel->fields('userId')->where('roomApplyId', $id)->find(); //获取参会人员的id
        if (!$result) {
            return JsonResult::layTable($result);//返回JsonResult对象
        }
        //转为一维数组
        foreach ($result as $item) {
            $userIds[] = $item['userId'];
        }
        $result = $userModel->alias('a')
            ->fields('a.id, a.userNo,a.username,a.phone,a.email,b.departmentName')
            ->join('department b', MYSQL_JOIN_LEFT)
            ->on('a.departmentId=b.id')
            ->where('a.id', 'IN', $userIds)
            ->find();
        return JsonResult::layTable($result);//返回JsonResult对象
    }

    /**
     * 删除申请关闭或申请过期的预约
     * @param $ids
     * @return JsonResult
     */
    public function deleteCommit($ids) {

        //获取删除数据的id
        $meetingMember = Loader::model(MeetingMemberDao::class); //调用参会人员数据连接模型

        //只能删除申请过期和申请关闭的预约
        $result = $this->whereArr(array(
            ['id', 'IN', $ids],
            ['status', '<', 3],
        ))
            ->findOne();
        if ($result) {
            //申请过期或申请关闭的情况
            return JsonResult::fail('只能删除已使用、申请过期和申请关闭的预约');//框架返回JsonResult对象数据
        }

        $this->beginTransaction(); //开启事务
        $meetingMember->beginTransaction(); //开启事务
        foreach ($ids as $id) {
            //删除会议室预约信息
            $result = $this->delete($id);
            if (!$result) {
                $meetingMember->rollback(); //回滚事务
                $this->rollback(); //回滚事务
                return JsonResult::fail('删除失败！');//框架返回JsonResult对象数据
            }

            //删除参会人员
            //查询删除
            $result = $meetingMember->where('roomApplyId', $id)->find();
            if ($result) {
                $result = $meetingMember->where('roomApplyId', $id)->deletes();
                if (!$result) {
                    $meetingMember->rollback(); //回滚事务
                    $this->rollback(); //回滚事务
                    return JsonResult::fail('删除失败！');//框架返回JsonResult对象数据
                }
            }
        }
        $meetingMember->commit(); //提交事务
        $this->commit(); //提交事务
        return JsonResult::success('删除成功！');//框架返回JsonResult对象数据
    }


    /**
     * 通过预约申请
     * @param $ids
     * @return JsonResult
     */
    public function pass($ids) {
        $this->beginTransaction();  //开启事务
        $result = $this->where('id', 'IN', $ids)
            ->where('status', '=', 0)
            ->sets('status', 1);  //服务类设置字段值
        if (!$result) {
            $this->rollback();  //回滚操作
            return JsonResult::fail('通过:操作失败'); //返回jsonResult对象数据
        }
        //遍历通过的预约,发送邮件通知,发送短信通知
        foreach ($ids as $id) {
            //邮件接收人
            $meetingMember = $this->getMeetingMemberByApplyId($id); //接收人(参会人员)
            //邮件主题
            $subject = '您有一个新会议';

            //邮件主体
            $body = $this->createEmailBody($meetingMember['meetingMemberName'], '', $id);

            //发送邮件
            $result *= MailerService::sendEmail($meetingMember['recipientsEmail'], $subject, $body);

            //发送短信
            $message = $this->createSMSMessage($subject, '', $id);
            $result *= SMSService::sendSMS($meetingMember['recipientsPhone'], $message);

            if (!$result) {
                $this->rollback();  //回滚操作
                return JsonResult::fail('通过:操作失败(邮件、短信发送失败)'); //返回jsonResult对象数据
            }
        }

        $this->commit();  //提交事务
        return JsonResult::success('通过:操作成功(成功发送邮件、短信)'); //返回jsonResult对象数据
    }

    /**
     * 撤销预约(申请中、申请成功的预约)=>关闭申请
     *
     *  1.申请中=>关闭申请
     *      1.1.管理员发起关闭申请(拒绝申请)(发送拒绝申请邮件,会议发起人)
     *      1.2.申请人发起关闭申请(撤回申请)(不发送邮件)
     *
     *  2.申请通过=>关闭申请
     *      2.1.管理员发起关闭申请(撤回通过)(发送取消会议邮件,所有参会人员)
     *      2.2.申请人发起关闭申请(撤回通过)(发送取消会议邮件,所有参会人员)
     *
     * @param $ids ;预约id一维数组
     * @param $isAdmin ;判断是申请人发起的关闭操作还是管理员
     * @return JsonResult
     */
    public function close($ids, $isAdmin = false) {
        $this->beginTransaction();  //开启事务
        //1.申请中=>关闭申请
        $status0ids = $this->fields('id')
            ->where('id', 'IN', $ids)
            ->where('status', 0)
            ->find();  //服务类查找

        //2.申请通过=>关闭申请
        $status1ids = $this->fields('id')
            ->where('id', 'IN', $ids)
            ->where('status', 1)
            ->find();  //服务类查找

        //1.申请中=>关闭申请
        if ($status0ids) {
            //将id二维数组转为一维数组
            $status0idsArr = array();
            foreach ($status0ids as $item) {
                $status0idsArr[] = $item['id'];
            }
            $result = $this->where('id', 'IN', $status0idsArr)->sets('status', 5);  //服务类设置字段值

            //1.1管理员发起关闭申请,发送邮件通知给申请人
            if ($isAdmin) {
                //邮件主题
                $subject = '!!!您预约的会议室没有通过!';
                $body = '<h2 style="color: red;">会议室预约不通过!!!</h2>';
                //遍历被关闭的预约,发送邮件通知
                foreach ($status0idsArr as $id) {
                    //参会人员
                    $meetingMember = $this->getMeetingMemberByApplyId($id); //(参会人员)

                    //邮件接收人
                    $applyInformation = $this->findById($id); //获取预约信息
                    $userModel = Loader::model(UserDao::class);  //调用用户数据连接模型
                    $recipient = $userModel->findById($applyInformation['applicant']); //发起人信息


                    //邮件主体
                    $body = $this->createEmailBody($meetingMember['meetingMemberName'], $body, $id);

                    $result *= MailerService::sendEmail([$recipient['email']], $subject, $body);
                    //发送短信
                    $message = $this->createSMSMessage($subject, '', $id);
                    $result *= SMSService::sendSMS($meetingMember['recipientsPhone'], $message);
                }

                if (!$result) {
                    $this->rollback();  //回滚操作
                    return JsonResult::fail('拒绝:操作失败'); //返回jsonResult对象数据
                }
            }
            //1.2申请人发起关闭申请,不发送邮件

        }


        //2.申请通过=>关闭申请
        if ($status1ids) {
            //将id二维数组转为一维数组
            $status1idsArr = array();
            foreach ($status1ids as $item) {
                $status1idsArr[] = $item['id'];
            }
            $result = $this->where('id', 'IN', $status1idsArr)->sets('status', 5);  //服务类设置字段值


            //2.1管理员发起关闭申请,发送邮件通知给所有参会人员
            //2.2申请人发起关闭申请,发送邮件通知给所有参会人员

            //邮件主题
            $subject = '!!!会议室已取消!';
            $body = '<h2 style="color: red;">!!!会议室已取消!</h2>';
            //遍历被关闭的预约,发送邮件通知
            foreach ($status1idsArr as $id) {
                //邮件接收人
                $meetingMember = $this->getMeetingMemberByApplyId($id); //接收人(参会人员)
                $body = $this->createEmailBody($meetingMember['meetingMemberName'], $body, $id);
                $result *= MailerService::sendEmail($meetingMember['recipientsEmail'], $subject, $body);
                //发送短信
                $message = $this->createSMSMessage($subject, '', $id);
                $result *= SMSService::sendSMS($meetingMember['recipientsPhone'], $message);
            }
            if (!$result) {
                $this->rollback();  //回滚操作
                return JsonResult::fail('取消会议:操作失败'); //返回jsonResult对象数据
            }
        }
        $this->commit();  //提交事务
        return JsonResult::success('取消会议:操作成功'); //返回jsonResult对象数据
    }

    /**
     * 根据预约id获取会议室申请人以及参会人员的邮箱和名字信息(返回二维数组)
     * @param $Aid
     * @return array(返回二维数组)
     */
    protected function getMeetingMemberByApplyId($Aid) {

        //根据预约id获取参会人员
        $meetingMemberDao = Loader::model(MeetingMemberDao::class); //加载参会人员数据连接模型
        $meetingMemberUid = $meetingMemberDao->fields('userId')->where('roomApplyId', $Aid)->find();
        //转化为一维数据
        foreach ($meetingMemberUid as &$user) {
            $user = $user['userId'];
        }
        //获取申请人
        $result = $this->findById($Aid);
        $meetingMemberUid[] = $result['applicant']; //申请人id,申请人也添加到参会人员
        //获取参会人员邮箱、手机号
        $userModel = Loader::model(UserDao::class);  //调用用户服务类
        $result = $userModel->fields('username,email,phone')->where('id', 'IN', $meetingMemberUid)->find();
        $meetingMember = array();
        foreach ($result as $user) {
            $meetingMember['meetingMemberName'][] = $user['username'];
            $meetingMember['recipientsEmail'][] = $user['email'];
            $meetingMember['recipientsPhone'][] = $user['phone'];
        }
        return $meetingMember;
    }

    /**
     * 创建邮件主体
     * @param $recipientsName
     * @param $body
     * @param $id
     * @return string
     */
    public function createEmailBody($recipientsName, $body, $id) {
        $names = '';
        foreach ($recipientsName as $name) {
            $names .= $name . '；';
        }

        //邮件主体
        $applyInformation = $this->findById($id); //获取预约信息

        $userModel = Loader::model(UserDao::class);  //调用用户数据连接模型
        $applicant = $userModel->findById($applyInformation['applicant']); //发起人信息

        $entityModel = Loader::model(EntityDao::class);//调用会议室实体数据连接模型
        $room = $entityModel->findById($applyInformation['roomId']); //会议室信息

        $body .= '<h3>会议主题:《' . $applyInformation['theme'] . '》</h3>';
        $body .= '<h3>发起人：' . $applicant['username'] . '</h3>';
        $body .= '<h3>会议地点：' . $room['roomNo'] . '-' . $room['roomName'] . '</h3>';
        $body .= '<h3>会议日期：' . $applyInformation['date'] . '</h3>';
        $body .= '<h3>开始时间：' . $applyInformation['start'] . '</h3>';
        $body .= '<h3>结束时间：' . $applyInformation['finish'] . '</h3>';
        $body .= '<h3>参会人员：' . $names . '</h3>';
        return $body;
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