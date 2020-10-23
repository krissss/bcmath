<?php

namespace kriss\bcmath;

class BCParser extends BaseBC
{
    public static function create($config = [])
    {
        return new static($config);
    }

    public function parse($formula, $values = [])
    {
        $formula = str_replace(' ', '', "({$formula})");
        foreach ($values as $key => $value) {
            $formula = str_replace("{{$key}}", $value, $formula);
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
                    switch ($matches[2]) {
                        case '+':
                            $result = bcadd($matches[1], $matches[3], $opScale);
                            break;
                        case '-':
                            $result = bcsub($matches[1], $matches[3], $opScale);
                            break;
                        case '*':
                            $result = bcmul($matches[1], $matches[3], $opScale);
                            break;
                        case '/':
                            $result = bcdiv($matches[1], $matches[3], $opScale);
                            break;
                        case '%':
                            $result = bcmod($matches[1], $matches[3]);
                            break;
                        case '^':
                            $result = bcpow($matches[1], $matches[3], $opScale);
                            break;
                        default:
                            $result = 0;
                    }
                    $singleFormula[1] = str_replace($matches[0], $result, $singleFormula[1]);
                }
            }
            $formula = str_replace($singleFormula[0], $singleFormula[1], $formula);
        }
        return $this->getScaleNumber($formula);
    }
}
