<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/19
 * Time: 下午2:35
 */

namespace App\Http\Controllers;


use Illuminate\Support\Str;

class VueController extends Controller
{
    public function appPage()
    {
        $uri = \Route::current()->uri();

        if (substr_count($uri, '/') > 1) {
            abort(404);
        }

        $page = basename($uri);
        $package = substr($uri, 0, - strlen($page));

        if (!$package) {
            $package = $page;
            $page = 'index';
        }

        $package = preg_replace('@/$@', '', $package);

        $module = Str::snake(Str::camel($package));
        $template_path = module_resource_path($module, 'views/app.blade.php');
        if (!is_file($template_path)) {
            abort(404);
        }

        return view("FT.$module::app", compact('page', 'package'));
    }
}
