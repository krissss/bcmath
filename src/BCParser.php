<?php

namespace kriss\bcmath;

class BCParser extends BaseBC
{
    public static function create(array $config = []): static
    {
        return new static($config);
    }

    /**
     * @param string $formula
     * @param array<string, float|string|int|null> $values
     * @return float|string
     */
    public function parse(string $formula, array $values = []): float|string
    {
        $formula = str_replace(' ', '', "({$formula})");
        foreach ($values as $key => $value) {
            $formula = str_replace("{{$key}}", (string)$value, $formula);
        }
        // 替换未知的参数为 0
        $formula = preg_replace("/\{[^{}]+\}/", '0', $formula);
        // 替换 -5^2 为 -(5^2)
        $formula = preg_replace('/\-(\d+(\.\d+)?\^\d+(\.\d+)?)/', '-(${1})', $formula);

        $operations = [
            '\^', // 指数
            '\*|\/|\%', // 乘法、除法、取余
            '[\+\-]', // 加法、减法
        ];
        $operand = '(?:(?<=[^0-9\.,]|^)[+-])?[0-9\.,]+';
        $opScale = $this->config['operateScale'];
        while (preg_match('/\(([^\)\(]+)\)/', $formula, $singleFormula)) {
            foreach ($operations as $operation) {
                while (preg_match("/({$operand})({$operation})({$operand})/", $singleFormula[1], $matches)) {
                    $result = match ($matches[2]) {
                        '+' => BC::create(['scale' => $opScale])->add($matches[1], $matches[3]),
                        '-' => BC::create(['scale' => $opScale])->sub($matches[1], $matches[3]),
                        '*' => BC::create(['scale' => $opScale])->mul($matches[1], $matches[3]),
                        '/' => BC::create(['scale' => $opScale])->div($matches[1], $matches[3]),
                        '%' => BC::create(['scale' => $opScale])->mod($matches[1], $matches[3]),
                        '^' => BC::create(['scale' => $opScale])->pow($matches[1], $matches[3]),
                        default => '0',
                    };
                    $singleFormula[1] = str_replace($matches[0], $result, $singleFormula[1]);
                }
            }
            $formula = str_replace($singleFormula[0], $singleFormula[1], $formula);
        }
        if (!is_numeric($formula)) {
            throw new \Exception('formula error');
        }

        return $this->getScaleNumber($formula);
    }
}
