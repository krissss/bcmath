<?php

use kriss\bcmath\BCS;

test('get result', function () {
    // 默认四舍五入
    $result = BCS::create(1.5, ['scale' => 2])->add(1.2)->mul(2)->sub(1.5)->getResult();
    expect($result)->toEqual(3.9);

    // 四舍五入
    $result = BCS::create(1.35, ['scale' => 2, 'round' => true])->add(1.2)->mul(1.35)->getResult();
    expect($result)->toEqual(3.44);

    // 向上保留
    $result = BCS::create(1.35, ['scale' => 2, 'ceil' => true])->add(1.2)->mul(1.35)->getResult();
    expect($result)->toEqual(3.45);

    // 向上保留，精度为0
    $result = BCS::create(82.5, ['scale' => 0, 'ceil' => true])->getResult();
    expect($result)->toEqual(83);

    // 向上保留，精度为2，尾数多0的情况
    $result = BCS::create(10.0000, ['scale' => 2, 'ceil' => true])->getResult();
    expect($result)->toEqual(10);
    $result = BCS::create(10.0001, ['scale' => 2, 'ceil' => true])->getResult();
    expect($result)->toEqual(10.01);

    // 舍位
    $result = BCS::create(1.35, ['scale' => 2, 'floor' => true])->add(1.2)->mul(1.35)->add(0.002)->getResult();
    expect($result)->toEqual(3.44);

    // 舍位，末位为9的情况
    $result = BCS::create(10.0198, ['scale' => 2, 'floor' => true])->getResult();
    expect($result)->toEqual(10.01);

    // 舍位，精度为0
    $result = BCS::create(82.5, ['scale' => 0, 'floor' => true])->getResult();
    expect($result)->toEqual(82);

    // 操作过程中精度保留
    $result = BCS::create(1.352, ['scale' => 2, 'operateScale' => 2])->add(0.014)->add(0.005)->getResult();
    expect($result)->toEqual(1.36);
});
test('is equal', function () {
    $result = BCS::create(1.2, ['scale' => 0])->isEqual(1.6);
    expect($result)->toEqual(true);

    $result = BCS::create(1.2, ['scale' => 2])->isEqual(1.6);
    expect($result)->toEqual(false);

    $result = BCS::create(1.6, ['scale' => 2])->isEqual(1.6);
    expect($result)->toEqual(true);
});
test('is larger than', function () {
    $result = BCS::create(1.2, ['scale' => 0])->isLargerThan(1.6);
    expect($result)->toEqual(false);

    $result = BCS::create(1.2, ['scale' => 2])->isLargerThan(1.6);
    expect($result)->toEqual(false);

    $result = BCS::create(1.7, ['scale' => 2])->isLargerThan(1.6);
    expect($result)->toEqual(true);
});
test('sub', function () {
    $result = BCS::create(1.2, ['scale' => 2])->sub(2.37, 3.84, 8.4)->getResult();
    expect($result)->toEqual(-13.41);
});
test('get sqrt', function () {
    $result = BCS::create(4, ['scale' => 2])->getSqrt();
    expect($result)->toEqual(2);
});
test('mul', function () {
    $result = BCS::create(1.2, ['scale' => 2])->mul(2.37, 3.84, 8.4)->getResult();
    expect($result)->toEqual(91.74);

    $result = BCS::create(1.2, ['scale' => 4, 'floor' => true])->mul(2.37, 3.84, 8.4)->getResult();
    expect($result)->toEqual(91.7360);
});
test('div', function () {
    $result = BCS::create(1.2, ['scale' => 6])->div(2.37, 3.84, 8.4)->getResult();
    expect($result)->toEqual(0.015697);
});
test('pow', function () {
    $result = BCS::create(1.2, ['scale' => 2])->pow(2, 2)->getResult();
    expect($result)->toEqual(2.07);

    // pow 不支持小数乘方，会自动转为整数
    $result = BCS::create(1.2, ['scale' => 6])->pow(2.37)->getResult();
    expect($result)->toEqual(1.44);
});
test('add', function () {
    $result = BCS::create(1.2, ['scale' => 2])->add(2.37, 3.84, 8.4)->getResult();
    expect($result)->toEqual(15.81);
});
test('compare', function () {
    $result = BCS::create(1.2, ['scale' => 0])->compare(1.6);
    expect($result)->toEqual(0);

    $result = BCS::create(1.2, ['scale' => 2])->compare(1.6);
    expect($result)->toEqual(-1);

    $result = BCS::create(1.2, ['scale' => 2])->compare(1.2);
    expect($result)->toEqual(0);
});
test('mod', function () {
    $result = BCS::create(10)->mod(5)->getResult();
    expect($result)->toEqual(0);

    $result = BCS::create(12)->mod(10)->getResult();
    expect($result)->toEqual(2);
});
test('is less than', function () {
    $result = BCS::create(1.2, ['scale' => 0])->isLessThan(1.6);
    expect($result)->toEqual(false);

    $result = BCS::create(1.2, ['scale' => 2])->isLessThan(1.6);
    expect($result)->toEqual(true);
});
test('not exist bc method', function () {
    BCS::create(1)->xyz(0);
})->throws(Exception::class, 'xyz not in ::bcEnables');
test('change config in invalid', function () {
    $bcs = BCS::create(1.2222, ['scale' => 2]);
    $bcs->config['round'] = false;

    $result = $bcs->add(0.2255)->getResult();
    expect($result)->toEqual(1.4477);
});