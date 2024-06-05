<?php


use kriss\bcmath\BCSummary;
test('average', function () {
    // 小数
    $result = BCSummary::create(['scale' => 3])->average(18.8, [2.2, 2.2, 4.4]);
    expect($result)->toEqual([4.7, 4.7, 9.4]);

    // 简单
    $result = BCSummary::create(['scale' => 2])->average(100, [2, 3]);
    expect($result)->toEqual([40, 60]);

    // 带key
    $result = BCSummary::create(['scale' => 2])->average(100, ['A' => 2, 'B' => 3]);
    expect($result)->toEqual(['A' => 40, 'B' => 60]);

    // 有小数
    $result = BCSummary::create(['scale' => 2])->average(100, ['A' => 3, 'B' => 6]);
    expect($result)->toEqual(['A' => 33.33, 'B' => 66.67]);

    // 多位
    $result = BCSummary::create(['scale' => 2])->average(100, [1, 2, 3, 4]);
    expect($result)->toEqual([10, 20, 30, 40]);

    // 小数
    $result = BCSummary::create(['scale' => 3])->average(18.8, [2.2, 2.2, 4.4]);
    expect($result)->toEqual([4.7, 4.7, 9.4]);

    // 小数2
    $result = BCSummary::create(['scale' => 3])->average(25, [1.2, 3.5, 7.8]);
    expect($result)->toEqual([2.4, 7, 15.6]);

    // 小数3
    $result = BCSummary::create(['scale' => 3])->average(14, [1.259, 2.518, 5.036]);
    expect($result)->toEqual([2, 4, 8]);

    // 小数4
    $result = BCSummary::create(['scale' => 3])->average(7.777, [1.259, 2.518, 5.036]);
    expect($result)->toEqual([1.111, 2.222, 4.444]);

    // 小数5
    $result = BCSummary::create(['scale' => 3])->average(7.7778, [1.259, 2.518, 5.036]);
    expect($result)->toEqual([1.111, 2.222, 4.445]);

    // 保留一位
    $result = BCSummary::create(['scale' => 1])->average(6.66, [1.5, 3, 4.5]);
    expect($result)->toEqual([1.1, 2.2, 3.4]);

    // 保留一位，最后多余的使用舍去法
    $result = BCSummary::create(['scale' => 1, 'floor' => true])->average(6.66, [1.5, 3, 4.5]);
    expect($result)->toEqual([1.1, 2.2, 3.3]);

    // 总数为 0
    $result = BCSummary::create(['scale' => 2])->average(0, [1, 2]);
    expect($result)->toEqual([0, 0]);
});

test('upgrade', function () {
    // 简单
    $result = BCSummary::create(['scale' => 2])->upgrade(50, 100);
    expect($result)->toEqual(1);

    // 小数
    $result = BCSummary::create(['scale' => 2])->upgrade(50.52, 100.38);
    expect($result)->toEqual(0.99);

    // 获取百分比值
    $result = BCSummary::create(['scale' => 2])->upgrade(50.52, 100.38, 100) . '%';
    expect($result)->toEqual('98.69%');

    // 原值为 0
    $result = BCSummary::create(['scale' => 2])->upgrade(0, 100.38);
    expect($result)->toEqual(1);

    // 原值为 0，新值为 0
    $result = BCSummary::create(['scale' => 2])->upgrade(0, 0);
    expect($result)->toEqual(0);

    // 负数
    $result = BCSummary::create(['scale' => 4])->upgrade(102.5, 95.85);
    expect($result)->toEqual(-0.0649);
});
