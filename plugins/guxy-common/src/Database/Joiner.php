<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/29
 * Time: 下午5:03
 */

namespace Guxy\Common\Database;

/**
 * 数据库内，外，左右连接拼接类
 * Class Joiner
 * @package Guxy\Common\Database
 */
class Joiner
{
    const INNER_JOIN = 'inner';
    const LEFT_JOIN = 'left';
    const RIGHT_JOIN = 'right';
    const CROSS_JOIN = 'cross';

    public $type;
    public $right;
    public $on;

    /**
     * 构造连接内容
     * Joiner constructor.
     * @param $right
     * @param $on
     * @param string $type
     */
    public function __construct($right, $on, $type = 'inner')
    {
        $this->right = $right;
        $this->type = $type;
        $this->on = $on;
    }

    /**
     * 返回连接内容对象
     * @param $right
     * @param $on
     * @param string $type
     * @return static
     */
    public static function make($right, $on, $type = 'inner')
    {
        return new static($right, $on, $type);
    }
}