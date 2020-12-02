<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-06-28
 */

namespace app\common\model;


class Position {

    //属性
    protected $id; //ID
    protected $departmentId;    //部门自增id外键
    protected $positionNo; //岗位编号
    protected $positionName; //岗位名称
    protected $members; //岗位人数
    protected $create_time; //创建时间
    protected $update_time;//更新时间

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
    public function getDepartmentIncId() {
        return $this->departmentIncId;
    }

    /**
     * @param mixed $departmentIncId
     */
    public function setDepartmentIncId($departmentIncId): void {
        $this->departmentIncId = $departmentIncId;
    }

    /**
     * @return mixed
     */
    public function getPositionNo() {
        return $this->positionNo;
    }

    /**
     * @param mixed $positionNo
     */
    public function setPositionNo($positionNo): void {
        $this->positionNo = $positionNo;
    }

    /**
     * @return mixed
     */
    public function getPositionName() {
        return $this->positionName;
    }

    /**
     * @param mixed $positionName
     */
    public function setPositionName($positionName): void {
        $this->positionName = $positionName;
    }

    /**
     * @return mixed
     */
    public function getMembers() {
        return $this->members;
    }

    /**
     * @param mixed $members
     */
    public function setMembers($members): void {
        $this->members = $members;
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