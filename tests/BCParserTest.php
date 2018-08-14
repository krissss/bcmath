<?php


use kriss\bcmath\BCParser;
use PHPUnit\Framework\TestCase;

class BCParserTest extends TestCase
{

    public function testParse()
    {
        // 正常
        $result = BCParser::create(['scale' => 2])->parse('1.2*1.3+5');
        $this->assertEquals(6.56, $result);

        // 复杂的公式
        $result = BCParser::create(['scale' => 4])->parse('1.2*1.3^2+19/(2-8)');
        $this->assertEquals(-1.1387, $result);

        // 使用变量替换公式
        $result = BCParser::create(['scale' => 2])->parse('{a}* {b}+{c}', ['a' => '1.2', 'b' => '1.3', 'c' => 5]);
        $this->assertEquals(6.56, $result);

        // 当其中某个变量未设置时
        $result = BCParser::create(['scale' => 2])->parse('{a}* {b}+{c}', ['a' => '1.2', 'b' => '1.3']);
        $this->assertEquals(1.56, $result);
    }
}
