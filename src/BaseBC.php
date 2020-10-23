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
        if ($this->config['round']) {
            return round($number, $scale);
        }
        if ($this->config['ceil']) {
            $ratio = pow(10, $scale);
            $number = ceil(bcmul($number, $ratio, $this->config['operateScale']));
            return bcdiv($number, $ratio, $scale);
        }
        if ($this->config['floor']) {
            $scale += 1;
            return substr(sprintf("%.{$scale}f", $number), 0, -1);
        }

        return $number;
    }
}
