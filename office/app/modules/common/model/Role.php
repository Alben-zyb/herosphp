<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-03
 */

namespace app\common\model;


class Role {
    //属性
    protected $id; //ID
    protected $roleName; //角色名称
    protected $detail; //角色描述
    protected $status; //启用状态
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
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * @param mixed $roleName
     */
    public function setRoleName($roleName): void {
        $this->roleName = $roleName;
    }

    /**
     * @return mixed
     */
    public function getDetail() {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail): void {
        $this->detail = $detail;
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