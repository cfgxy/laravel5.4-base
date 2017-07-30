<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/6/28
 * Time: 下午2:38
 */

namespace Guxy\Common\Exceptions;


class StateMachineException extends \Exception
{
    const ERR_NO_TRANSACTIONS = 1;
    const ERR_TRANSACTION_IN_LIFECYCLE = 2;
    const ERR_STATE = 3;

}