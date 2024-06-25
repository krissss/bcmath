<?php

use kriss\bcmath\BC;

test('add', function () {
    $result = BC::create(['scale' => 2])->add(1.2, 1.3);
    expect($result)->toEqual(2.5);
});
test('div', function () {
    $result = BC::create(['scale' => 6])->div(1.2, 1.3);
    expect($result)->toEqual(0.923077);
});
test('pow', function () {
    $result = BC::create(['scale' => 6])->pow(1.2, 2);
    expect($result)->toEqual(1.44);

    // pow 无小数次幂
    $result = BC::create(['scale' => 6])->pow(1.2, 2.2);
    expect($result)->toEqual(1.44);
});
test('sub', function () {
    $result = BC::create(['scale' => 6])->sub(1.2, 1.3);
    expect($result)->toEqual(-0.1);
});
test('mod', function () {
    $result = BC::create(['scale' => 6])->mod(5, 2);
    expect($result)->toEqual(1);
});
test('mul', function () {
    $result = BC::create(['scale' => 6])->mul(1.2, 1.3, 1.1);
    expect($result)->toEqual(1.716);
});
test('no value', function () {
    $result = BC::create(['scale' => 6])->add();
    expect($result)->toEqual(0);
});
test('compare', function () {
    $result = BC::create(['scale' => 6])->compare(1, 2);
    expect($result)->toEqual(-1);
    $result = BC::create(['scale' => 6])->compare(1, 1);
    expect($result)->toEqual(0);
    $result = BC::create(['scale' => 6])->compare(2, 1);
    expect($result)->toEqual(1);
    $result = BC::create(['scale' => 6])->compare(2, 1);
    expect($result)->toEqual(1);
    $result = BC::create(['scale' => 2])->compare(1000.00008, 1000);
    expect($result)->toEqual(0);
});
