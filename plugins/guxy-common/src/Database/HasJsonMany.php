<?php


namespace Guxy\Common\Database;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasJsonMany extends HasMany
{

    public function addConstraints()
    {
        if (static::$constraints) {
            $this->query->whereIn($this->foreignKey, $this->getParentKey());
        }
    }

    public function addEagerConstraints(array $models)
    {
        $this->query->whereIn($this->foreignKey, $this->getKeys($models, $this->localKey));
    }

    public function match(array $models, Collection $results, $relation)
    {
        $dictionary = [];
        $foreign = $this->getPlainForeignKey();
        foreach ($results as $result) {
            $dictionary[$result->{$foreign}] = $result;
        }

        foreach ($models as $model) {
            $key = $model->getAttribute($this->localKey);
            if (!is_array($key)) {
                $key = (array)json_decode($key, true) ?: [];
            }

            $list = [];
            foreach ($key as $id) {
                if (isset($dictionary[$id])) {
                    $list[] = $dictionary[$id];
                }
            }

            $model->setRelation($relation, $this->related->newCollection($list));
        }

        return $models;
    }


    /**
     * Get all of the primary keys for an array of models.
     *
     * @param  array   $models
     * @param  string  $key
     * @return array
     */
    protected function getKeys(array $models, $key = null)
    {
        $arr = array_unique(array_values(@call_user_func_array("array_merge", array_map(function ($value) use ($key) {
            $arr = $key ? $value->getAttribute($key) : $value->getKey();
            if (!is_array($arr)) {
                $arr = (array)json_decode($arr, true) ?: [];
            }
            return $arr;
        }, $models)) ?: []));
        return $arr;
    }


    /**
     * Get the key value of the parent's local key.
     *
     * @return mixed
     */
    public function getParentKey()
    {
        $arr = $this->parent->getAttribute($this->localKey);
        if (!is_array($arr)) {
            $arr = (array)json_decode($arr, true) ?: [];
        }
        $arr = array_values(array_unique($arr));
        return $arr;
    }

}