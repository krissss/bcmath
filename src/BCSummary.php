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
        $result = [];
        $lastOne = array_slice($proportions, count($proportions) - 1, 1, true);
        array_pop($proportions);
        foreach ($proportions as $index => $proportion) {
            $result[$index] = BCS::create($proportion, $this->config)
                ->div($sum)
                ->mul($total)
                ->getResult();
        }
        $sumBefore = BC::create($this->config)->add(...array_values($result));
        $result[key($lastOne)] = BCS::create($total, $this->config)->sub($sumBefore)->getResult();
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
