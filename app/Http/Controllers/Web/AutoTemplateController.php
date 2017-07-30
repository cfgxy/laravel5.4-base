<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/7/28
 * Time: 下午2:57
 */

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Guxy\Common\ClosureResponse;

class AutoTemplateController extends Controller
{
    public function templatePage()
    {
        $user = \Auth::user();

        $route = \Route::getCurrentRoute();
        $filename = @$route->action['__auto_template'];
        $base = @$route->action['__auto_template_base'];

        if (!$filename) {
            abort(404);
        }


        $filename = realpath($filename);

        //目录安全性检查
        if (strpos($filename, realpath(public_path('web'))) !== 0) {
            abort(404);
        }

        //扩展名检查
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'html') {
            abort(404);
        }



        $content = file_get_contents($filename);
        $content = str_replace_first('<head>', "<head>\n    <base href=\"/web$base\"/>", $content);

        return response($content);
    }
}
