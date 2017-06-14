<?php


namespace Guxy\Common {

    use Doctrine\Common\Annotations\SimpleAnnotationReader;
    use Illuminate\Support\ServiceProvider as LaravelProvider;
    use Guxy\Common\Console\MakeModuleCommand;
    use Guxy\Common\Console\RepositoriesCommand;
    use Guxy\Common\Annotations\enum;

    class ServiceProvider extends LaravelProvider
    {
        public function boot()
        {

            $configPath = __DIR__ . '/../config.php';
            $publishPath = config_path('guxy.php');
            $this->publishes([$configPath => $publishPath], 'config');

            //触发 autoload
            class_exists(enum::class);


            // ExModel 辅助开发生成工具
            if (config('guxy.use_ide_helper')) {
                $this->commands([
                    RepositoriesCommand::class
                ]);
            }

            // 模块化拆分
            if (config('guxy.module_support')) {
                $this->commands([
                    MakeModuleCommand::class
                ]);

                $configs = glob(app_path('Features/*/config.php'));
                $resources = glob(app_path('Features/*/resources'));
                $migrations = glob(app_path('Features/*/migrations'));

                //配置文件
                foreach ($configs as $config) {
                    preg_match('@/Features/([^/]+)/config[.]php$@', $config, $m);
                    $this->mergeConfigFrom($config, "FT." . strtolower($m[1]));
                }

                //
                foreach ($resources as $config) {
                    preg_match('@/Features/([^/]+)/resources$@', $config, $m);

                    if (is_dir("$config/views")) {
                        $this->loadViewsFrom("$config/views", "FT." . strtolower($m[1]));
                    }
                }

                if ($migrations) {
                    $this->loadMigrationsFrom($migrations);
                }


                $routes = glob(app_path('Features/*/routes'));
                foreach ($routes as $config) {
                    preg_match('@/Features/([^/]+)/routes$@', $config, $m);

                    $dir = dir($config);
                    while ($file = $dir->read()) {
                        if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
                            $group = basename($file, '.php');
                            \Route::middleware($group)
                                ->namespace("App\\Features\\{$m[1]}\\Controllers")
                                ->group("$config/$file");
                        }
                    }
                }
            }

        }

        public function register()
        {
            $this->app->singleton('guxy.enum_annotation_reader', function () {
                $reader = new SimpleAnnotationReader();
                $reader->addNamespace("Guxy\Common\Annotations");
                return $reader;
            });
        }
    }


}

namespace {

    // 加载helpers
    if (config('guxy.helpers_support')) {
        require_once __DIR__ . '/../helpers.php';
        require_once app_path('helpers.php');

        // 模块化支持
        if (config('guxy.module_support')) {
            $helpers = glob(app_path('Features/*/helpers.php'));
            foreach ($helpers as $filename) {
                require_once $filename;
            }
        }
    }
}
