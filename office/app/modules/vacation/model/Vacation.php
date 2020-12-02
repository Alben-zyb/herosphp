<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-07 下午6:53
 * @function
 */

namespace app\vacation\model;


class Vacation {
    //属性
    protected $id; //ID
    protected $userId; //用户id
    protected $type; //请假类型
    protected $start;    //假期开始时间
    protected $finish;    //假期结束时间
    protected $status;    //申请状态
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
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void {
        $this->type = $type;
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