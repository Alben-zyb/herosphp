<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-06-23
 * @detail 部门实体
 */

namespace app\common\model;


class Department
{

    protected $id; //ID
    protected $departmentNo;    //部门编号
    protected $departmentName; //部门名称
    protected $members; //部门人数
    protected $create_time; //创建时间
    protected $update_time; //更新时间

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getDepartmentNo() {
        return $this->departmentNo;
    }

    /**
     * @param mixed $departmentNo
     */
    public function setDepartmentNo($departmentNo): void {
        $this->departmentNo = $departmentNo;
    }

    /**
     * @return mixed
     */
    public function getDepartmentName() {
        return $this->departmentName;
    }

    /**
     * @param mixed $departmentName
     */
    public function setDepartmentName($departmentName): void {
        $this->departmentName = $departmentName;
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