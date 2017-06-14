<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/10
 * Time: 下午5:45
 */

namespace Guxy\Common;


trait ExEnum
{
    protected static $name_mapping;
    protected static $display_mapping;

    public static function lists($custom_show = '', $custom_key = '')
    {
        static::prepareMapping();

        if ($custom_show) {
            $ret = [strval($custom_key) => $custom_show];
            foreach (static::$display_mapping as $k => $v) {
                $ret[strval($k)] = $v;
            }
            return $ret;
        } else {
            return static::$display_mapping;
        }
    }

    public static function listKeys()
    {
        static::prepareMapping();
        return array_keys(static::$name_mapping);
    }

    public static function valid($value)
    {
        static::prepareMapping();
        return array_key_exists($value, static::$name_mapping);
    }

    public static function validName($name)
    {
        static::prepareMapping();
        return in_array($name, static::$name_mapping);
    }

    public static function display($value)
    {
        static::prepareMapping();
        return static::$display_mapping[$value];
    }

    public static function displayName($value)
    {
        static::prepareMapping();
        return static::$name_mapping[$value];
    }

    public static function fromName($name)
    {
        static::prepareMapping();
        return array_search($name, static::$name_mapping);
    }

    private static function prepareMapping()
    {
        if (!static::$name_mapping) {
            $rfl = new \ReflectionClass(static::class);

            $reader = app('guxy.enum_annotation_reader');
            $arr = $reader->getClassAnnotations($rfl);


            foreach ($arr as $item) {
                static::$name_mapping[strval($rfl->getConstant($item->name))] = $item->name;
                static::$display_mapping[strval($rfl->getConstant($item->name))] = $item->desc;
            }
        }
    }
}