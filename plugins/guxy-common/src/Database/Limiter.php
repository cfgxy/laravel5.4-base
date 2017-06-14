<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/15
 * Time: 上午11:22
 */

namespace Guxy\Common\Database;

/**
 * 查询条件拼接类
 * Class Limiter
 * @package Guxy\Common\Database
 */
class Limiter
{
    const EQ = '=';
    const NOT_EQ = '<>';
    const LT = '<';
    const GT = '>';
    const LTE = '<=';
    const GTE = '>=';
    const LIKE = 'like';
    const NOT_LIKE = 'not like';
    const IN = 'in';
    const NOT_IN = 'not in';
    const BETWEEN = 'in';
    const NOT_BETWEEN = 'not in';
    const CALLBACK = 'callback';

    public $field;
    public $value;
    public $operator;

    /**
     * 构造拼接内容
     * Limiter constructor.
     * @param $field
     * @param $operator
     * @param null $value
     */
    public function __construct($field, $operator, $value = null)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * 返回拼接内容对象
     * @param $field
     * @param $operator
     * @param null $value
     * @return static
     */
    public static function make($field, $operator, $value = null)
    {
        return new static($field, $operator, $value);
    }
}
