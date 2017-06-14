<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/5
 * Time: 下午8:53
 */


if (!function_exists('guxy_pager_wrapper')) {
    function guxy_pager_wrapper($results)
    {
        $len = count($results);

        if ($len) {
            return new \Illuminate\Pagination\LengthAwarePaginator($results, $len, $len, 1);
        } else {
            return new \Illuminate\Pagination\LengthAwarePaginator([], $len, $len, 0);
        }
    }
}


if (!function_exists('guxy_pager_split'))
{
    function guxy_pager_split($results, $size, $page)
    {
        $total = count($results);

        $pager = new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $size, $page);
        $pager->setCollection(collect(array_slice($results, $pager->firstItem() - 1, $size)));
        return $pager;
    }
}


/**
 * 编码json数据
 */
if (!function_exists('guxy_json_decode')) {
    function guxy_json_decode($json, $assoc = true, $depth = 512, $options = 0)
    {
        return json_decode($json, $assoc, $depth, $options);
    }
}

/**
 * 解码json数据
 */
if (!function_exists('guxy_json_encode')) {
    function guxy_json_encode($data, $options = 0, $depth = 512)
    {
        return json_encode($data, $options | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, $depth);
    }
}

/**
 * 返回格式化的json数据
 */
if (!function_exists('guxy_json_message')) {
    /**
     * @param $data
     * @param int $code
     * @param null $dataParser
     * @return \Illuminate\Http\Response
     */
    function guxy_json_message($data, $code = 0, $dataParser = null)
    {
        if ($code && is_string($data)) {
            $ret = ['code'  => (int)$code, 'msg' => $data];
        } else {
            if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $pager = $data;
                $dataParser = $dataParser ?: @$pager->_dataParser;

                $items = $pager->getCollection();
                if (is_callable($dataParser)) {
                    $newItems = [];

                    foreach ($items as $item) {
                        $newItems[] = call_user_func($dataParser, $item);
                    }

                    $items = $newItems;

                    $items = array_values(array_filter($items, function ($item) {
                        return $item !== null;
                    }));
                }

                $data = array(
                    'page' => (int)($pager->currentPage() ?: 1),
                    'perpage' => (int)$pager->perPage(),
                    'max_page' => (int)$pager->lastPage(),
                    'has_more' => (bool)$pager->hasMorePages(),
                    'total' => (int)$pager->total(),
                    'data' => $items,
                    'extras' => (object)(@$pager->_extras ?: array())
                );
            } elseif (is_array($data) || $data instanceof \Traversable) {
                if (is_callable($dataParser)) {
                    $newItems = [];

                    foreach ($data as $item) {
                        $newItems[] = call_user_func($dataParser, $item);
                    }

                    $data = $newItems;

                    $data = array_values(array_filter($data, function ($item) {
                        return $item !== null;
                    }));
                }
            } elseif ($data instanceof \Illuminate\Database\Eloquent\Model && is_callable(@$dataParser)) {
                $data = call_user_func($dataParser, $data);
            }

            if ($code && @$data['msg']) {
                $ret = ['code'  => (int)$code, 'msg' => $data['msg'], 'data' => $data];
            } else {
                $ret = ['code'  => (int)$code, 'data' => $data];
            }
        }

        if (!array_diff(array_keys($ret), ['code', 'data']) && is_string($ret['data'])) {
            $ret['msg'] = $ret['data'];
        }

        return response(guxy_json_encode($ret, JSON_PRETTY_PRINT))->header('Content-Type', 'application/json');
    }
}


/**
 * 返回module路径
 */
if (!function_exists('module_path')) {
    function module_path($module, $path)
    {
        $module = ucfirst(\Illuminate\Support\Str::camel($module));
        return app_path("Features/$module/$path");
    }
}

/**
 * 返回module路径
 */
if (!function_exists('module_resource_path')) {
    function module_resource_path($module, $path)
    {
        $module = ucfirst(\Illuminate\Support\Str::camel($module));
        return app_path("Features/$module/resources/$path");
    }
}

if (!function_exists('guxy_mask')) {
    function guxy_mask($arr, array $keys)
    {
        $newArr = [];

        foreach ($arr as $k => $v) {
            if (in_array($k, $keys)) {
                $newArr[$k] = $v;
            }
        }

        return $newArr;
    }
}


/**
 * 功能同 dd，但不退出程序
 */
if (!function_exists('wd')) {
    function wd()
    {
        array_map(function ($x) {
            (new \Illuminate\Support\Debug\Dumper())->dump($x);
        }, func_get_args());
    }
}

/**
 * 返回表单验证类的路径
 */
