<?php


use kriss\bcmath\BCParser;
test('parse', function () {
    // 正常
    $result = BCParser::create(['scale' => 2])->parse('1.2*1.3+5');
    expect($result)->toEqual(6.56);

    // 复杂的公式
    $result = BCParser::create(['scale' => 4])->parse('1.2*1.3^2+19/(2-8)');
    expect($result)->toEqual(-1.1387);

    // 使用变量替换公式
    $result = BCParser::create(['scale' => 2])->parse('{a}* {b}+{c}', ['a' => '1.2', 'b' => '1.3', 'c' => 5]);
    expect($result)->toEqual(6.56);

    // 当其中某个变量未设置时
    $result = BCParser::create(['scale' => 2])->parse('{a}* {b}+{c}', ['a' => '1.2', 'b' => '1.3']);
    expect($result)->toEqual(1.56);

    // 取模
    $result = BCParser::create(['scale' => 2])->parse('10%3');
    expect($result)->toEqual(1);

    // 不允许的操作符
    $result = BCParser::create(['scale' => 2])->parse('12x3');
    expect($result)->toEqual(12);
});
