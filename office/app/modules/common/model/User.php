<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-02
 */

namespace app\common\model;


class User {

    //属性
    protected $id; //ID
    protected $userNo; //工号
    protected $username; //用户姓名
    protected $phone; //手机号(登录)
    protected $email; //企业邮箱
    protected $departmentId;    //部门自增id外键
    protected $positionId;    //岗位自增id外键
    protected $operator;    //操作者
    protected $operatorId;    //操作者id外键
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
    public function getUserNo() {
        return $this->userNo;
    }

    /**
     * @param mixed $userNo
     */
    public function setUserNo($userNo): void {
        $this->userNo = $userNo;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDepartmentId() {
        return $this->departmentId;
    }

    /**
     * @param mixed $departmentId
     */
    public function setDepartmentId($departmentId): void {
        $this->departmentId = $departmentId;
    }

    /**
     * @return mixed
     */
    public function getPositionId() {
        return $this->positionId;
    }

    /**
     * @param mixed $positionId
     */
    public function setPositionId($positionId): void {
        $this->positionId = $positionId;
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