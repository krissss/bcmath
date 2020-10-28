<?php


use kriss\bcmath\BCSummary;
use PHPUnit\Framework\TestCase;

class BCSummaryTest extends TestCase
{

    public function testAverage()
    {
        // 小数
        $result = BCSummary::create(['scale' => 3])->average(18.8, [2.2, 2.2, 4.4]);
        $this->assertEquals([4.7, 4.7, 9.4], $result);
        // 简单
        $result = BCSummary::create(['scale' => 2])->average(100, [2, 3]);
        $this->assertEquals([40, 60], $result);

        // 带key
        $result = BCSummary::create(['scale' => 2])->average(100, ['A' => 2, 'B' => 3]);
        $this->assertEquals(['A' => 40, 'B' => 60], $result);

        // 有小数
        $result = BCSummary::create(['scale' => 2])->average(100, ['A' => 3, 'B' => 6]);
        $this->assertEquals(['A' => 33.33, 'B' => 66.67], $result);

        // 多位
        $result = BCSummary::create(['scale' => 2])->average(100, [1, 2, 3, 4]);
        $this->assertEquals([10, 20, 30, 40], $result);

        // 小数
        $result = BCSummary::create(['scale' => 3])->average(18.8, [2.2, 2.2, 4.4]);
        $this->assertEquals([4.7, 4.7, 9.4], $result);

        // 小数2
        $result = BCSummary::create(['scale' => 3])->average(25, [1.2, 3.5, 7.8]);
        $this->assertEquals([2.4, 7, 15.6], $result);

        // 小数3
        $result = BCSummary::create(['scale' => 3])->average(14, [1.259, 2.518, 5.036]);
        $this->assertEquals([2, 4, 8], $result);

        // 小数4
        $result = BCSummary::create(['scale' => 3])->average(7.777, [1.259, 2.518, 5.036]);
        $this->assertEquals([1.111, 2.222, 4.444], $result);

        // 小数5
        $result = BCSummary::create(['scale' => 3])->average(7.7778, [1.259, 2.518, 5.036]);
        $this->assertEquals([1.111, 2.222, 4.445], $result);

        // 保留一位
        $result = BCSummary::create(['scale' => 1])->average(6.66, [1.5, 3, 4.5]);
        $this->assertEquals([1.1, 2.2, 3.4], $result);

        // 保留一位，最后多余的使用舍去法
        $result = BCSummary::create(['scale' => 1, 'floor' => true])->average(6.66, [1.5, 3, 4.5]);
        $this->assertEquals([1.1, 2.2, 3.3], $result);

        // 总数为 0
        $result = BCSummary::create(['scale' => 2])->average(0, [1, 2]);
        $this->assertEquals([0, 0], $result);
    }

    public function testUpgrade()
    {
        // 简单
        $result = BCSummary::create(['scale' => 2])->upgrade(50, 100);
        $this->assertEquals(1, $result);

        // 小数
        $result = BCSummary::create(['scale' => 2])->upgrade(50.52, 100.38);
        $this->assertEquals(0.99, $result);

        // 获取百分比值
        $result = BCSummary::create(['scale' => 2])->upgrade(50.52, 100.38, 100) . '%';
        $this->assertEquals('98.69%', $result);

        // 原值为 0
        $result = BCSummary::create(['scale' => 2])->upgrade(0, 100.38);
        $this->assertEquals(1, $result);

        // 原值为 0，新值为 0
        $result = BCSummary::create(['scale' => 2])->upgrade(0, 0);
        $this->assertEquals(0, $result);

        // 负数
        $result = BCSummary::create(['scale' => 4])->upgrade(102.5, 95.85);
        $this->assertEquals(-0.0649, $result);
    }
}
