<?php


use kriss\bcmath\BC;
use PHPUnit\Framework\TestCase;

class BCTest extends TestCase
{

    public function testAdd()
    {
        $result = BC::create(['scale' => 2])->add(1.2, 1.3);
        $this->assertEquals(2.5, $result);
    }

    public function testDiv()
    {
        $result = BC::create(['scale' => 6])->div(1.2, 1.3);
        $this->assertEquals(0.923077, $result);
    }

    public function testPow()
    {
        $result = BC::create(['scale' => 6])->pow(1.2, 2);
        $this->assertEquals(1.44, $result);

        // pow 无小数次幂
        $result = BC::create(['scale' => 6])->pow(1.2, 2.2);
        $this->assertEquals(1.44, $result);
    }

    public function testSub()
    {
        $result = BC::create(['scale' => 6])->sub(1.2, 1.3);
        $this->assertEquals(-0.1, $result);
    }

    public function testMod()
    {
        $result = BC::create(['scale' => 6])->mod(5, 2);
        $this->assertEquals(1, $result);
    }

    public function testMul()
    {
        $result = BC::create(['scale' => 6])->mul(1.2, 1.3, 1.1);
        $this->assertEquals(1.716, $result);
    }
}
