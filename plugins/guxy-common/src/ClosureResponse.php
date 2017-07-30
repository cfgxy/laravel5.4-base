<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/7/28
 * Time: 下午3:25
 */

namespace Guxy\Common;


use Illuminate\Http\Response;

class ClosureResponse extends Response
{
    /** @var  \Closure */
    protected $contentClosure;

    public function __construct(\Closure $content = null, $status = 200, array $headers = array())
    {
        $this->contentClosure = $content;

        parent::__construct('', $status, $headers);
    }

    public function sendContent()
    {
        if ($func = $this->contentClosure) {
            $func();
        }

        return $this;
    }
}