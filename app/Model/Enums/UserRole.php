<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 下午10:28
 */

namespace App\Model\Enums;

use Guxy\Common\ExEnum;


/**
 * Enum演示
 *
 * @Enum({"ADMIN",  "管理员"})
 * @Enum({"MEMBER", "会员"})
 */
class UserRole
{
    use ExEnum;

    const ADMIN   = 'admin';
    const MEMBER  = 'member';
}