<?php

namespace kriss\bcmath;

/**
 * @method float|string add(...$numbers)
 * @method float|string sub(...$numbers)
 * @method float|string mul(...$numbers)
 * @method float|string div(...$numbers)
 * @method float|string mod(...$numbers)
 * @method float|string pow(...$numbers)
 * @method int compare($number1, $number2)
 */
class BC extends BaseBC
{
    public static function create($config = [])
    {
        return new static($config);
    }

    public function __call($name, $arguments)
    {
        if (!isset($arguments[0])) {
            return 0;
        }
        $bcs = BCS::create($arguments[0], $this->config);
        unset($arguments[0]);
        $bcs = $bcs->$name(...$arguments);
        if ($name === 'compare') {
            /** @var int $bcs */
            return $bcs;
        }
        /** @var BCS $bcs */
        return $bcs->getResult();
    }
}
