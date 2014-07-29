FactoryMuffin
=============

[![Build Status](https://img.shields.io/travis/thephpleague/factory-muffin/master.svg?style=flat)](https://travis-ci.org/thephpleague/factory-muffin)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thephpleague/factory-muffin.svg?style=flat)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/factory-muffin.svg?style=flat)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/thephpleague/factory-muffin.svg?style=flat)](https://github.com/thephpleague/factory-muffin/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/league/factory-muffin.svg?style=flat)](https://packagist.org/packages/league/factory-muffin)

The goal of this Package is to enable the rapid creation of objects for the purpose of testing.

It's basically a "[factory\_girl](https://github.com/thoughtbot/factory_girl)", simplified for use with PHP.

## Install

Via Composer

```json
{
    "require": {
        "league/factory-muffin": "~2.0@dev"
    }
}
```

## Usage

To start with, we need to create some defintions.

In this example, we will create them in the `/tests/factories/all.php`:

```php
<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('Message', array(
    'user_id' => 'factory|User',
    'subject' => 'string',
    'message' => 'text',
    'phone_number' => 'integer|8',
    'created' => 'date|Ymd h:s',
    'slug' => 'call|makeSlug|string',
));

FactoryMuffin::define('User', array(
    'username' => 'string',
    'email' => 'email',
    'greeting' => RandomGreeting::get(),
    'four' => function() {
        return 2 + 2;
    },
));
```

You can then use these factories in your tests:

```php
<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

class TestUserModel extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
        FactoryMuffin::setSaveMethod('save'); // this is not required, but allows you to modify the method name
    }

    public function testSampleFactory()
    {
        $message = FactoryMuffin::create('Message');
        $this->assertInstanceOf('Message', $message);
        $this->assertInstanceOf('User', $message->user);
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete'); // this is not required, but allows you to modify the method name
        FactoryMuffin::deleteSaved();
    }
}
```

## Kinds of attribute supported

| Kind          | Option  | Description                                                                        | Example
| :-----------: | :-----: |:----------------------------------------------------------------------------------:| :----------------:|
| string        | length  | Random string of text                                                              | string|12         |
| email         | -       | Random email address                                                               | email             |
| text          | length  | Random body of text                                                                | text|100          |
| integer       | length  | Random integer/number                                                              | integer|10        |
| date          | format  | Generate date with specific format                                                 | date|d-M-Y        |
| factory       | model   | Will run ->create() on another model and return it's id                            | factory|User      |
| call          | method  | Allows you to call any static methods                                              | call|staticMethod |
| default       | string  | Any Kinds that are not reccognised will try and load from Faker, or return the text| creditCardDetails |


## Save Failures

If a model cannot be saved to the database, for example if it fails validation through a library like Ardent, a `League\FactoryMuffin\Exception\Save` will be raised.

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Zizaco Zizuini](https://github.com/Zizaco)
- [Scott Robertson](https://github.com/scottrobertson)
- [Graham Campbell](https://github.com/GrahamCampbell)
- [All Contributors](https://github.com/thephpleague/factory-muffin/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
