<?php

namespace App\Model;

use Guxy\Common\Database\ExModel;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;


class News extends Model
{
    use ExModel;


    protected $visible = [
        'id', 'title'
    ];
}
