<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-15 下午7:01
 * @function  会议室模型
 */

namespace app\room\model;


class Entity {
    //属性
    protected $id; //ID
    protected $roomNo; //会议室编号
    protected $roomName; //会议室名称
    protected $device;    //设备情况
    protected $capacity;    //容纳人数
    protected $operator;    //操作者
    protected $operatorId;    //操作者id外键
    protected $create_time; //创建时间
    protected $update_time; //更新时间

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
    public function getRoomNo() {
        return $this->roomNo;
    }

    /**
     * @param mixed $roomNo
     */
    public function setRoomNo($roomNo): void {
        $this->roomNo = $roomNo;
    }

    /**
     * @return mixed
     */
    public function getRoomName() {
        return $this->roomName;
    }

    /**
     * @param mixed $roomName
     */
    public function setRoomName($roomName): void {
        $this->roomName = $roomName;
    }

    /**
     * @return mixed
     */
    public function getDevice() {
        return $this->device;
    }

    /**
     * @param mixed $device
     */
    public function setDevice($device): void {
        $this->device = $device;
    }

    /**
     * @return mixed
     */
    public function getCapacity() {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity): void {
        $this->capacity = $capacity;
    }

    /**
     * @return mixed
     */
    public function getOperator() {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator($operator): void {
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getOperatorId() {
        return $this->operatorId;
    }

    /**
     * @param mixed $operatorId
     */
    public function setOperatorId($operatorId): void {
        $this->operatorId = $operatorId;
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