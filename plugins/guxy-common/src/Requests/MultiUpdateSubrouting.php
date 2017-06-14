<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/30
 * Time: 下午3:25
 */

namespace Guxy\Common\Requests;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * 批量操作的类
 * Class MultiUpdateSubrouting
 * @package App\Http
 */
trait MultiUpdateSubrouting
{

    /**
     * 修改操作的入口方法
     * @param Request $req
     * @return mixed
     */
    public function dispatchUpdate(Request $req)
    {
        if ($req->multiAction) {
            $method = 'multiUpdate' . ucfirst(Str::camel($req->multiAction));
            return $this->$method($req);
        } else {
            $method = 'singleUpdate';
            return $this->$method($req);
        }
    }
}