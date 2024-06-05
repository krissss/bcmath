<?php

use kriss\bcmath\BCS;
test('issues2', function () {
    $total = BCS::create(0, ['scale' => 6]);
    $total->add(1);
    $total->add(0.000008);

    expect($total->getResult())->toEqual(1.000008);
});
