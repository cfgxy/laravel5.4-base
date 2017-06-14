<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/15
 * Time: 下午9:14
 */

namespace Guxy\Common\Requests;
/**
 * 默认的删除验证 默认继承类
 * Class DefaultDestroyRequest
 * @package App\Http
 */
class DefaultDestroyRequest extends ActionRequest
{
    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * 整理提交的字段
     * @return array
     */
    public function sanitize()
    {
        $act = \Route::input('datum');
        if ($act !== 'selected' && !is_numeric($act)) {
            return [];
        }

        if (is_numeric($act)) {
            $this->query->set('ids', $act);
        }
        $this->commaArray($this->query, ['ids']);
        $this->numberInt($this->query, ['ids[]']);

        return guxy_mask($this->query, ['ids']);
    }

}
