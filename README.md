# Kriss BCMath

PHP bcmath quick write.

## Usage

```php
<?php
use kriss\bcmath\BCS;
use kriss\bcmath\BC;
use kriss\bcmath\BCParser;

$result = BCS::create(1.5, ['scale' => 2])->add(1.2)->mul(2)->sub(1.5)->getResult();
echo $result; // 3.9

$result = BC::create(['scale' => 2])->add(1.2, 1.3, 1.5, 1.8);
echo $result; // 5.8

$result = BCParser::create(['scale' => 4])->parse('5*3+3.5-1.8/7');
echo $result; // 18.2429
```

more example see `tests/phpunit`
