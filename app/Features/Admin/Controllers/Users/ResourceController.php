<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 上午9:50
 */

namespace App\Features\Admin\Controllers\Users;


use App\Features\Admin\Requests\Users\CreationRequest;
use App\Features\Admin\Requests\Users\UpdationRequest;
use App\Http\Controllers\Controller;
use App\Model\Enums\UserRole;
use App\User;
use Guxy\Common\Exceptions\AppException;
use Guxy\Common\Database\Limiter;
use Guxy\Common\Requests\DefaultDestroyRequest;
use Guxy\Common\Requests\DefaultSearchRequest;
use Guxy\Common\Requests\MultiUpdateSubrouting;

class ResourceController extends Controller
{
    use MultiUpdateSubrouting;

    public function index(DefaultSearchRequest $request)
    {
        //获取查询字段
        $params = guxy_defaults([
            'page'      => 1,
            'size'      => 10,
            'show_all'  => false,
            'orderby'   => ['id desc']
        ], $request->validated());

        $limiters = [];
        $joiners = [];

        //拼接查询关键字
        if (isset($params['keyword'])) {
            $keyword = "%{$params['keyword']}%";
            $limiters[] =
                Limiter::make(function(\Illuminate\Database\Eloquent\Builder $q) use($keyword) {
                    $q->where('name', 'like', $keyword);
                    $q->orWhere('email', 'like', $keyword);
                }, Limiter::CALLBACK);
        }

        //获取分页数据
        $pager = User::repository()->getPager($limiters, [
            'page'      => $params['page'],
            'size'      => $params['size'],
            'orderby'   => $params['orderby'],
            'joins'     => $joiners
        ]);

        $pager->_extras = [
            'roles' => UserRole::lists()
        ];

        return guxy_json_message($pager, 0);
    }

    public function store(CreationRequest $request)
    {
        $data = $request->validationData();

        $user = new User();
        $user->fill(guxy_mask($data, ['name', 'email', 'role']));
        $user->password = bcrypt($data['password']);

        \DB::transaction(function() use ($user) {
            $user->save();
        });

        return guxy_json_message('ok');
    }

    public function update(UpdationRequest $request)
    {
        return $this->dispatchUpdate($request);
    }

    public function destroy(DefaultDestroyRequest $request)
    {
        $qb = User::repository()->getQueryBuilderById($request->validated('ids'));

        \DB::transaction(function() use ($qb) {
            $qb->delete();
        });

        return guxy_json_message('ok');
    }


    public function singleUpdate(UpdationRequest $request)
    {
        $data = $request->validationData();
        if (@$data['password']) {
            $data['password'] = bcrypt($data['password']);
        }


        $user = User::repository()->findOneById($request->id);

        if (!$user) {
            throw new AppException('用户不存在', 1);
        }

        $user->fill($data);

        \DB::transaction(function() use ($user) {
            $user->save();
        });

        return guxy_json_message('ok');
    }


}