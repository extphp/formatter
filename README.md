# Formatter

[![Build Status](https://travis-ci.org/extphp/formatter.svg?branch=master)](https://travis-ci.org/extphp/formatter)
[![Latest Stable Version](https://poser.pugx.org/extphp/formatter/v/stable)](https://packagist.org/packages/extphp/formatter)
[![License](https://poser.pugx.org/extphp/formatter/license)](https://packagist.org/packages/extphp/formatter)
[![Total Downloads](https://poser.pugx.org/extphp/formatter/downloads)](https://packagist.org/packages/extphp/formatter)


A great way to quickly format a given set of variables.

This project was highly inspired by the Laravel Validator.

# Usage

```php
<?php

use ExtPHP\Formatter\Formatter;

$data = [
    'id'            => '153',
    'first_name'    => 'mary j.',
    'last_name'     => 'blige',
    'female'        => 1,
    'married'       => 'false',
    'cash'          => '2761,32'
];

$rules = [
    'id'            => 'cast:int',
    'first_name'    => 'ucwords',
    'last_name'     => 'strtoupper',
    'female'        => 'cast:ebool',
    'married'       => 'cast:ebool',
    'cash'          => 'str_replace:\,,.|cast:float|number_format:4,\,,.'
];

$formatter = new Formatter($data, $rules);

$formatted = $formatter->format();

var_dump($formatted);
```
```
// the above code will output
array(6) {
  ["id"]=>
  int(153)
  ["first_name"]=>
  string(7) "Mary J."
  ["last_name"]=>
  string(5) "BLIGE"
  ["female"]=>
  bool(true)
  ["married"]=>
  bool(false)
  ["cash"]=>
  string(10) "2.761,3200"
}
```