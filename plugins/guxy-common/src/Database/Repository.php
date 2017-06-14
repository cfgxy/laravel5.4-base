<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/15
 * Time: 上午8:27
 */

namespace Guxy\Common\Database;


use Guxy\Common\Core\QArray;
use Guxy\Common\Features\Auth\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * 执行查询数据的工厂函数
 * Class Repository
 * @package Guxy\Common\Database
 */
abstract class Repository
{


    protected $withoutLandlords = [];

    public abstract function model();

    /**
     * 处理没定义的查询方法
     * @param $method
     * @param $arguments
     * @return Model|int|null|static
     */
    public function __call($method, $arguments)
    {
        /* @var Builder $query */

        switch (true) {

            //Simple querys
            case (0 === strpos($method, 'getPagerBy')):
                $type   = 'simple';
                $expect = 'pager';
                $by = substr($method, 10);
                $method = 'queryBy' . $by;
                break;
            case (0 === strpos($method, 'getQueryBy')):
                $type   = 'simple';
                $expect = 'query';
                $by = substr($method, 10);
                $method = 'queryBy' . $by;
                break;
            case (0 === strpos($method, 'getQueryBuilderBy')):
                $type   = 'simple';
                $expect = 'query_builder';
                $by = substr($method, 17);
                $method = 'queryBy' . $by;
                break;
            case (0 === strpos($method, 'getSQLBy')):
                $type   = 'simple';
                $expect = 'sql';
                $by = substr($method, 8);
                $method = 'queryBy' . $by;
                break;
            case (0 === strpos($method, 'findOneBy')):
                $type   = 'simple';
                $expect = 'one';
                $by = substr($method, 9);
                $method = 'queryBy' . $by;
                break;
            case (0 === strpos($method, 'findBy')):
                $type   = 'simple';
                $expect = 'result';
                $by = substr($method, 6);
                $method = 'queryBy' . $by;
                break;
            case (0 === strpos($method, 'countBy')):
                $type = 'simple';
                $expect = 'count';
                $by = substr($method, 7);
                $method = 'queryBy' . $by;
                break;

            //Named querys
            case (preg_match('#^get(\w*)Pager$#', $method, $m)):
                $type   = 'named';
                $expect = 'pager';
                $by = $m[1];
                $method = 'query' . $by;
                break;
            case (preg_match('#^get(\w*)Query$#', $method, $m)):
                $type   = 'named';
                $expect = 'query';
                $by = $m[1];
                $method = 'query' . $by;
                break;
            case (preg_match('#^get(\w*)QueryBuilder$#', $method, $m)):
                $type   = 'named';
                $expect = 'query_builder';
                $by = $m[1];
                $method = 'query' . $by;
                break;
            case (preg_match('#^get(\w*)SQL#', $method, $m)):
                $type   = 'named';
                $expect = 'sql';
                $by = $m[1];
                $method = 'query' . $by;
                break;
            case (preg_match('#^findOne(\w*)$#', $method, $m)):
                $type   = 'named';
                $expect = 'one';
                $by = $m[1];
                $method = 'query' . $by;
                break;
            case (preg_match('#^find(\w*)$#', $method, $m)):
                $type   = 'named';
                $expect = 'result';
                $by = $m[1];
                $method = 'query' . $by;
                break;
            case (preg_match('#^count(\w*)$#', $method, $m)):
                $type = 'named';
                $expect = 'count';
                $by = $m[1];
                $method = 'query' . $by;
                break;

            // Default querys
            case (preg_match('#^queryBy(\w+)$#', $method, $m)):
                return $this->callQueryBy(lcfirst(\Doctrine\Common\Util\Inflector::tableize($m[1])), $arguments[0], $arguments[1] ?: [], $arguments[2] ?: []);

            default:
                $type = 'unknown';
                break;
        }

        /* @var Builder */
        $query = null;
        $options = [];
        $limiters = [];

        if (method_exists($this, $method) || $type === 'simple') {
            if ($type === 'simple') {
                //Parameter list:
                //  0: Primary field value
                //  1: Criteria
                //  2: Options
                switch (count($arguments)) {
                    case 1:
                        $options = $this->mergeOptions($expect, []);
                        $query = $this->$method($arguments[0], [], $options);
                        break;

                    case 2:
                        $limiters = $arguments[1];
                        $options = $this->mergeOptions($expect, []);
                        $query = $this->$method($arguments[0], $limiters, $options);
                        break;

                    case 3:
                        $limiters = $arguments[1];
                        $options = $this->mergeOptions($expect, $arguments[2] ?: array());
                        $query = $this->$method($arguments[0], $limiters, $options);
                        break;

                    default:
                }
            } elseif ($type === 'named') {
                //Parameter list:
                //  0: Criteria
                //  1: Options
                switch (count($arguments)) {
                    case 0:
                        $options = $this->mergeOptions($expect, []);
                        $query = $this->$method([], $options);
                        break;

                    case 1:
                        $limiters = $arguments[0];
                        $options = $this->mergeOptions($expect, []);
                        $query = $this->$method($limiters, $options);
                        break;

                    case 2:
                        $limiters = $arguments[0];
                        $options = $this->mergeOptions($expect, $arguments[1] ?: array());
                        $query = $this->$method($limiters, $options);
                        break;

                    default:
                }
            }
        }

        if ($query) {
            if (!$query instanceof Builder) {
                throw new \BadMethodCallException(
                    "The method '$method' should return a Doctrine\\Orm\\Query or Doctrine\\Orm\\QueryBuilder, " .
                    "but " . (gettype($query) === 'object' ? get_class($query) : gettype($query)) . " given."
                );
            }


            switch ($expect) {
                case 'sql':
                    $q = $query->toBase();

                    if (@$options['columns']) {
                        $q->select($options['columns']);
                    }

                    if (@$options['sql_type'] === 'statement') {
                        return $q->toSql();
                    } elseif (@$options['sql_type'] === 'array') {
                        return [
                            'sql'       => $q->toSql(),
                            'bindings'  => $q->getBindings()
                        ];
                    } else {
                        $sql = $q->toSql();
                        $bindings = $q->getBindings();

                        /* @var \Illuminate\Database\Connection $conn */
                        $conn = $query->getConnection();
                        $pdo = $conn->getPdo();

                        foreach ($bindings as $key => $binding) {
                            $regex = is_numeric($key)
                                ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
                                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
                            $sql = preg_replace($regex, $pdo->quote($binding), $sql, 1);
                        }

                        return $sql;
                    }
                    break;
                case 'query':
                    return $query->toBase();
                case 'query_builder':
                    return $query;
                case 'pager':
                    return $query->paginate($options['size'], $options['columns'], 'page', $options['page']);
                case 'result':
                    if (!@$options['hydrate']) {
                        $q = $query->toBase();

                        if (array_key_exists('limit', $options)) {
                            $q->limit($options['limit']);
                        }
                        if (array_key_exists('skip', $options)) {
                            $q->skip($options['skip']);
                        }

                        return $q->get(@$options['columns'])->all();
                    } else {
                        if (array_key_exists('limit', $options)) {
                            $query->getQuery()->limit($options['limit']);
                        }
                        if (array_key_exists('skip', $options)) {
                            $query->getQuery()->skip($options['skip']);
                        }

                        return $query->get(@$options['columns']);
                    }
                case 'one':
                    if (!@$options['hydrate']) {
                        $q = $query->toBase();
                        $q->limit(1);
                        return $q->get(@$options['columns'])->first();
                    } else {
                        $query->getQuery()->limit(1);
                        return $query->first(@$options['columns']);
                    }
                case 'count':
                    return $query->toBase()->count();
                default:
                    break;
            }
        }

        throw new \BadMethodCallException(
            "Undefined method '$method'."
        );
    }

