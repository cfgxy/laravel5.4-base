<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/10
 * Time: 下午6:47
 */

namespace Guxy\Common\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class enum
{
    public $name;
    public $desc;

    public function __construct($values)
    {
        $this->name = $values['value'][0];
        $this->desc = $values['value'][1];
    }
}