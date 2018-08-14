<?php


use kriss\bcmath\BCS;
use PHPUnit\Framework\TestCase;

class BCSTest extends TestCase
{
    public function testIsEqual()
    {
        $result = BCS::create(1.2, ['scale' => 0])->isEqual(1.6);
        $this->assertEquals(true, $result);

        $result = BCS::create(1.2, ['scale' => 2])->isEqual(1.6);
        $this->assertEquals(false, $result);

        $result = BCS::create(1.6, ['scale' => 2])->isEqual(1.6);
        $this->assertEquals(true, $result);
    }

    public function testIsLargerThan()
    {
        $result = BCS::create(1.2, ['scale' => 0])->isLargerThan(1.6);
        $this->assertEquals(false, $result);

        $result = BCS::create(1.2, ['scale' => 2])->isLargerThan(1.6);
        $this->assertEquals(false, $result);

        $result = BCS::create(1.7, ['scale' => 2])->isLargerThan(1.6);
        $this->assertEquals(true, $result);
    }

    public function testSub()
    {
        $result = BCS::create(1.2, ['scale' => 2])->sub(2.37, 3.84, 8.4)->getResult();
        $this->assertEquals(-13.41, $result);
    }

    public function testGetSqrt()
    {
        $result = BCS::create(4, ['scale' => 2])->getSqrt();
        $this->assertEquals(2, $result);
    }

    public function testMul()
    {
        $result = BCS::create(1.2, ['scale' => 2])->mul(2.37, 3.84, 8.4)->getResult();
        $this->assertEquals(91.74, $result);

        $result = BCS::create(1.2, ['scale' => 4, 'rounded' => false])->mul(2.37, 3.84, 8.4)->getResult();
        $this->assertEquals(91.7360, $result);
    }

    public function testDiv()
    {
        $result = BCS::create(1.2, ['scale' => 6])->div(2.37, 3.84, 8.4)->getResult();
        $this->assertEquals(0.015697, $result);
    }

    public function testPow()
    {
        $result = BCS::create(1.2, ['scale' => 2])->pow(2, 2)->getResult();
        $this->assertEquals(2.07, $result);

        // pow 不支持小数乘方，会自动转为整数
        $result = BCS::create(1.2, ['scale' => 6])->pow(2.37)->getResult();
        $this->assertEquals(1.44, $result);
    }

    public function testAdd()
    {
        $result = BCS::create(1.2, ['scale' => 2])->add(2.37, 3.84, 8.4)->getResult();
        $this->assertEquals(15.81, $result);
    }

    public function testCompare()
    {
        $result = BCS::create(1.2, ['scale' => 0])->compare(1.6);
        $this->assertEquals(0, $result);

        $result = BCS::create(1.2, ['scale' => 2])->compare(1.6);
        $this->assertEquals(-1, $result);

        $result = BCS::create(1.2, ['scale' => 2])->compare(1.2);
        $this->assertEquals(0, $result);
    }

    public function testMod()
    {
        $result = BCS::create(10)->mod(5)->getResult();
        $this->assertEquals(0, $result);

        $result = BCS::create(12)->mod(10)->getResult();
        $this->assertEquals(2, $result);
    }

    public function testIsLessThan()
    {
        $result = BCS::create(1.2, ['scale' => 0])->isLessThan(1.6);
        $this->assertEquals(false, $result);

        $result = BCS::create(1.2, ['scale' => 2])->isLessThan(1.6);
        $this->assertEquals(true, $result);
    }
}
