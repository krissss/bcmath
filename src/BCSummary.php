<?php

namespace kriss\bcmath;

class BCSummary extends BaseBC
{
    /**
     * @param array $config
     * @return static
     */
    public static function create(array $config = []): static
    {
        return new static($config);
    }

    /**
     * 平均分配
     * @param float|string|int|null $total 总数
     * @param array<float|string|int|null> $proportions 占比
     * @return array<float|string>
     */
    public function average(float|string|int|null $total, array $proportions): array
    {
        // 操作过程中的替换精度为操作精度
        $operateConfig = array_merge($this->config, ['scale' => $this->config['operateScale']]);

        $sum = BC::create($operateConfig)->add(...array_values($proportions));
        if (BCS::create($sum, $operateConfig)->isEqual(0)) {
            return array_map(static fn() => 0, $proportions);
        }
        
        $keys = array_keys($proportions);
        $lastKey = end($keys);
        reset($keys);
        
        // ceil 模式：从前往后分配，确保不超过剩余总数，最后一个承担差额
        if ($this->config['ceil']) {
            $result = [];
            $remaining = $total;
            $totalPositive = BCS::create($total, $operateConfig)->isLargerThan(0);
            
            foreach ($proportions as $index => $proportion) {
                if ($index === $lastKey) {
                    // 最后一个元素取剩余值
                    $result[$index] = $this->getScaleNumber($remaining);
                } else {
                    // 计算当前元素的理论值
                    $value = BCS::create($proportion, $operateConfig)
                        ->div($sum)
                        ->mul($total)
                        ->getResult();
                    $scaledValue = $this->getScaleNumber($value);
                    
                    // 确保不超过剩余总数
                    if ($totalPositive) {
                        if (BCS::create($scaledValue, $operateConfig)->isLargerThan($remaining)) {
                            $scaledValue = $this->getScaleNumber($remaining);
                        }
                    } else {
                        if (BCS::create($scaledValue, $operateConfig)->isLessThan($remaining)) {
                            $scaledValue = $this->getScaleNumber($remaining);
                        }
                    }
                    
                    $result[$index] = $scaledValue;
                    $remaining = BCS::create($remaining, $operateConfig)->sub($scaledValue)->getResult();
                }
            }
            return $result;
        }
        
        // floor 和 round 模式：差额给最后一个元素
        $result = [];
        foreach ($proportions as $index => $proportion) {
            if ($index === $lastKey) {
                continue;
            }
            $result[$index] = BCS::create($proportion, $this->config)
                ->div($sum)
                ->mul($total)
                ->getResult();
        }
        
        // 最后一个元素 = total - 前面所有元素的和
        $sumBefore = BC::create($this->config)->add(...array_values($result));
        $result[$lastKey] = BCS::create($total, $this->config)->sub($sumBefore)->getResult();
        
        return $result;
    }

    /**
     * 新旧增长率
     * @param float|string|int|null $old
     * @param float|string|int|null $new
     * @param int $multi 结果比例
     * @return float|string
     */
    public function upgrade(float|string|int|null $old, float|string|int|null $new, int $multi = 1): float|string
    {
        // 操作过程中的替换精度为操作精度
        $operateConfig = array_merge($this->config, ['scale' => $this->config['operateScale']]);

        if (BCS::create($old, $operateConfig)->isEqual(0)) {
            return BCS::create($new, $this->config)->isEqual(0) ? 0 : 1;
        }
        return BCS::create($new, $this->config)
            ->sub($old)
            ->div($old)
            ->mul($multi)
            ->getResult();
    }
}
