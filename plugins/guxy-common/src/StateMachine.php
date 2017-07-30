<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/6/28
 * Time: 下午2:25
 */

namespace Guxy\Common;
use Illuminate\Support\Str;
use Guxy\Common\Exceptions\StateMachineException;


/**
 * Class StateMachine
 * 状态机;
 * 模型参考 @link https://github.com/jakesgordon/javascript-state-machine
 * 使用方法参考 TestStateMachine
 * @package Guxy\Common
 */
abstract class StateMachine
{

    protected $init_state;

    /**
     * 在子类中定义; 格式
     * [
     *      动作1, 源状态, 目标状态,
     *      动作2, 源状态, 目标状态
     * ]
     * @var array
     */
    protected $transitions;

    private $in_lifecycle;
    private $state;
    private $state_history = [];

    /**
     * StateMachine constructor.
     * @param mixed $state
     * @throws StateMachineException
     */
    public function __construct($state = null)
    {
        if (!$this->transitions) {
            throw new StateMachineException("You should define the StateMachine's transitions field.", StateMachineException::ERR_NO_TRANSACTIONS);
        }

        if (is_string($this->transitions[0])) {
            $this->transitions = array_chunk($this->transitions, 3);
        }

        if ($state !== null) {
            $this->state = $state;
        } elseif ($this->init_state === null) {
            $this->state = $this->transitions[0][1];
        } else {
            $this->state = $this->init_state;
        }

        $this->state_history[] = $this->state;
    }


    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    protected function changeState($trans, $state, $with_life_cycle = true)
    {
        if (!$with_life_cycle) {
            if ($trans && !$this->can($trans)) {
                return;
            }

            $differ = $this->state != $state;

            $this->state = $state;

            if ($trans || $differ) {
                $this->state_history[] = $state;
            }
            return;
        }


        if ($this->in_lifecycle) {
            throw new StateMachineException("Trying to transition from within a lifecycle event is not allowed.", StateMachineException::ERR_TRANSACTION_IN_LIFECYCLE);
        }

        $this->in_lifecycle = true;

        $old = $this->state;

        $trans_word = ucfirst(Str::camel($trans));
        $old_word = ucfirst(Str::camel($old));
        $state_word = ucfirst(Str::camel($state));

        if (method_exists($this, 'onBeforeTransition')) {
            $this->onBeforeTransition($trans, $old, $state);
        }

        if (method_exists($this, "onBefore$trans_word")) {
            $this->{"onBefore$trans_word"}($trans, $old, $state);
        }

        if ($old != $state) {
            if (method_exists($this, 'onLeaveState')) {
                $this->onLeaveState($trans, $old, $state);
            }

            if (method_exists($this, "onLeave$old_word")) {
                $this->{"onLeave$old_word"}($trans, $old, $state);
            }
        }

        $this->state = $state;

        $this->state_history[] = $state;

        if (method_exists($this, 'onTransition')) {
            $this->onTransition($trans, $old, $state);
        }

        if (method_exists($this, "on$trans_word")) {
            $this->{"on$trans_word"}($trans, $old, $state);
        }

        if ($old != $state) {
            if (method_exists($this, 'onEnterState')) {
                $this->onEnterState($trans, $old, $state);
            }

            if (method_exists($this, "onEnter$state_word")) {
                $this->{"onEnter$state_word"}($trans, $old, $state);
            }
        }

        if (method_exists($this, 'onAfterTransition')) {
            $this->onAfterTransition($trans, $old, $state);
        }

        if (method_exists($this, "onAfter$trans_word")) {
            $this->{"onAfter$trans_word"}($trans, $old, $state);
        }

        $this->in_lifecycle = false;
    }

    public function __call($name, $arguments)
    {
        $has_method = false;

        foreach ($this->transitions as $trans) {
            if ($trans[0] == $name) {
                $has_method = true;
                if ($this->state == $trans[1]) {
                    $this->changeState($name, $trans[2]);
                    return;
                }
            }
        }

        if ($has_method) {
            throw new StateMachineException("Transaction '$name' not allowed from state '$this->state'.", StateMachineException::ERR_STATE);
        }

        throw new \BadMethodCallException(
            "Undefined method '$name'."
        );
    }

    public function can($transaction)
    {
        foreach ($this->transitions as $trans) {
            if ($trans[0] == $transaction && $this->state == $trans[1]) {
                return true;
            }
        }

        return false;
    }

    public function history()
    {
        return $this->state_history;
    }

    public function clearHistory()
    {
        $this->state_history = [$this->state];
    }
}