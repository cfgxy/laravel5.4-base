<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 下午10:06
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Guxy\Common\Database\ExModel;

class UserLoginLog extends Model
{
    use ExModel;

    public $timestamps = false;
    protected $dates = ['logon_at'];

}