<?php

namespace kriss\bcmath;

abstract class BaseBC
{
    public $config = [
        'scale' => null, // 精度，为 null 会取 ini 中的配置，否则为 0
        'operateScale' => 18, // 操作过程中的计算精度
        'round' => false, // 是否四舍五入，当向上和舍位都为 false 时，默认为四舍五入
        'ceil' => false, // 是否向上取数，当有小数位时精度末位向上取
        'floor' => false, // 是否舍位，当有小数位时舍去精度之后的
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);

        if (!$this->config['ceil'] && !$this->config['floor']) {
            $this->config['round'] = true;
        }
        if (is_null($this->config['scale'])) {
            $this->config['scale'] = intval(ini_get('bcmath.scale')) ?: 0;
        }
    }

    /**
     * 获取对应精度的数值
     * @param $number
     * @return float|string
     */
    public function getScaleNumber($number)
    {
        $scale = $this->config['scale'];
        $operateScale = $this->config['operateScale'];

        if ($this->config['round']) {
            return round($number, $scale);
        }
        if ($this->config['ceil'] || $this->config['floor']) {
            // 通过截取字符串的形式处理而非先乘再除，是因为先乘会出现超过 operateScale 最大精度的情况
            $arr = explode('.', $number);
            $integer = $arr[0];
            if (count($arr) !== 2) {
                // 非小数
                return $integer;
            }
            $decimal = $arr[1];

            $decimalUsed = 0; // 需要使用的小数值
            $decimalLeft = $decimal; // 截断后剩余的小数值
            if ($scale > 0) {
                $decimalUsed = substr($decimal, 0, $scale);
                $decimalLeft = substr($decimal, $scale);
            }

            if (bccomp($decimalUsed, 0, $operateScale) === 0 && bccomp($decimalLeft, 0, $operateScale) === 0) {
                // 小数位数值均为 0 时
                return $integer;
            }

            $result = $integer;
            if (bccomp($decimalUsed, 0, $operateScale) === 1) {
                $result = $integer . '.' . $decimalUsed;
            }

            if ($this->config['floor'] || bccomp($decimalLeft, 0, $operateScale) === 0) {
                // 舍位 或 位数恰好
                return $result;
            }

            // 需要进位
            return bcadd($result, bcpow(0.1, $scale, $operateScale), $scale);
        }

        return $number;
    }
}
