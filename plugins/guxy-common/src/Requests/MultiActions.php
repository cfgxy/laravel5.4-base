<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2016/11/22
 * Time: 下午2:12
 */

namespace Guxy\Common\Requests;

/**
 * 批量验证类
 * Class MultiActions
 * @package App\Http
 */
trait MultiActions
{

    /**
     * @var string 批量处理动作
     */
    public $multiAction;

    /**
     * @var int 单一处理ID
     */
    public $id;

    public function authorize()
    {
        if ($this->multiAction && !in_array($this->multiAction, $this->allowedMultiActions)) {
            return false;
        }

        return parent::authorize();
    }

    /**
     * 验证规则
     * @return mixed
     */
    public function rules()
    {
        $rules = parent::rules();

        foreach ($rules as &$rule) {
            if (is_string($rule)) {
                $rule = preg_replace('@^@', 'sometimes|', $rule);
                $rule = preg_replace('@|required@', '', $rule);
            } elseif (is_array($rule)) {
                array_unshift($rule, 'sometimes');
                $rule = preg_replace('@^required$@', '', $rule);
                $rule = array_filter($rule);
            }
        }
        unset($rule);

        return $rules;
    }

    /**
     * 整理内容
     * @return array
     */
    public function sanitize()
    {
        $this->multiAction = \Route::input('datum');

        $id_type = 'int';
        if (property_exists($this, 'id_type')) {
            $id_type = $this->id_type;
        }

        if (is_numeric($this->multiAction)) {
            if ($id_type == 'int') {
                $this->id = (int)$this->multiAction;
            }
            $this->multiAction = null;
        }

        if ($this->multiAction) {
            $this->commaArray($this->request, ['ids']);
            if ($id_type == 'int') {
                $this->numberInt($this->request, ['ids[]']);
            }
            return mask($this->request, ['ids']);
        }

        return parent::sanitize();
    }
}