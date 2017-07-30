<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/10
 * Time: 下午5:56
 */

namespace Tests\Guxy\Common;

use Tests\TestCase;
use Guxy\Common\Exceptions\StateMachineException;
use Guxy\Common\ExEnum;
use Guxy\Common\StateMachine;

class SampleMachine1 extends StateMachine
{
}

class SampleMachine2 extends StateMachine
{
    protected $transitions = [
        'step', 'A', 'B',
        'step', 'B', 'C',
        'step', 'C', 'A'
    ];
}

class SampleMachine3 extends StateMachine
{
    protected $init_state = '液体';

    /**
     * 为便于理解; 全用中文表示, 实际使用不建议这么干
     * @var array
     */
    protected $transitions = [
        '熔化', '固体', '液体',
        '汽化', '液体', '气体',
        '液化', '气体', '液体',
        '凝固', '液体', '固体',
        '升华', '固体', '气体',
        '凝华', '气体', '固体'
    ];
}

class SampleLifecycle extends StateMachine
{
    /**
     * 同SampleMachine3，英文版，演示 Lifecycle
     * @var array
     */
    protected $transitions = [
        'melt', 'solid', 'liquid',
        'vaporize', 'liquid', 'gas',
        'condense', 'gas', 'liquid',
        'freeze', 'liquid', 'solid'
    ];

    public function onBeforeMelt()
    {
        echo 'Before melt.', PHP_EOL;
    }

    public function onMelt()
    {
        echo 'In melt.', PHP_EOL;
    }

    public function onAfterMelt()
    {
        echo 'After melt.', PHP_EOL;
    }

    public function onLeaveSolid()
    {
        echo 'Leaving solid.', PHP_EOL;
    }

    public function onEnterLiquid()
    {
        echo 'Enter liquid.', PHP_EOL;
    }
}


class TestStateMachine extends TestCase
{

    public function testMachine1()
    {
        try {
            $machine = new SampleMachine1();
            $this->fail('Here should be exception, because we not define the transactions field.');
        } catch (\Exception $ex) {
            $this->assertInstanceOf(StateMachineException::class, $ex);
        }
    }

    public function testMachine2()
    {
        $machine = new SampleMachine2();

        $this->assertEquals('A', $machine->getState());
        $this->assertTrue($machine->can('step'));
        $machine->step();
        $this->assertEquals('B', $machine->getState());
        $machine->step();
        $this->assertEquals('C', $machine->getState());
        $machine->step();
        $this->assertEquals('A', $machine->getState());
        $machine->step();
        $this->assertEquals('B', $machine->getState());

        $this->assertEquals(['A', 'B', 'C', 'A', 'B'], $machine->history());
        $machine->clearHistory();
        $this->assertEquals(['B'], $machine->history());
    }

    public function testMachine3()
    {
        $machine = new SampleMachine3();
        $this->assertEquals('液体', $machine->getState());

        $machine = new SampleMachine3('气体');
        $this->assertTrue($machine->can('液化'));
        $this->assertTrue($machine->can('凝华'));
        $this->assertFalse($machine->can('凝固'));

        $machine->液化();
        $this->assertEquals('液体', $machine->getState());
        $this->assertTrue($machine->can('凝固'));
        $this->assertTrue($machine->can('汽化'));
        $this->assertFalse($machine->can('熔化'));

        try {
            $machine->熔化();
            $this->fail('液体不能熔化');
        } catch (StateMachineException $ex) {
            $this->assertEquals(3, $ex->getCode());
        }
    }

    public function testLifecycle()
    {
        $machine = new SampleLifecycle();
        $this->assertEquals('solid', $machine->getState());

        ob_start();
        $machine->melt();
        $buffer = ob_get_clean();

        $buffer = explode(PHP_EOL, $buffer);
        $this->assertEquals([
            'Before melt.',
            'Leaving solid.',
            'In melt.',
            'Enter liquid.',
            'After melt.',
            ''
        ], $buffer);
    }
}
