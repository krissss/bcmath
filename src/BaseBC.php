<?php

namespace kriss\bcmath;

abstract class BaseBC
{
    /**
     * 精度
     * null 会取 ini 中的配置，否则为 0
     * @see setScale()
     * @see getScale()
     * @var null
     */
    public $scale = null;
    /**
     * 是否四舍五入
     * @var bool
     */
    public $rounded = true;
    /**
     * 操作过程中的计算精度
     * @var int
     */
    public $operateScale = 18;

    public function __construct($config = [])
    {
        foreach ($config as $name => $value) {
            $this->{$name} = $value;
        }
        if (is_null($this->scale)) {
            $this->scale = intval(ini_get('bcmath.scale')) ?: 0;
        }
    }

    /**
     * 获取对应精度的数值
     * @param $number
     * @return bool|float|string
     */
    public function getScaleNumber($number)
    {
        $scale = $this->scale;
        if ($this->rounded) {
            return round($number, $scale);
        }
        $scale += 1;
        return substr(sprintf("%.{$scale}f", $number), 0, -1);
    }
}
