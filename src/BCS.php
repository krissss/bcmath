<?php

namespace kriss\bcmath;

/**
 * @method BCS add(float|string|int|null ...$numbers)
 * @method BCS sub(float|string|int|null ...$numbers)
 * @method BCS mul(float|string|int|null ...$numbers)
 * @method BCS div(float|string|int|null ...$numbers)
 * @method BCS mod(float|string|int|null ...$numbers)
 * @method BCS pow(float|string|int|null ...$numbers) 只支持指数为整数的，若传入小数会自动 intval
 */
class BCS extends BaseBC
{
    /**
     * 允许使用的bc函数
     * @var array<string>
     */
    public array $bcEnables = ['bcadd', 'bcsub', 'bcmul', 'bcdiv', 'bcmod', 'bcpow'];

    /**
     * @var string|float
     */
    public string|float $result;

    /**
     * {@inheritdoc}
     */
    public function __construct(float|string|int|null $number, array $config = [])
    {
        parent::__construct($config);
        $this->result = $this->numberFormat($number);
    }

    /**
     * @param float|string|int|null $number
     * @param array $config
     * @return BCS
     */
    public static function create(float|string|int|null $number, array $config = []): BCS
    {
        return new static($number, $config);
    }

    public function __call(string $name, array $arguments): BCS
    {
        $bcName = 'bc' . $name;
        if (!in_array($bcName, $this->bcEnables, true)) {
            throw new \Exception("{$bcName} not in ::bcEnables");
        }
        foreach ($arguments as $number) {
            $number = $this->numberFormat($number);
            if ($bcName === 'bcpow') {
                $number = (string)intval($number);
            }
            if ($bcName === 'bcmod') {
                $this->result = call_user_func($bcName, $this->result, $number);
            } else {
                $this->result = call_user_func($bcName, $this->result, $number, $this->config['operateScale']);
            }
        }
        return $this;
    }

    /**
     * 获取结果
     * @return float|string
     */
    public function getResult(): float|string
    {
        return $this->getScaleNumber($this->result);
    }

    /**
     * 获取平方根
     * @return string
     */
    public function getSqrt(): string
    {
        return bcsqrt((string)$this->result, $this->config['scale']);
    }

    /**
     * 比较结果
     * @param float|string|int|null $number
     * @return int
     */
    public function compare(float|string|int|null $number): int
    {
        return bccomp((string)$this->result, $this->numberFormat($number), $this->config['scale']);
    }

    /**
     * 是否相等
     * @param float|string|int|null $number
     * @return bool
     */
    public function isEqual(float|string|int|null $number): bool
    {
        return $this->compare($number) === 0;
    }

    /**
     * 是否小于
     * @param float|string|int|null $number
     * @return bool
     */
    public function isLessThan(float|string|int|null $number): bool
    {
        return $this->compare($number) === -1;
    }

    /**
     * 是否大于
     * @param float|string|int|null $number
     * @return bool
     */
    public function isLargerThan(float|string|int|null $number): bool
    {
        return $this->compare($number) === 1;
    }

    /**
     * 格式化数字
     * @param float|string|int|null $number
     * @return string|float|int
     */
    private function numberFormat(float|string|int|null $number): string|float|int
    {
        if ($number === null) {
            // null直接返回 0
            return '0';
        }
        if (is_float($number)) {
            // 将 float 转为 string，科学计数法可以正常变成 8.0E-6
            $number = (string)$number;
        }
        if (is_string($number) && (str_contains($number, 'E') || str_contains($number, 'e'))) { // 科学计数法
            return number_format($number, $this->config['operateScaleNumberFormat'], '.', '');
        }
        return $number;
    }
}