    /**
     * 构造查询范围
     * @param Builder $builder
     * @param array $limiters
     */
    protected function applySimpleLimiters(Builder $builder, array $limiters)
    {
        foreach ($limiters ?: array() as $k => $v) {
            if (is_array($v)) {
                if (array_key_exists('min', $v) || array_key_exists('max', $v)) {
                    if (array_key_exists('min', $v) && isset($v['min'])) {
                        $builder->where($k, '>=', $v);
                    }
                    if (array_key_exists('max', $v) && isset($v['max'])) {
                        $builder->where($k, '<=', $v);
                    }
                } else {
                    $builder->getQuery()->whereIn($k, $v);
                }
            } elseif ($v instanceof Limiter) {
                if ($v->operator === Limiter::IN || $v->operator === Limiter::NOT_IN) {
                    $builder->getQuery()->whereIn($v->field, $v->value, 'and', $v->operator === Limiter::NOT_IN);
                } elseif ($v->operator === Limiter::BETWEEN || $v->operator === Limiter::NOT_BETWEEN) {
                    $builder->getQuery()->whereBetween($v->field, $v->value, 'and', $v->operator === Limiter::NOT_BETWEEN);
                } elseif ($v->operator === Limiter::CALLBACK && is_callable($v->field)) {
                    $builder->where($v->field);
                } else {
                    $builder->where($v->field, $v->operator, $v->value);
                }
            } else {
                $builder->where($k, '=', $v);
            }
        }
    }

