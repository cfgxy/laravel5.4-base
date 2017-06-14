<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/15
 * Time: 下午9:40
 */

namespace Guxy\Common\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

/**
 * 数据提交字段的验证，添加验证规则类时继承此类
 * Class ActionRequest
 * @package App\Http
 */
abstract class ActionRequest extends FormRequest
{
    const REMOVE_THIS_FIELD_TAG = '{!#--REMOVE_THIS_FIELD(5314bc0bd2be274bfbd4754d3bb957e1)--#!}';

    /**
     * The sanitized input.
     *
     * @var array
     */
    public $sanitized;

    protected $is_sub_request = false;
    protected $extras = [];

    /**
     * 验证失败时JSON输出错误
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors)
    {
        $msg = array_values($errors)[0][0];
        return guxy_json_message(['msg' => $msg, 'errors' => $errors], 422);
    }

    /**
     * 认证失败时JSON输出错误
     * @return mixed
     */
    public function forbiddenResponse()
    {
        return guxy_json_message('没有操作权限', 401);
    }

    /**
     * 认证逻辑，可重载
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 数组化验证规则，需实现
     * @return mixed
     */
    public abstract function rules();

    /**
     * Code化验证逻辑，可重载
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
    }

    /**
     * 获取验证数据
     * @param $key
     * @param $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        if ($key === null) {
            $data = $this->validationData();
            unset($data['_method']);
            return $data;
        }

        return data_get($this->validationData(), $key, $default);
    }

    /**
     * 获取验证数据
     * @return array
     */
    public function validationData()
    {
        if ($this->sanitized !== null) {
            return $this->sanitized;
        }

        return $this->sanitized = $this->sanitize();
    }

    /**
     * 输入内容整理，可重载
     * @return array  返回待验证数组，将来会被validationData返回
     */
    public function sanitize()
    {
        return $this->all();
    }

    /**
     * 内容整理的模板方法，最终方法的调用方式:
     *   XXX()                                  处理所有输入字段
     *   XXX(string $key)                       处理所有类型输入中的$key字段
     *   XXX(string[] $keys)                    同上$keys是个数组
     *   XXX(\IteratorAggregate $source)        处理$source内的输入字段, $source可以为 attributes/request/query/cookies/headers/json 之一
     *   XXX(\IteratorAggregate[] $sources)     同上，$sources是个数组
     *
     *   可同时传入 $key 和 $source 两个字段
     *
     * @param $callback callable 值整理逻辑
     * @param $params array 参数传递
     */
    private function sanitizeBulkCall($callback, $params)
    {
        /* @var \Symfony\Component\HttpFoundation\HeaderBag $source */

        $sources = [$this->attributes, $this->request, $this->query, $this->cookies, $this->headers, $this->json];
        $sources = array_filter($sources, function($source) {
            return $source !== null && $source instanceof \IteratorAggregate;
        });

        switch (true) {
            case count($params) === 0:
                foreach ($sources as $source) {
                    foreach ($source as $k => &$v) {
                        $nv = call_user_func_array($callback, [&$v, $k]);
                        if ($nv === static::REMOVE_THIS_FIELD_TAG) {
                            $source->remove($k);
                        } else {
                            $source->set($k, $nv);
                        }
                    }
                    unset($v);
                }
                break;
            case count($params) === 1:
                if (is_object($params[0]) && in_array($params[0], $sources)) {
                    foreach ($params[0] as $k => &$v) {
                        $nv = call_user_func_array($callback, [&$v, $k]);
                        if ($nv === static::REMOVE_THIS_FIELD_TAG) {
                            $params[0]->remove($k);
                        } else {
                            $params[0]->set($k, $nv);
                        }
                    }
                    unset($v);
                } else {
                    return $this->sanitizeBulkCall($callback, [$sources, $params[0]]);
                }

                break;
            case count($params) === 2:
                $items = [];
                if (is_object($params[0]) && in_array($params[0], $sources)) {
                    $items[] = $params[0];
                } elseif (is_array($params[0])) {
                    foreach ($params[0] as $source) {
                        if (in_array($source, $sources)) {
                            $items[] = $source;
                        }
                    }
                }

                if (is_array($params[1])) {
                    $clean_keys = preg_replace('@\[\]$@', '', $params[1]);

                    foreach ($items as $source) {
                        foreach ($source as $k => &$v) {
                            if (!in_array($k, $clean_keys)) {
                                continue;
                            }

                            $is_arr = !in_array($k, $params[1]);
                            if ($is_arr && !is_array($v)) {
                                $v = [$v];
                            }

                            if ($is_arr) {
                                $nv = [];
                                foreach ($v as &$vi) {
                                    $nv[] = call_user_func_array($callback, [&$vi, $k]);
                                }
                                $source->set($k, $nv);
                            } else {
                                $nv = call_user_func_array($callback, [&$v, $k]);
                                if ($nv === static::REMOVE_THIS_FIELD_TAG) {
                                    $source->remove($k);
                                } else {
                                    $source->set($k, $nv);
                                }
                            }

                        }
                    }
                } else {
                    $clean_key = preg_replace('@\[\]$@', '', $params[1]);

                    foreach ($items as $source) {
                        foreach ($source as $k => &$v) {
                            if ($k !== $clean_key) {
                                continue;
                            }

                            $is_arr = $k !== $params[1];

                            if ($is_arr) {
                                $nv = [];
                                foreach ($v as &$vi) {
                                    $nv[] = call_user_func_array($callback, [&$vi, $k]);
                                }

                                $source->set($k, $nv);
                            } else {
                                $nv = call_user_func_array($callback, [&$v, $k]);
                                if ($nv === static::REMOVE_THIS_FIELD_TAG) {
                                    $source->remove($k);
                                } else {
                                    $source->set($k, $nv);
                                }
                            }
                        }
                    }
                }
                break;
        }
    }

