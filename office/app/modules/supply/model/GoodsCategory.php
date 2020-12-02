<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-18 下午5:18
 * @function
 */

namespace app\supply\model;


class GoodsCategory {

    //属性
    protected $id; //ID
    protected $categoryNo; // 分类编号
    protected $categoryName; // 分类名称
    protected $operator; // 操作者
    protected $operatorId; // 操作者id
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
    public function getCategoryNo() {
        return $this->categoryNo;
    }

    /**
     * @param mixed $categoryNo
     */
    public function setCategoryNo($categoryNo): void {
        $this->categoryNo = $categoryNo;
    }

    /**
     * @return mixed
     */
    public function getCategoryName() {
        return $this->categoryName;
    }

    /**
     * @param mixed $categoryName
     */
    public function setCategoryName($categoryName): void {
        $this->categoryName = $categoryName;
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