    /**
     * 构造正反序查询
     * @param Builder $builder
     * @param $orderby
     */
    protected function applySimpleOrders(Builder $builder, $orderby)
    {
        if (is_string($orderby)) {
            $orders = explode(',', $orderby);
        } else {
            $orders = $orderby;
        }
        if (isset($orders) && count($orders) > 0) {
            foreach ($orders as $order) {
                if (preg_match('#^ *([\w.]+) *(asc|desc)? *$#i', $order, $m)) {
                    $builder->getQuery()->orderBy($m[1], strtolower(@$m[2]) ?: 'asc');
                }
            }
        }
    }

    /**
     * 添加查询范围
     * @param array $field
     * @param $value
     * @param array $limiters
     * @param array $options
     * @return Builder
     * @internal param array $arguments
     */
    protected function callQueryBy($field, $value, $limiters = [], $options = [])
    {
        if (is_array($value)) {
            return $this->query($limiters, $options)->whereIn($field, $value);
        } else {
            return $this->query($limiters, $options)->where($field, '=', $value);
        }
    }

    /**
     * 生成查询
     * @param array $limiters
     * @param array $options
     * @return Builder
     */
    protected function query($limiters = [], $options = [])
    {
        /* @var $builder Builder */
        $builder = call_user_func([$this->model(), 'query']);
        $builder->withoutGlobalScopes($this->withoutLandlords);

        if (@$options['apply_to']) {
            if (is_callable($options['apply_to'])) {
                call_user_func($options['apply_to'], $builder);
            }
        }

        if (@$options['landlords']) {
            $builder->withoutGlobalScopes($options['landlords']);
        }

        if ($limiters) {
            $this->applySimpleLimiters($builder, $limiters);
        }

        if (@$options['orderby']) {
            $this->applySimpleOrders($builder, $options['orderby']);
        }

        if (@$options['with']) {
            $builder->with($options['with']);
        }

        if (@$options['joins']) {
            foreach ($options['joins'] as $j) {
                $builder->join(\DB::raw($j->right), $j->on, null, null, $j->type, $where = false);
            }
        }

        return $builder;
    }

    /**
     * 合并查询选项值
     * @param $expect
     * @param array $options
     * @return array
     */
    private function mergeOptions($expect, $options = array())
    {

        $model = call_user_func([$this->model(), 'getModel']);
        $table = $model->getTable();

        if ($expect === 'pager') {
            $options = array_merge(array(
                'page'    => 1,     //页码
                'size'    => 20     //分页大小
            ), $options);
        } elseif ($expect === 'result') {
            $options = array_merge(array(
                'hydrate' => true,
                'limit'   => 500         //Limit行数
            ), $options);
        } elseif ($expect === 'one') {
            $options = array_merge(array(
                'hydrate' => true
            ), $options);
        } elseif ($expect === 'sql') {
            $options = array_merge([
                'sql_type' => 'raw'  // statement/raw/array
            ], $options);
        }

        $options = array_merge(array(
            'landlords'    => [],
            'with'         => [],
            'joins'        => [],
            'columns'      => ["$table.*"]
        ), $options);

        return $options;
    }

    /**
     * 设置withoutLandlords内容
     * @param $landlords
     */
    public function setWithoutLandloads($landlords)
    {
        $this->withoutLandlords = $landlords;
    }

    /**
     * 根据主键数组进行查询
     * @param $ids
     * @param array $limiters
     * @param array $options
     * @return array
     */
    public function retrieveByPKs($ids, $limiters = [], $options = [])
    {
        $model = call_user_func([$this->model(), 'getModel']);
        $rfl = new \ReflectionClass(Model::class);
        $p = $rfl->getProperty('keyType');
        $p->setAccessible(true);

        if ($p->getValue($model) === 'int') {
            $ids = QArray::make($ids)->map(function($v) {
                return (int)$v;
            })->asArray();
        }


        $ids = array_values(array_unique(array_filter($ids)));
        if (!$ids) {
            return array();
        }

        $method = "findBy" . Str::camel($model->getKeyName());
        return $this->$method($ids, $limiters, $options);
    }

    /**
     * 根据某一个主键进行查询
     * @param $id
     * @param array $limiters
     * @param array $options
     * @return mixed
     */
    public function retrieveByPK($id, $limiters = [], $options = [])
    {
        $model = call_user_func([$this->model(), 'getModel']);
        $rfl = new \ReflectionClass(Model::class);
        $p = $rfl->getProperty('keyType');
        $p->setAccessible(true);

        if ($p->getValue($model) === 'int') {
            $id = (int)$id;
        }

        $method = "findOneBy" . Str::camel($model->getKeyName());
        return $this->$method($id, $limiters, $options);
    }
}