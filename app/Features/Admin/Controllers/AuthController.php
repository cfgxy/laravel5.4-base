<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 下午7:15
 */

namespace App\Features\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Model\Enums\UserRole;
use App\Model\UserLoginLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Guxy\Common\Exceptions\AppException;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected function guard()
    {
        return \Auth::guard('admin');
    }

    public function username()
    {
        return 'email';
    }

    public function showLoginForm()
    {
        return view('FT.admin::login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws AppException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw new AppException(trans('auth.failed'), 1);
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws AppException
     */
    protected function authenticated(Request $request, $user)
    {
        $log = new UserLoginLog();
        $log->uid = $user->id;
        $log->logon_at = $log->freshTimestamp();
        $log->ip = $request->ip();

        \DB::transaction(function() use($log) {
            $log->save();
        });

        return guxy_json_message([
            'name'  => $user->name
        ]);
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/admin/');
    }

    public function loginInfo()
    {
        /** @var User $user */
        $user = $this->guard()->user();
        
        
        
        if ($user) {
            $log = UserLoginLog::repository()->findOneByUid($user->id, [], [
                'orderby'   => 'id desc'
            ]);

            return guxy_json_message([
                'is_login'      => true,
                'uid'           => $user->id,
                'avatar'        => $user->avatar,
                'avatar_small'  => $user->styledAvatar('small'),
                'name'          => $user->name,
                'role'          => UserRole::display($user->role),
                'last_login'    => $log->logon_at->format('Y-m-d H:i:s'),

                'messages'      => [
                    'nums'      => 0,
                    'data'      => [
//                        [
//                            'id'        => 1,
//                            'user'  => [
//                                'id'        => 1,
//                                'avatar'    => '',
//                                'name'      => '',
//                            ],
//                            'test'  => '',
//                            'timestamp' => time()
//                        ]
                    ]
                ],
                'notifications' => [
                    'nums'      => 0,
                    'data'      => [
//                        [
//                            'id'        => 1,
//                            'icon'      => '',
//                            'text'      => ''
//                        ]
                    ]
                ],
                'tasks'         => [
                    'nums'      => 0,
                    'data'      => [
//                        [
//                            'id'        => 1,
//                            'name'      => '',
//                            'percent'   => 20
//                        ]
                    ]
                ]
            ]);
        } else {
            return guxy_json_message([
                'is_login'      => false,
                'uid'           => 0
            ]);
        }
    }
}