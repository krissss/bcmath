<?php

use kriss\bcmath\BC;
use kriss\bcmath\BCS;

test('issue-2', function () {
    $total = BCS::create(0, ['scale' => 6]);
    $total->add(1);
    $total->add(0.000008);
    expect($total->getResult())->toEqual(1.000008);

    $total = BCS::create(0, ['scale' => 6]);
    $total->add(1e3);
    $total->add(0.000008);
    expect($total->getResult())->toEqual(1000.000008);

    $total = BCS::create(0, ['scale' => 6]);
    $total->add('1e3');
    $total->add(0.000008);
    expect($total->getResult())->toEqual(1000.000008);
});

test('issue-7', function () {
    $result = BC::create(['scale' => 2])->compare('1e3', 100);
    expect($result)->toEqual(1);

    $result = BCS::create(1233123131432143214321412.123)->compare(12312312312312);
    expect($result)->toEqual(1);

    $result = BCS::create('1e3')->compare(12312);
    expect($result)->toEqual(-1);

    $result = BCS::create(123)->compare('1e3');
    expect($result)->toEqual(-1);
});

test('issue-9', function () {
    $result = BCS::create((string)1233123131432143214321412.123)->compare((string)1233123131432143214321411.123);
    expect($result)->toEqual(0);
    $result = BCS::create(1233123131432143214321412.123)->compare(1233123131432143214321411.123);
    expect($result)->toEqual(0);

    $result = BCS::create('1233123131432143214321412.123')->compare('1233123131432143214321411.123');
    expect($result)->toEqual(1);
});

test('issue-11', function () {
    $a = BCS::create(1.66, ['scale' => 2]);
    $l = [
        0.110000,0.110000, 0.110000, 0.090000, 0.080000, 0.080000, 0.050000, 0.050000, 0.050000, 0.050000, 0.050000, 0.110000, 0.110000, 0.110000, 0.090000, 0.080000, 0.080000, 0.050000, 0.050000, 0.050000, 0.050000,0.050000];
    foreach ($l as $_l) {
        expect($a->isLessThan($_l))->toBeFalse();
        $a->sub($_l);
    }
    expect($a->getResult())->toBe(0.0);
});
