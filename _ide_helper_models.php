<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Guxy\Common\Database{
/**
 * DefaultRepository
 *
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPager($limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQuery($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilder($limiters = [], $options = [])
 * @method string getSQL($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Model findOne($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[] find($limiters = [], $options = [])
 * @method int count($limiters = [], $options = [])
 */
	 abstract class DefaultRepository extends \Guxy\Common\Database\Repository {}
}

namespace App\Model{
/**
 * App\Model\UserLoginLog
 *
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property \Carbon\Carbon $logon_at
 * @method static \Illuminate\Database\Query\Builder|\App\Model\UserLoginLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\UserLoginLog whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\UserLoginLog whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\UserLoginLog whereLogonAt($value)
 * @method static \App\Model\UserLoginLogRepository repository()
 */
	class UserLoginLog extends \Eloquent {}
}

namespace App\Model{
/**
 * UserLoginLogRepository
 *
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerById($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryById($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderById($id, $limiters = [], $options = [])
 * @method string getSQLById($id, $limiters = [], $options = [])
 * @method \App\Model\UserLoginLog findOneById($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\Model\UserLoginLog[] findById($id, $limiters = [], $options = [])
 * @method int countById($id, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByUid($uid, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByUid($uid, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByUid($uid, $limiters = [], $options = [])
 * @method string getSQLByUid($uid, $limiters = [], $options = [])
 * @method \App\Model\UserLoginLog findOneByUid($uid, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\Model\UserLoginLog[] findByUid($uid, $limiters = [], $options = [])
 * @method int countByUid($uid, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByIp($ip, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByIp($ip, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByIp($ip, $limiters = [], $options = [])
 * @method string getSQLByIp($ip, $limiters = [], $options = [])
 * @method \App\Model\UserLoginLog findOneByIp($ip, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\Model\UserLoginLog[] findByIp($ip, $limiters = [], $options = [])
 * @method int countByIp($ip, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByLogonAt($logon_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByLogonAt($logon_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByLogonAt($logon_at, $limiters = [], $options = [])
 * @method string getSQLByLogonAt($logon_at, $limiters = [], $options = [])
 * @method \App\Model\UserLoginLog findOneByLogonAt($logon_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\Model\UserLoginLog[] findByLogonAt($logon_at, $limiters = [], $options = [])
 * @method int countByLogonAt($logon_at, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPager($limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQuery($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilder($limiters = [], $options = [])
 * @method string getSQL($limiters = [], $options = [])
 * @method \App\Model\UserLoginLog findOne($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\Model\UserLoginLog[] find($limiters = [], $options = [])
 * @method int count($limiters = [], $options = [])
 * @method \App\Model\UserLoginLog retrieveByPK($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\Model\UserLoginLog[] retrieveByPKs($ids, $limiters = [], $options = [])
 */
	 abstract class UserLoginLogRepository extends \Guxy\Common\Database\Repository {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property boolean $enabled
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $role
 * @property integer $avatar_ver
 * @property-read mixed $avatar
 * @property-read mixed $avatar_path
 * @property-read mixed $has_avatar
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAvatarVer($value)
 * @method static \App\UserRepository repository()
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * UserRepository
 *
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerById($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryById($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderById($id, $limiters = [], $options = [])
 * @method string getSQLById($id, $limiters = [], $options = [])
 * @method \App\User findOneById($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findById($id, $limiters = [], $options = [])
 * @method int countById($id, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByName($name, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByName($name, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByName($name, $limiters = [], $options = [])
 * @method string getSQLByName($name, $limiters = [], $options = [])
 * @method \App\User findOneByName($name, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByName($name, $limiters = [], $options = [])
 * @method int countByName($name, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByEmail($email, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByEmail($email, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByEmail($email, $limiters = [], $options = [])
 * @method string getSQLByEmail($email, $limiters = [], $options = [])
 * @method \App\User findOneByEmail($email, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByEmail($email, $limiters = [], $options = [])
 * @method int countByEmail($email, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByPassword($password, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByPassword($password, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByPassword($password, $limiters = [], $options = [])
 * @method string getSQLByPassword($password, $limiters = [], $options = [])
 * @method \App\User findOneByPassword($password, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByPassword($password, $limiters = [], $options = [])
 * @method int countByPassword($password, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByEnabled($enabled, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByEnabled($enabled, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByEnabled($enabled, $limiters = [], $options = [])
 * @method string getSQLByEnabled($enabled, $limiters = [], $options = [])
 * @method \App\User findOneByEnabled($enabled, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByEnabled($enabled, $limiters = [], $options = [])
 * @method int countByEnabled($enabled, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByRememberToken($remember_token, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByRememberToken($remember_token, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByRememberToken($remember_token, $limiters = [], $options = [])
 * @method string getSQLByRememberToken($remember_token, $limiters = [], $options = [])
 * @method \App\User findOneByRememberToken($remember_token, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByRememberToken($remember_token, $limiters = [], $options = [])
 * @method int countByRememberToken($remember_token, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByCreatedAt($created_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByCreatedAt($created_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByCreatedAt($created_at, $limiters = [], $options = [])
 * @method string getSQLByCreatedAt($created_at, $limiters = [], $options = [])
 * @method \App\User findOneByCreatedAt($created_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByCreatedAt($created_at, $limiters = [], $options = [])
 * @method int countByCreatedAt($created_at, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method string getSQLByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method \App\User findOneByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method int countByUpdatedAt($updated_at, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByRole($role, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByRole($role, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByRole($role, $limiters = [], $options = [])
 * @method string getSQLByRole($role, $limiters = [], $options = [])
 * @method \App\User findOneByRole($role, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByRole($role, $limiters = [], $options = [])
 * @method int countByRole($role, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPagerByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQueryByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilderByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method string getSQLByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method \App\User findOneByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] findByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method int countByAvatarVer($avatar_ver, $limiters = [], $options = [])
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator getPager($limiters = [], $options = [])
 * @method \Illuminate\Database\Query\Builder getQuery($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Builder getQueryBuilder($limiters = [], $options = [])
 * @method string getSQL($limiters = [], $options = [])
 * @method \App\User findOne($limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] find($limiters = [], $options = [])
 * @method int count($limiters = [], $options = [])
 * @method \App\User retrieveByPK($id, $limiters = [], $options = [])
 * @method \Illuminate\Database\Eloquent\Collection|\App\User[] retrieveByPKs($ids, $limiters = [], $options = [])
 */
	 abstract class UserRepository extends \Guxy\Common\Database\Repository {}
}

