<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-21 上午11:49
 * @function  参会人员联系表
 */

namespace app\room\model;


class MeetingMember {
    //属性
    protected $id; //ID
    protected $roomApplyId; //会议室申请id外键
    protected $userId; //用户id外键（参会人员id）

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRoomApplyId() {
        return $this->roomApplyId;
    }

    /**
     * @param mixed $roomApplyId
     */
    public function setRoomApplyId($roomApplyId): void {
        $this->roomApplyId = $roomApplyId;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void {
        $this->userId = $userId;
    }


}