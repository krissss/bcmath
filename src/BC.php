<?php

namespace kriss\bcmath;

/**
 * @method float|string add(float|string|int|null ...$numbers)
 * @method float|string sub(float|string|int|null ...$numbers)
 * @method float|string mul(float|string|int|null ...$numbers)
 * @method float|string div(float|string|int|null ...$numbers)
 * @method float|string mod(float|string|int|null ...$numbers)
 * @method float|string pow(float|string|int|null ...$numbers)
 * @method int compare(float|string|int|null $number1, float|string|int|null $number2)
 */
class BC extends BaseBC
{
    public static function create(array $config = []): static
    {
        return new static($config);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return float|string|int|BCS
     */
    public function __call(string $name, array $arguments): float|string|int|BCS
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
