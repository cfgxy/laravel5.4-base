<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/5
 * Time: 下午7:50
 */

namespace Guxy\Common\Console;


use Illuminate\Console\Command;

class MakeModuleCommand extends Command
{
    protected $signature = '
        guxy:make:module {name : 模块名(小写)}
            {--f|full : 加此参数生成完全目录}
    ';

    protected $description = '创建一个Feature模块';

    public function handle()
    {
        $name = $this->argument('name');
        $name = preg_replace('@\W@', '', $name);
        $name = strtolower($name);
        $uname = ucfirst($name);
        $is_full = $this->option('full');

        $base = app_path('Features/' . $uname);
        if (is_dir($base)) {
            return $this->error("目录已存在，不能重复创建: {$base}");
        }

        mkdir("$base/Controllers", 0755, true);
        touch("$base/Controllers/.gitignore");

        mkdir("$base/Model", 0755, true);
        touch("$base/Model/.gitignore");

        mkdir("$base/migrations", 0755, true);
        touch("$base/migrations/.gitignore");

        mkdir("$base/resources/views", 0755, true);
        touch("$base/resources/views/.gitignore");

        mkdir("$base/resources/assets/js", 0755, true);
        touch("$base/resources/assets/js/.gitignore");

        mkdir("$base/resources/assets/images", 0755, true);
        touch("$base/resources/assets/images/.gitignore");

        mkdir("$base/resources/assets/sass", 0755, true);
        touch("$base/resources/assets/sass/.gitignore");

        mkdir("$base/routes", 0755, true);
        file_put_contents("$base/routes/web.php", <<<eot
<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


eot
        );

        if ($is_full) {
            file_put_contents("$base/config.php", <<<eot
<?php
/*
|--------------------------------------------------------------------------
| Module configs; 
|   Config namespace: FT.{$name}
|   Blade namespace:  FT.{$name}::
|--------------------------------------------------------------------------
*/

return [];

eot
            );

            mkdir("$base/Middleware", 0755, true);
            touch("$base/Middleware/.gitignore");

            mkdir("$base/Exceptions", 0755, true);
            touch("$base/Exceptions/.gitignore");

            mkdir("$base/Providers", 0755, true);
            touch("$base/Providers/.gitignore");

            mkdir("$base/Console", 0755, true);
            touch("$base/Console/.gitignore");

            file_put_contents("$base/routes/ajax.php", <<<eot
<?php
/*
|--------------------------------------------------------------------------
| ajax Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


eot
            );

            file_put_contents("$base/routes/api.php", <<<eot
<?php
/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


eot
            );

            file_put_contents("$base/routes/console.php", <<<eot
<?php
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/


eot
            );


            file_put_contents("$base/helpers.php", <<<eot
<?php
/*
|--------------------------------------------------------------------------
| Define your own helper functions here.
| Attention: function defined in this file will be globally
|--------------------------------------------------------------------------
*/
return [];

eot
            );

        }

        return $this;
    }
}
