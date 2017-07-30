<?php

namespace App\Model;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\LaratrustRole;
use Guxy\Common\Database\ExModel;



class Role extends LaratrustRole
{
    use ExModel;

    protected $visible = [
        'id', 'name', 'display_name'
    ];
}
