<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/10
 * Time: 下午5:56
 */

namespace Tests\Guxy\Common;

use Tests\TestCase;
use Guxy\Common\ExEnum;

/**
 * @Enum({"PENDING",  "等待执行"})
 * @Enum({"RUNNING",  "执行中"})
 * @Enum({"COMPLETE", "已完成"})
 * @Enum({"SKIPED",   "已跳过"})
 */
class SampleEnum
{
    use ExEnum;

    const PENDING  = 1;
    const RUNNING  = 2;
    const COMPLETE = 3;
    const SKIPED   = 4;
}


class TestEnum extends TestCase
{

    public function testList()
    {

        $this->assertEquals([
            '1'   => "等待执行",
            '2'   => "执行中",
            '3'   => "已完成",
            '4'   => "已跳过"
        ], SampleEnum::list());

        $this->assertEquals([
            '0'   => '--请选择--',
            '1'   => "等待执行",
            '2'   => "执行中",
            '3'   => "已完成",
            '4'   => "已跳过"
        ], SampleEnum::list('--请选择--', 0));

        $this->assertEquals('等待执行', SampleEnum::display(1));
        $this->assertEquals('PENDING', SampleEnum::displayName(1));
        $this->assertTrue(SampleEnum::valid(2));
        $this->assertTrue(SampleEnum::validName('COMPLETE'));
        $this->assertNotTrue(6);
        $this->assertNotTrue(SampleEnum::validName('muhaha'));
        $this->assertEquals(3, SampleEnum::fromName('COMPLETE'));
    }
}
