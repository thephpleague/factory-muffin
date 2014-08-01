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

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('Message', array(
    'user_id' => 'factory|User',
    'subject' => 'sentence',
    'message' => 'text',
    'phone_number' => 'randomNumber|8',
    'created' => 'date|Ymd h:s',
    'slug' => 'call|makeSlug|word',
));

FactoryMuffin::define('User', array(
    'username' => 'firstNameMale',
    'email' => 'email',
    'avatar' => 'imageUrl|400;600',
    'greeting' => RandomGreeting::get(),
    'four' => function() {
        return 2 + 2;
    },
));
```

You can then use these factories in your tests:

```php
<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

class TestUserModel extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::setFakerLocale('en_EN'); // optional step
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
        FactoryMuffin::setSaveMethod('save'); // optional step
    }

    public function testSampleFactory()
    {
        $message = FactoryMuffin::create('Message');
        $this->assertInstanceOf('Message', $message);
        $this->assertInstanceOf('User', $message->user);
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete'); // optional step
        FactoryMuffin::deleteSaved();
    }
}
```

## Generators

| Generator     | Option  | Description                                                                      | Example
| :-----------: | :-----: | :------------------------------------------------------------------------------: | :------------------: |
| factory       | model   | Will run ->create() on another model and return it's id                          | factory|User         |
| call          | method  | Allows you to call any static methods                                            | call|staticMethod    |
| closure       | closure | Allows you to call pass a closure that will be called                            | function {return 1;} |
| default       |         | Any Generators that are not reccognised will load from Faker, or return the text | creditCardDetails    |


## Save Failures

If a model cannot be saved to the database, for example if it fails validation through a library like Ardent, a `League\FactoryMuffin\Exceptions\SaveFailedException` will be raised.

## Delete Failures

If one or more models cannot be deleted, a `League\FactoryMuffin\Exceptions\DeletingFailedException` will be raised after we have attempted to delete all the saved models. You may access each underline exception, in the order they were thrown during the whole process, with the `getExceptions` method which will return an array of exceptions.

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
