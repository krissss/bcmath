<?php

namespace kriss\bcmath;

/**
 * @method float|string add(...$numbers)
 * @method float|string sub(...$numbers)
 * @method float|string mul(...$numbers)
 * @method float|string div(...$numbers)
 * @method float|string mod(...$numbers)
 * @method float|string pow(...$numbers)
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
        $bcs = BCS::create($arguments[0], [
            'scale' => $this->scale,
            'rounded' => $this->rounded,
            'operateScale' => $this->operateScale,
        ]);
        unset($arguments[0]);
        /** @var BCS $bcs */
        $bcs = $bcs->$name(...$arguments);
        return $bcs->getResult();
    }
}