    /**
     * 过滤前后空格
     *
     * @method void trim(string $key)
     * @method void trim(string[] $keys)
     * @method void trim(\IteratorAggregate $source)
     * @method void trim(\IteratorAggregate[] $sources)
     * @method void trim(\IteratorAggregate $source, string $key)
     * @method void trim(\IteratorAggregate $source, string[] $keys)
     * @method void trim(\IteratorAggregate[] $sources, string $key)
     * @method void trim(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function trim()
    {
        $this->sanitizeBulkCall(function(&$v) {
            if (is_string($v)) {
                return trim($v);
            }

            return $v;
        }, func_get_args());
    }

    /**
     * 删除内容为空的参数
     *
     * @method void removeEmpty(string $key)
     * @method void removeEmpty(string[] $keys)
     * @method void removeEmpty(\IteratorAggregate $source)
     * @method void removeEmpty(\IteratorAggregate[] $sources)
     * @method void removeEmpty(\IteratorAggregate $source, string $key)
     * @method void removeEmpty(\IteratorAggregate $source, string[] $keys)
     * @method void removeEmpty(\IteratorAggregate[] $sources, string $key)
     * @method void removeEmpty(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function removeEmpty()
    {
        $this->sanitizeBulkCall(function(&$v) {
            if (empty($v)) {
                return static::REMOVE_THIS_FIELD_TAG;
            }

            return $v;
        }, func_get_args());
    }

    /**
     * 强转为整型数
     *
     * @method void numberInt(string $key)
     * @method void numberInt(string[] $keys)
     * @method void numberInt(\IteratorAggregate $source)
     * @method void numberInt(\IteratorAggregate[] $sources)
     * @method void numberInt(\IteratorAggregate $source, string $key)
     * @method void numberInt(\IteratorAggregate $source, string[] $keys)
     * @method void numberInt(\IteratorAggregate[] $sources, string $key)
     * @method void numberInt(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function numberInt()
    {
        $this->sanitizeBulkCall(function(&$v) {
            if (is_int($v)) {
                return $v;
            }

            if (is_string($v)) {
                if (trim(strtolower($v)) === 'true') {
                    return 1;
                } elseif (trim(strtolower($v)) === 'false') {
                    return 0;
                }
            }

            return (int)$v;
        }, func_get_args());
    }

    /**
     * 强转为浮点数
     *
     * @method void numberFloat(string $key)
     * @method void numberFloat(string[] $keys)
     * @method void numberFloat(\IteratorAggregate $source)
     * @method void numberFloat(\IteratorAggregate[] $sources)
     * @method void numberFloat(\IteratorAggregate $source, string $key)
     * @method void numberFloat(\IteratorAggregate $source, string[] $keys)
     * @method void numberFloat(\IteratorAggregate[] $sources, string $key)
     * @method void numberFloat(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function numberFloat()
    {
        $this->sanitizeBulkCall(function(&$v) {
            if (is_float($v)) {
                return $v;
            }

            if (is_string($v)) {
                if (trim(strtolower($v)) === 'true') {
                    return 1.0;
                } elseif (trim(strtolower($v)) === 'false') {
                    return 0.0;
                }
            }

            return (float)$v;
        }, func_get_args());
    }

    /**
     * 强转为布尔值
     *
     * @method void numberBoolean(string $key)
     * @method void numberBoolean(string[] $keys)
     * @method void numberBoolean(\IteratorAggregate $source)
     * @method void numberBoolean(\IteratorAggregate[] $sources)
     * @method void numberBoolean(\IteratorAggregate $source, string $key)
     * @method void numberBoolean(\IteratorAggregate $source, string[] $keys)
     * @method void numberBoolean(\IteratorAggregate[] $sources, string $key)
     * @method void numberBoolean(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function numberBoolean()
    {
        $this->sanitizeBulkCall(function(&$v) {
            if (is_bool($v)) {
                return $v;
            }

            if (is_string($v)) {
                if (in_array(trim(strtolower($v)), ['true', 'on', 'enabled', 'yes'])) {
                    return true;
                } elseif (in_array(trim(strtolower($v)), ['false', 'off', 'disabled', 'no'])) {
                    return false;
                }
            }

            return (bool)$v;
        }, func_get_args());
    }

    /**
     * 作为纯文本过滤(滤除所有tag内容)
     *
     * TODO: 考虑特殊字符
     *
     * @method void text(string $key)
     * @method void text(string[] $keys)
     * @method void text(\IteratorAggregate $source)
     * @method void text(\IteratorAggregate[] $sources)
     * @method void text(\IteratorAggregate $source, string $key)
     * @method void text(\IteratorAggregate $source, string[] $keys)
     * @method void text(\IteratorAggregate[] $sources, string $key)
     * @method void text(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function text()
    {
        $this->sanitizeBulkCall(function(&$v) {
            return guxy_clean_text((string)$v);
        }, func_get_args());
    }

    /**
     * 作为HTML过滤(滤除所有XSS内容)，通过 config/purifier.php 配置
     *
     * TODO: 考虑特殊字符
     *
     * @method void html(string $key)
     * @method void html(string[] $keys)
     * @method void html(\IteratorAggregate $source)
     * @method void html(\IteratorAggregate[] $sources)
     * @method void html(\IteratorAggregate $source, string $key)
     * @method void html(\IteratorAggregate $source, string[] $keys)
     * @method void html(\IteratorAggregate[] $sources, string $key)
     * @method void html(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function html()
    {
        $this->sanitizeBulkCall(function(&$v) {
            return clean((string)$v);
        }, func_get_args());
    }

    /**
     * 过滤敏感词，通过 app/censorwords/*.php 配置过滤字典；
     * 可以拆分不同的字典，在调用前用 guxy_load_censorword_dict 加载字典
     *
     * TODO: 考虑特殊字符
     *
     * @method void censorwords(string $key)
     * @method void censorwords(string[] $keys)
     * @method void censorwords(\IteratorAggregate $source)
     * @method void censorwords(\IteratorAggregate[] $sources)
     * @method void censorwords(\IteratorAggregate $source, string $key)
     * @method void censorwords(\IteratorAggregate $source, string[] $keys)
     * @method void censorwords(\IteratorAggregate[] $sources, string $key)
     * @method void censorwords(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function censorwords()
    {
        $this->sanitizeBulkCall(function(&$v) {
            $ret = app('censorwords')->censorString((string)$v);
            return $ret['clean'];
        }, func_get_args());
    }

    /**
     * 拆分逗号数组
     *
     * TODO: 考虑特殊字符
     *
     * @method void commaArray(string $key)
     * @method void commaArray(string[] $keys)
     * @method void commaArray(\IteratorAggregate $source)
     * @method void commaArray(\IteratorAggregate[] $sources)
     * @method void commaArray(\IteratorAggregate $source, string $key)
     * @method void commaArray(\IteratorAggregate $source, string[] $keys)
     * @method void commaArray(\IteratorAggregate[] $sources, string $key)
     * @method void commaArray(\IteratorAggregate[] $sources, string[] $keys)
     */
    public function commaArray()
    {
        $this->sanitizeBulkCall(function(&$v) {
            if (is_array($v)) {
                return $v;
            }
            return explode(',', $v);
        }, func_get_args());
    }

    public function asSubRequest()
    {
        $this->is_sub_request = true;
        return $this;
    }

    public function isSubRequest()
    {
        return $this->is_sub_request;
    }



    public function putExtra($key, $value)
    {
        $this->extras[$key] = $value;
    }

    public function getExtra($key, $default = null)
    {
        return @$this->extras[$key] ?: $default;
    }
}
