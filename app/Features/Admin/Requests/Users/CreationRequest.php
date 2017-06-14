<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 上午9:17
 */

namespace App\Features\Admin\Requests\Users;


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
            'name'      => 'required|between:2,16',
            'email'     => "required|email|unique:users|max:64",
            'role'      => [
                "required",
                Rule::in(UserRole::listKeys())
            ],
            'password'  => 'required|between:6,16|confirmed'
        ];
    }

    public function attributes()
    {
        return [
            'name'  => '姓名'
        ];
    }

}