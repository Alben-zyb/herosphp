<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-08-19 上午10:09
 * @function
 */

namespace app\supply\model;


class Storehouse {

    //属性
    protected $id; //ID
    protected $category; // 分类id
    protected $goodsNo; // 物品编号
    protected $goodsName; // 物品名称
    protected $number; // 物品数量
    protected $purchaser; // 采购员id
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
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getGoodsNo() {
        return $this->goodsNo;
    }

    /**
     * @param mixed $goodsNo
     */
    public function setGoodsNo($goodsNo): void {
        $this->goodsNo = $goodsNo;
    }

    /**
     * @return mixed
     */
    public function getGoodsName() {
        return $this->goodsName;
    }

    /**
     * @param mixed $goodsName
     */
    public function setGoodsName($goodsName): void {
        $this->goodsName = $goodsName;
    }

    /**
     * @return mixed
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getPurchaser() {
        return $this->purchaser;
    }

    /**
     * @param mixed $purchaser
     */
    public function setPurchaser($purchaser): void {
        $this->purchaser = $purchaser;
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