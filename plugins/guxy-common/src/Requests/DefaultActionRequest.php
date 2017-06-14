<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/24
 * Time: 下午4:25
 */

namespace Guxy\Common\Requests;

/**
 * 默认的提交字段验证  默认继承类
 * Class DefaultActionRequest
 * @package App\Http
 */
class DefaultActionRequest extends ActionRequest
{
    public function rules()
    {
        return [];
    }
}