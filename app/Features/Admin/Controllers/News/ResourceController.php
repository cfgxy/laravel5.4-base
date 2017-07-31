<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/22
 * Time: 上午9:50
 */

namespace App\Features\Admin\Controllers\News;


use App\Features\Admin\Requests\News\CreationRequest;
use App\Features\Admin\Requests\News\UpdationRequest;
use App\Http\Controllers\Controller;
use App\Model\News;
use Guxy\Common\Exceptions\AppException;
use Guxy\Common\Database\Limiter;
use Guxy\Common\Requests\DefaultDestroyRequest;
use Guxy\Common\Requests\DefaultSearchRequest;
use Guxy\Common\Requests\MultiUpdateSubrouting;
use Symfony\Component\HttpFoundation\Request;

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
        $pager = News::repository()->getPager($limiters, [
            'page'      => $params['page'],
            'size'      => $params['size'],
            'orderby'   => $params['orderby'],
            'joins'     => $joiners
        ]);

        return guxy_json_message($pager, 0);
    }

    public function show(Request $request)
    {
        $id = $request->route('datum');
        $news = News::repository()->findOneById($id);
        guxy_make_visible($news, ['content']);
        return guxy_json_message($news);
    }

    public function store(CreationRequest $request)
    {
        $data = $request->validationData();

        $model = new News();
        $model->title = $data['title'];
        $model->content = clean($data['content']);

        \DB::transaction(function() use ($model) {
            $model->save();
        });

        return guxy_json_message('ok');
    }

    public function update(UpdationRequest $request)
    {
        return $this->dispatchUpdate($request);
    }

    public function destroy(DefaultDestroyRequest $request)
    {
        $qb = News::repository()->getQueryBuilderById($request->validated('ids'));

        \DB::transaction(function() use ($qb) {
            $qb->delete();
        });

        return guxy_json_message('ok');
    }


    public function singleUpdate(UpdationRequest $request)
    {
        $data = $request->validationData();

        $model = News::repository()->findOneById($request->id);

        if (!$model) {
            throw new AppException('对象不存在', 1);
        }

        $model->title = $data['title'];
        $model->content = clean($data['content']);

        \DB::transaction(function() use ($model) {
            $model->save();
        });

        return guxy_json_message('ok');
    }


}