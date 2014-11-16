# Lock

The standard PHP `flock` function is difficult to test against. This
library provides a modern, object-oriented wrapper to `flock`.

## Installation

Add the following to your `composer.json`:

```json
{
    "require": {
        "benconstable/lock": "~1.0"
    }
}
```

## Usage

```php
<?php

$lock = new BenConstable\Lock\Lock('/path/to/file.txt');

try {
    $lock->aquire();
    // Lock successful...
} catch (BenConstable\Lock\Exception\LockException $e) {
    // Lock failed...
}
```

## Development & contribution

To develop and/or contribute to this library, you must add tests for your code.
Tests are built with Phpspec. To run them:

```sh
$ git clone https://github.com/BenConstable/lock && cd lock/
$ composer install
$ bin/phpspec run
```
