# lee/php-green-button

A Green Button Generator for PHP.

![PHP](https://github.com/peter279k/php-green-button/workflows/PHP/badge.svg)

## Requirements

* PHP 7.2+

## Installation

```bash
composer require lee/php-green-button
```

## Usage

Using following codes to generate a customer agreement information XML:

```php
<?php

use Carbon\Carbon;
use Lee\Green\Button\CustomerDataWriter;

$dateTimeString = Carbon::now(self::$timezone)->format('Y-m-d\TH:i:s\Z');
$options = [
    'title' => 'Green Button Customer Feed',
    'account_id' => 'Peter',
    'meter_form_number' => 'TD17234599',
    'end_device_serial_number' => '99123456',
];
$customerDataWriter = new CustomerDataWriter($options);
$customerDataWriter->setPublishedDateTime($dateTimeString);
$customerDataWriter->setUpdatedDateTime($dateTimeString);

$result = $customerDataWriter->createCustomerAccountData();

```

## Similar libraries

* https://github.com/peter279k/green-button-converter
* https://github.com/cew821/greenbutton

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
