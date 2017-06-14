<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/15
 * Time: 上午11:55
 */

namespace Guxy\Common\Database;


/**
 * 通用Model仓库
 * Class DefaultRepository
 * @package Guxy\Common\Database
 */
class DefaultRepository extends Repository
{
    protected $model;

    /**
     * 构造函数 存储model类
     * DefaultRepository constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * 返回model
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }
}
