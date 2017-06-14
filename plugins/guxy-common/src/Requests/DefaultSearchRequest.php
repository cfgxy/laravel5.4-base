<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/15
 * Time: 下午9:14
 */

namespace Guxy\Common\Requests;
/**
 * 默认的搜索查询验证 默认继承类
 * Class DefaultSearchRequest
 * @package App\Http
 */
class DefaultSearchRequest extends ActionRequest
{
    protected $bool_fields  = [];
    protected $int_fields   = [];
    protected $float_fields = [];
    protected $text_fields  = [];
    protected $comma_array_fields = [];
    protected $order_fields = [];

    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [
            'page'      => 'sometimes|between:1,5000',
            'size'      => 'sometimes|between:1,200',
            'orderby_field' => 'sometimes|in:' . implode(',', array_values($this->order_fields))
        ];
    }

    /**
     * 整理数据
     * @return array
     */
    public function sanitize()
    {
        $this->numberInt($this->query,   ['page', 'size']);
        $this->numberBoolean($this->query, ['show_all']);
        $this->text($this->query,        ['keyword']);
        $this->removeEmpty($this->query, ['keyword']);

        $addon_fields = array_merge(
            $this->bool_fields,
            $this->int_fields,
            $this->float_fields,
            $this->text_fields,
            $this->comma_array_fields
        );

        $this->trim($this->query, $addon_fields);
        $this->removeEmpty($this->query, $addon_fields);

        if ($this->text_fields) {
            $this->text($this->query, $this->text_fields);
        }

        if ($this->comma_array) {
            $this->text($this->query, $this->comma_array);
        }

        if ($this->int_fields) {
            $this->numberInt($this->query, $this->int_fields);
        }

        if ($this->float_fields) {
            $this->numberFloat($this->query, $this->float_fields);
        }

        if ($this->bool_fields) {
            $this->numberBoolean($this->query, $this->bool_fields);
        }

        foreach ($this->order_fields as $k => $field) {
            if (is_numeric($k) && !array_key_exists($field, $this->order_fields)) {
                $this->order_fields[$field] = $field;
                unset($this->order_fields[$k]);
            }
        }

        $ret = guxy_mask($this->query, array_merge(['page', 'size', 'keyword', 'show_all'], $addon_fields));

        $orderby = $this->query->get('orderby');
        if ($orderby) {
            $orderby = strtolower($orderby);
            $orderby = explode(' ', $orderby);

            $orderby_field = trim($orderby[0]);

            $ret['orderby_field'] = @$this->order_fields[$orderby_field] ?: $orderby_field;
            if (trim(@$orderby[1]) == 'desc') {
                $orderby_sort = 'desc';
            } else {
                $orderby_sort = 'asc';
            }

            $ret['orderby'] = "{$ret['orderby_field']} $orderby_sort";
        }

        return $ret;
    }

    /**
     * 验证失败返回信息
     * @return array
     */
    public function messages()
    {
        return [
            'page.between'          => ':attribute 必须介于 :min - :max',
            'size.between'          => ':attribute 必须介于 :min - :max',
            'orderby_field.in'      => '不支持该排序字段',
        ];
    }

}
