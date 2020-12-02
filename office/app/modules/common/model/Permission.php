<?php
/**
 * @author ZhengYiBin<zhengyb@pvc123.com>
 * @date   2020-07-04
 */

namespace app\common\model;


class Permission {

    //属性
    protected $id; //ID
    protected $permissionName; //权限名称
    protected $rule; //权限规则
    protected $module; //模块
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
    public function getPermissionName() {
        return $this->permissionName;
    }

    /**
     * @param mixed $permissionName
     */
    public function setPermissionName($permissionName): void {
        $this->permissionName = $permissionName;
    }

    /**
     * @return mixed
     */
    public function getRule() {
        return $this->rule;
    }

    /**
     * @param mixed $rule
     */
    public function setRule($rule): void {
        $this->rule = $rule;
    }

    /**
     * @return mixed
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module): void {
        $this->module = $module;
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