<?php

namespace App\Model;

use Laratrust\LaratrustPermission;
use Guxy\Common\Database\ExModel;



class Permission extends LaratrustPermission
{
    use ExModel;


    protected $visible = [
        'id', 'name', 'display_name'
    ];
}
