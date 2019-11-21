# Kriss BCMath

PHP bcmath quick write.

# Install

```
composer require kriss/bcmath -vvv
```

## Usage

```php
<?php
use kriss\bcmath\BCS;
use kriss\bcmath\BC;
use kriss\bcmath\BCParser;
use kriss\bcmath\BCSummary;

$result = BCS::create(1.5, ['scale' => 2])->add(1.2)->mul(2)->sub(1.5)->getResult();
echo $result; // 3.9

$result = BC::create(['scale' => 2])->add(1.2, 1.3, 1.5, 1.8);
echo $result; // 5.8

$result = BCParser::create(['scale' => 4])->parse('5*3+3.5-1.8/7');
echo $result; // 18.2429

$result = BCSummary::create(['scale' => 4])->average(18, ['A' => 1, 'B' => 2, 'C' => 3]);
echo $result; // ['A' => 3, 'B' => 6, 'C' => 9]

$result = BCSummary::create(['scale' => 2])->upgrade(18, 36, 100) . '%';
echo $result; // 100%
```

more example see `tests/phpunit`
