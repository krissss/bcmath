<?php

namespace kriss\bcmath;

/**
 * @method BCS add(...$numbers)
 * @method BCS sub(...$numbers)
 * @method BCS mul(...$numbers)
 * @method BCS div(...$numbers)
 * @method BCS mod(...$numbers)
 * @method BCS pow(...$numbers) 只支持指数为整数的，若传入小数会自动 intval
 */
class BCS extends BaseBC
{
    /**
     * 允许使用的bc函数
     * @var array
     */
    public $bcEnables = ['bcadd', 'bcsub', 'bcmul', 'bcdiv', 'bcmod', 'bcpow'];
    /**
     * @var string|float
     */
    public $result;

    /**
     * {@inheritdoc}
     */
    public function __construct($number, $config = [])
    {
        parent::__construct($config);
        $this->result = $this->numberFormat($number);
    }

    /**
     * @param $number
     * @param $config
     * @return BCS
     */
    public static function create($number, $config = [])
    {
        return new static($number, $config);
    }

    public function __call($name, $arguments)
    {
        $bcName = 'bc' . $name;
        if (!in_array($bcName, $this->bcEnables)) {
            throw new \Exception("{$bcName} not in ::bcEnables");
        }
        foreach ($arguments as $number) {
            $number = $this->numberFormat($number);
            if ($bcName === 'bcpow') {
                $number = intval($number);
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
    public function getResult()
    {
        return $this->getScaleNumber($this->result);
    }

    /**
     * 获取平方根
     * @return string
     */
    public function getSqrt()
    {
        return bcsqrt($this->result, $this->config['scale']);
    }

    /**
     * 比较结果
     * @param $number
     * @return int
     */
    public function compare($number)
    {
        return bccomp($this->result, $this->numberFormat($number), $this->config['scale']);
    }

    /**
     * 是否相等
     * @param $number
     * @return bool
     */
    public function isEqual($number)
    {
        return $this->compare($number) === 0;
    }

    /**
     * 是否小于
     * @param $number
     * @return bool
     */
    public function isLessThan($number)
    {
        return $this->compare($number) === -1;
    }

    /**
     * 是否大于
     * @param $number
     * @return bool
     */
    public function isLargerThan($number)
    {
        return $this->compare($number) === 1;
    }

    /**
     * 格式化数字
     * @param $number
     * @return string
     */
    private function numberFormat($number): string
    {
        if (is_string($number) && strpos($number, 'E') === false && strpos($number, 'e') === false) {
            return $number;
        }
        return number_format($number, $this->config['operateScale']+1, '.', '');
    }
}
