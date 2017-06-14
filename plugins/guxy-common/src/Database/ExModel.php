<?php


namespace Guxy\Common\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Guxy\Common\Exceptions\CodeSafeException;

/**
 * 数据表操作扩展类 用于模型的继承用
 * Class ExModel
 * @package Guxy\Common\Database
 */
trait ExModel
{
    private static $_base_disallowed_compute_fields = [];
    private static $_base_disallowed_compute_methods = [];

    protected $tenantColumns = [];
    protected $_pending_incrs = [];


    protected $compute_cache = [];

    /**
     * 注册事件的返回函数
     */
    public static function bootExModel()
    {
        static::updating(function (Model $model) {
            $model->setAttributes($model->_pending_incrs);
        }, -10);

        static::updated(function (Model $model) {
            foreach ($model->_pending_incrs as $field => $expr) {
                $model->setAttribute($field, $expr->_oldVal + $expr->_incr);
                $model->syncOriginalAttribute($field);
            }

            $model->_pending_incrs = [];
        }, 10);
    }

    /**
     * Define a one-to-many relationship.
     * 一对多操作
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasJsonMany($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related;

        $localKey = $localKey ?: $this->getKeyName();

        return new HasJsonMany($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    /**
     * 表查询的工厂方法实例化
     * @return DefaultRepository
     */
    public static function repository($withoutLandlords = [])
    {
        if (!is_array($withoutLandlords)) {
            $withoutLandlords = func_get_args();
        }

        /* @var $repo DefaultRepository */
        if (class_exists(static::class . 'Repository')) {
            $repo = app(static::class . 'Repository');
        } else {
            $repo = new DefaultRepository(static::class);
        }

        $repo->setWithoutLandloads($withoutLandlords);
        return $repo;
    }

    /**
     * 返回表查询工厂方法的实例化
     * @return DefaultRepository
     */
    public function getRepository($withoutLandlords = [])
    {
        if (!is_array($withoutLandlords)) {
            $withoutLandlords = func_get_args();
        }

        return static::repository($withoutLandlords);
    }

    private $_exmodel_booted = false;

    protected function bootIfNotBooted()
    {
        parent::bootIfNotBooted();

        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            if (method_exists($class, $method = 'dynamicBoot'.class_basename($trait))) {
                $this->$method();
            }
        }
    }

    /**
     * 设置模型字段
     * @param $attrs
     */
    public function setAttributes($attrs)
    {
        foreach ($attrs as $k => $v) {
            $this->setAttribute($k, $v);
        }
    }

    public function computeAttribute($key, $default = null)
    {
        // 安全性检查:
        //   0. 不允许语句或代码块
        //   1. 函数调用只允许属性链内，不允许调用全局函数; 既函数调用必须有 -> 开头
        //   2. 方法调用只能调用getXXX / isXXX / formatXXX / toXXX / hasXXX系列的方法(保证只读)
        //   3. 不允许调用Model基类定义的方法和属性

        if (!trim($key)) {
            return $default;
        }

        $key = "->$key";

        if (preg_match('@[;{},]@imu', $key)) {
            throw new CodeSafeException('拒绝执行', 400);
        }

        if (preg_match('@(?:^|\W)(?<!->)\s*(\w+)\s*(?=[(])@imu', $key)) {
            throw new CodeSafeException('拒绝执行', 401);
        }

        if (preg_match('@(?<=->)\s*((?!get|is|format|to|has)\w*)\s*(?=[(])@imu', $key)) {
            throw new CodeSafeException('拒绝执行', 402);
        }

        if (!static::$_base_disallowed_compute_fields) {
            $rfl = new \ReflectionClass(Model::class);
            static::$_base_disallowed_compute_fields = collect($rfl->getProperties())->pluck('name')->all();
            static::$_base_disallowed_compute_methods = collect($rfl->getMethods())->pluck('name')->all();
        }

        preg_match_all('@(?<=->)\s*\w+\s*(?=[(])@imu', $key, $m0);
        preg_match_all('@(?<=->)\s*\w+\s*(?![(])(?:$|[\M])@imu', $key, $m1);

        if (array_intersect($m0[0], static::$_base_disallowed_compute_methods)) {
            throw new CodeSafeException('拒绝执行', 403);
        }

        if (array_intersect($m1[0], static::$_base_disallowed_compute_fields)) {
            throw new CodeSafeException('拒绝执行', 404);
        }

        $path = array_slice(explode('->', $key), 1);

        $node = $this;

        foreach ($path as $p) {
            if ($node === null) {
                return $default;
            }

            if (is_object($node)) {
                eval("\$node = \$node->$p;");
            } else {
                return $default;
            }
        }

        return $node;
    }

    public function incrBy($field, $incr = 1)
    {
        $incr = (int)$incr;

        $oldExpr = @$this->_pending_incrs[$field];
        if ($oldExpr) {
            $incr += $oldExpr->_incr;
            $oldVal = $oldExpr->_oldVal;
        } else {
            $oldVal = $this->getAttribute($field);
        }


        /* @var $q \Illuminate\Database\Eloquent\Builder */
        $q = static::query();
        $wfield = $q->toBase()->getGrammar()->wrap($field);

        if ($incr == 0) {
            unset($this->_pending_incrs[$field]);
            $this->setAttribute($field, $oldVal);
            $this->syncOriginalAttribute($field);
            return;
        } elseif ($incr > 0) {
            $expr = "$wfield + " . $incr;
        } else {
            $expr = "$wfield - " . abs($incr);
        }

        $expr = new Expression($expr);
        $expr->_incr = $incr;
        $expr->_oldVal = $oldVal;

        $this->_pending_incrs[$field] = $expr;
        $this->setAttribute($field, $oldVal + $incr);
        $this->syncOriginalAttribute($field);
    }
}