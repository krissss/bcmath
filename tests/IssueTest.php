<?php

use kriss\bcmath\BCS;
use PHPUnit\Framework\TestCase;

class IssueTest extends TestCase
{
    // https://github.com/krissss/bcmath/issues/2
    public function testIssues2()
    {
        $total = BCS::create(0, ['scale' => 6]);
        $total->add(1);
        $total->add(0.000008);

        $this->assertEquals(1.000008, $total->getResult());
    }
}
