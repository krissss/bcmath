<?php

namespace kriss\bcmath;

class BCSummary extends BaseBC
{
    /**
     * @param $config
     * @return self
     */
    public static function create($config = [])
    {
        return new static($config);
    }

    /**
     * 平均分配
     * @param $total float 总数
     * @param $proportions array 占比
     * @return array
     */
    public function average($total, $proportions)
    {
        $sum = BC::create(['scale' => $this->operateScale])->add(...array_values($proportions));
        if (BCS::create($sum, ['scale' => $this->operateScale])->isEqual(0)) {
            return array_map(function () {
                return 0;
            }, $proportions);
        }
        $result = [];
        $lastOne = array_slice($proportions, count($proportions) - 1, 1, true);
        array_pop($proportions);
        foreach ($proportions as $index => $proportion) {
            $result[$index] = BCS::create($proportion, ['scale' => $this->scale])->div($sum)->mul($total)->getResult();
        }
        $sumBefore = BC::create(['scale' => $this->scale])->add(...array_values($result));
        $result[key($lastOne)] = BCS::create($total, ['scale' => $this->scale, 'rounded' => $this->rounded])->sub($sumBefore)->getResult();
        return $result;
    }

    /**
     * 新旧增长率
     * @param $old
     * @param $new
     * @param int $multi 结果比例
     * @return float
     */
    public function upgrade($old, $new, $multi = 1)
    {
        if (BCS::create($old, ['scale' => $this->operateScale])->isEqual(0)) {
            return BCS::create($new, ['scale' => $this->operateScale])->isEqual(0) ? 0 : 1;
        }
        return BCS::create($new, ['scale' => $this->scale])->sub($old)->div($old)->mul($multi)->getResult();
    }
}