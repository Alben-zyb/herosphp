<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-20 下午5:22
 * @function  会议室申请数据模型
 */

namespace app\room\model;


class Apply {

    //属性
    protected $id; //ID
    protected $roomId; //会议室id
    protected $applicant; //会议室申请人id
    protected $status;    //申请状态
    protected $start;    //开始使用
    protected $finish;    //结束使用
    protected $create_time; //创建时间
    protected $update_time;  //更新时间

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
    public function getRoomId() {
        return $this->roomId;
    }

    /**
     * @param mixed $roomId
     */
    public function setRoomId($roomId): void {
        $this->roomId = $roomId;
    }

    /**
     * @return mixed
     */
    public function getApplicant() {
        return $this->applicant;
    }

    /**
     * @param mixed $applicant
     */
    public function setApplicant($applicant): void {
        $this->applicant = $applicant;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start): void {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getFinish() {
        return $this->finish;
    }

    /**
     * @param mixed $finish
     */
    public function setFinish($finish): void {
        $this->finish = $finish;
    }

    /**
     * @return mixed
     */
    public function getCreateTime() {
        return $this->create_time;
    }

    /**
     * @param mixed $create_time
     */
    public function setCreateTime($create_time): void {
        $this->create_time = $create_time;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime() {
        return $this->update_time;
    }

    /**
     * @param mixed $update_time
     */
    public function setUpdateTime($update_time): void {
        $this->update_time = $update_time;
    }



}