<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 上午9:17
 */

namespace App\Features\Admin\Requests\News;

use Guxy\Common\Requests\MultiActions;

class UpdationRequest extends CreationRequest
{
    use MultiActions;

    protected $allowedMultiActions = [];
}