<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/23
 * Time: 上午8:23
 */

namespace App\Features\Admin;


use App\Model\Enums\UserRole;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;

class UserProvider implements \Illuminate\Contracts\Auth\UserProvider
{

    protected $hasher;

    protected $repo;


    public function __construct()
    {
        $this->hasher = app('hash');
        $this->repo = User::repository();
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $user = $this->repo->findOneById($identifier, [
            'role'  => UserRole::ADMIN
        ]);

        return $user;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = $this->repo->findOneById($identifier, [
            'role'  => UserRole::ADMIN,
            'remember_token' => $token
        ]);

        return $user;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        \DB::transaction(function() use($user) {
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
        });
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        unset($credentials['password']);

        if (!$credentials) {
            return null;
        }

        return $this->repo->findOne($credentials);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }
}