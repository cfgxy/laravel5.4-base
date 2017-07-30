<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/23
 * Time: 下午12:27
 */

namespace App\Features\Admin\Controllers\Users;


use App\Features\Admin\Requests\Users\UpdationRequest;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Guxy\Common\Exceptions\AppException;

class MyController extends Controller
{
    public function profile()
    {
        /** @var User $user */
        $user = \Auth::guard()->user();

        return guxy_json_message([
            'name'      => $user->name,
            'email'     => $user->email,
            'has_avatar'=> $user->has_avatar,
            'avatar'    => $user->avatar,
            'avatar_ver'=> $user->avatar_ver
        ]);
    }

    public function updateProfile(UpdationRequest $request)
    {
        $data = $request->validationData();
        if (@$data['password']) {
            $data['password'] = bcrypt($data['password']);
        }


        $user = User::repository()->findOneById(\Auth::guard()->id());

        if (!$user) {
            throw new AppException('用户不存在', 1);
        }

        $user->fill($data);

        $avatar = \Session::remove('user.pending_avatar');
        if (@$data['avatar_ver'] && $avatar) {
            $dir = dirname($user->avatar_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($user->avatar_path, base64_decode($avatar));
        }

        \DB::transaction(function() use ($user) {
            $user->save();
        });

        return guxy_json_message('ok');

    }

    public function uploadAvatar(Request $request)
    {
        $uuid = $request->input('qquuid');
        $file = $request->file('qqfile');

        \Session::put('user.pending_avatar', base64_encode(file_get_contents($file->getRealPath())));
        unlink($file->getRealPath());

        return response()->json(["success" => true, "uuid" => $uuid]);
    }

}
