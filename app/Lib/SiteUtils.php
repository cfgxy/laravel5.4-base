<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 17/7/27
 * Time: 下午2:56
 */

namespace App\Lib;


use App\Http\Controllers\Web\AutoTemplateController;

class SiteUtils
{


    public static function exportTemplateRoutes($path, $base_root = '')
    {
        $path = public_path($path);
        $rootlen = strlen($path);

        ob_start();
        passthru("find $path -type f -name '[^_]*.html'");
        $ret = ob_get_clean();
        $ret = array_filter(explode(PHP_EOL, $ret));


        foreach ($ret as $template) {
            $uri = substr($template, $rootlen, -5);
            $base = dirname($uri);
            $uri = str_replace_last('/index', '', $uri);

            $file_content = file_get_contents($template, false, null, 0, 1024);
            if (preg_match('@[<]meta *name="app-perms" *content="([^"]+)" */[>]@i', $file_content, $m)) {

                $route = \Route::get("$base_root$uri", [
                    'uses' => '\\' . AutoTemplateController::class . '@templatePage',
                    '__auto_template' => $template,
                    '__auto_template_base' => $base,
                ]);

                if ($m[1] != 'guest') {
                    $route->middleware(... explode(';', $m[1]));
                }
            }

        }
    }
}