if (!function_exists('include_module_validators')) {
    function include_module_validators($module, $submodule = '')
    {
        $package = $submodule;
        $moduleName = ucfirst(\Illuminate\Support\Str::camel($module));
        $submodule = ucfirst(\Illuminate\Support\Str::camel($submodule));

        if ($submodule) {
            $path = "Requests/$submodule/*Request.php";
            $namespace = "\\App\\Features\\{$moduleName}\\Requests\\{$submodule}\\";
        } else {
            $path = 'Requests/*Request.php';
            $namespace = "\\App\\Features\\{$moduleName}\\Requests\\";
        }

        $js = [];
        foreach(glob(module_path($module, $path)) as $file) {
            $classname = $namespace . basename($file, '.php');
            $formname = str_replace('_', '-', \Illuminate\Support\Str::snake(basename(basename($file, '.php'), 'Request')));
            if ($package) {
                $formname = "$package.$formname";
            }

            $js[] = \JsValidator::formRequest($classname, $formname);
        }

        $js = implode(",\n", $js);

        return "<script type=\"text/javascript\"> var formValidators = { $js };</script>";
    }
}

if (!function_exists('guxy_defaults')) {
    function guxy_defaults(array $default, array... $arrs)
    {
        array_unshift($arrs, $default);
        return call_user_func_array('array_merge', $arrs);
    }
}


if (!function_exists('guxy_clean_text')) {
    function guxy_clean_text($text)
    {
        $text = preg_replace('#<br\s*/?>#i', "\n", $text);
        $text = preg_replace('#[\cM]#', '', $text);
        $text = preg_replace('#\n+#', "\n", $text);
        $text = preg_replace('#<p[^/]*>#i', "\n\n", $text);
        $text = trim(strip_tags($text));
        return trim($text);
    }
}



if (!function_exists('guxy_encrypt_id')) {
    function guxy_encrypt($id, $strong = false, $key = 'lucky')
    {
        if ($strong) {
            $text = $id;
            $method = 'aes-128-cbc';
            $iv = str_pad('', openssl_cipher_iv_length($method), "\0");
            $options = 0;
        } else {
            $text = str_pad($id, (strlen($id) + 8) - (strlen($id) % 8), "\0");
            $method = 'des-ecb';
            $iv = '';
            $options = OPENSSL_ZERO_PADDING;
        }

        $cipher = openssl_encrypt($text, $method, $key, $options, $iv);
        $cipher = base64_decode($cipher);
        $cipher = strtoupper(bin2hex($cipher));

        return $cipher;
    }
}


if (!function_exists('guxy_decrypt_id')) {
    function guxy_decrypt($cipher, $strong = false, $key = 'lucky')
    {
        $cipher = @hex2bin($cipher);
        $cipher = base64_encode($cipher);

        if ($strong) {
            $method = 'aes-128-cbc';
            $iv = str_pad('', openssl_cipher_iv_length($method), "\0");
            $options = 0;
        } else {
            $method = 'des-ecb';
            $iv = '';
            $options = OPENSSL_ZERO_PADDING;
        }

        $text = openssl_decrypt($cipher, $method, $key, $options, $iv);

        if (!$strong) {
            $text = trim($text, "\0");
        }

        return $text;
    }
}


if (!function_exists('guxy_make_visible')) {
    /**
     * @param Illuminate\Database\Eloquent\Model|Illuminate\Database\Eloquent\Model[]|\Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator $target The target
     * @param string[] $keys temp visible keys
     */
    function guxy_make_visible($target, $keys, $clean = false)
    {
        $collection = null;

        if ($target instanceof \Illuminate\Contracts\Pagination\Paginator) {
            if ($target instanceof \Illuminate\Pagination\AbstractPaginator) {
                $collection = $target->getCollection();
            } else {
                $collection = collect($target->items());
            }
        } elseif (is_array($target) && count($target) && $target[0] instanceof \Illuminate\Database\Eloquent\Model) {
            $collection = collect($target);
        } elseif ($target instanceof \Illuminate\Support\Collection) {
            $collection = $target;
        } elseif ($target instanceof \Illuminate\Database\Eloquent\Model) {
            $collection = collect([$target]);
        }

        if (!$collection || !($collection->first() instanceof \Illuminate\Database\Eloquent\Model)) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $collection->first();
        $fields = array_keys($model->getAttributes());
        $relations = array_keys($model->getRelations());

        if ($clean) {
            $collection->each->setAppends([]);
            $collection->each->setVisible([$model->getKeyName()]);
        }
        $collection->each->makeVisible($keys);
        $collection->each->append(array_diff($keys, $fields, $relations));
    }
}


