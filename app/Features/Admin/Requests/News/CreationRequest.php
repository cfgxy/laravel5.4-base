<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 上午9:17
 */

namespace App\Features\Admin\Requests\News;


use App\Model\Enums\UserRole;
use Illuminate\Validation\Rule;
use Guxy\Common\Requests\ActionRequest;

class CreationRequest extends ActionRequest
{

    /**
     * 数组化验证规则，需实现
     * @return mixed
     */
    public function rules()
    {
        return [
            'title'      => 'required|between:0,255',
            'content'    => "required"
        ];
    }

    public function attributes()
    {
        return [
            'title'   => '标题',
            'content' => '内容'
        ];
    }

}