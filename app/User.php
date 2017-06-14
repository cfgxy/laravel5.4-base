<?php

namespace App;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Guxy\Common\Database\ExModel;

class User extends Authenticatable
{
    use Notifiable;
    use ExModel;
    use BelongsToTenants;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar_ver',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'email', 'role', 'created_at', 'updated_at',
    ];

    public function getAvatarAttribute()
    {
        $encid = guxy_encrypt($this->id);
        return "/avatar.php?$encid.{$this->avatar_ver}";
    }

    public function getAvatarPathAttribute()
    {
        $encid = guxy_encrypt($this->id);
        return storage_path("app/public/avatar/$encid.jpg");
    }

    public function getHasAvatarAttribute()
    {
        return is_file($this->avatar_path);
    }

    public function styledAvatar($style)
    {
        return "{$this->avatar}.$style";
    }
}
