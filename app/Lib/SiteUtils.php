<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 17/7/27
 * Time: 下午2:56
 */

namespace App\Lib;


use App\Http\Controllers\VueController;
use App\Http\Controllers\Web\AutoTemplateController;
use Illuminate\Support\Str;

class SiteUtils
{


    public static function exportTemplateRoutes($path, $base_root = '')
    {
        $rootlen = strlen($path);

        ob_start();
        passthru("find $path -type f -name '[^_]*.html'");
        $ret = ob_get_clean();
        $ret = array_filter(explode(PHP_EOL, $ret));


        foreach ($ret as $template) {
            $uri = substr($template, $rootlen, -5);
            $base = dirname($uri);
            $uri = str_replace_last('/index', '', $uri);
            $uri = substr($uri, 1);

            if ($base_root) {
                $uri = "$base_root/$uri";
            }

            $uri = preg_replace('@/$@', '', $uri);

            $name = str_replace('_', '-', Str::snake($uri));
            $name = str_replace('/', '.', $name);

            $file_content = file_get_contents($template, false, null, 0, 1024);
            if (preg_match('@[<]meta *name="app-perms" *content="([^"]+)" */[>]@i', $file_content, $m)) {

                $route = \Route::get($uri, [
                    'uses' => '\\' . AutoTemplateController::class . '@templatePage',
                    '__auto_template' => $template,
                    '__auto_template_base' => $base,
                    'as'   => $name
                ]);

                if ($m[1] != 'guest') {
                    $route->middleware(... explode(';', $m[1]));
                }
            }

        }
    }


    public static function exportVueRoutes($path, $base_root = '', $middleware = [])
    {
        $rootlen = strlen($path);

        ob_start();
        passthru("find $path -type f -name '[^_]*.js'");
        $ret = ob_get_clean();
        $ret = array_filter(explode(PHP_EOL, $ret));


        foreach ($ret as $template) {
            $uri = substr($template, $rootlen, -3);
            $uri = str_replace_last('/index', '', $uri);
            $uri = substr($uri, 1);

            if ($base_root) {
                $uri = "$base_root/$uri";
            }

            $uri = preg_replace('@/$@', '', $uri);

            $name = str_replace('_', '-', Str::snake($uri));
            $name = str_replace('/', '.', $name);


            $route = \Route::get($uri, [
                'uses' => '\\' . VueController::class . '@appPage',
                'as'   => $name
            ]);
            $route->middleware(... $middleware);

        }
    }
}
