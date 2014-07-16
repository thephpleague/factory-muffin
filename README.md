# FactoryMuffin

[![Build Status](https://img.shields.io/travis/thephpleague/factory-muffin/master.svg?style=flat)](https://travis-ci.org/thephpleague/factory-muffin)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thephpleague/factory-muffin.svg?style=flat)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/factory-muffin.svg?style=flat)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/thephpleague/factory-muffin.svg?style=flat)](https://github.com/thephpleague/factory-muffin/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/league/factory-muffin.svg?style=flat)](https://packagist.org/packages/league/factory-muffin)

The goal of this Package is to enable the rapid creation of objects for the purpose of testing. Basically a "[factory\_girl](https://github.com/thoughtbot/factory_girl)" simplified for use with PHP.

## Install

Via Composer

```json
{
    "require": {
        "league/factory-muffin": "~2.0"
    }
}
```

## Usage

Declare a __public static__ array called __$factory__, or a __public static__ method called factory() in your model. This should contain the kind of values you want for the attributes.

Example:
```php
class Message
{
    public static $factory = array(
        'user_id' => 'factory|User', // This will create a new User object
        'subject' => 'string',
        'message' => 'text',
        'phone_number' => 'integer|8',
        'created' => 'date|Ymd h:s',
        'slug' => 'call|makeSlug|string',
    );

    public static function makeSlug($factory_muff_generated_string)
    {
        $base = strtolower($factory_muff_generated_string);
        return preg_replace('|[^a-z0-9]+|', '-', $base);
    }
}

class User
{
    public static $factory = array(
        'username' => 'string',
        'email' => 'email'
    );
}
```

You can also declare a static method named `factory()` on your model. This allows more flexibility as PHP has restrictions on what you can use as array values when defining properties.

Example:
```php
class Message
{
    public static factory()
    {
        return array(
            'user_id' => 'factory|User',
            // Not possible when defined as a static array
            'greeting' => RandomGreeting::get(),
            // Closures will be called automatically
            'four' => function() {
                return 2 + 2;
            },
        );
    }
```

To create model instances do the following:
```php
<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

class TestUserModel extends PHPUnit_Framework_TestCase
{
    public function testSampleFactory()
    {
        $message = FactoryMuffin::create('Message');
        $this->assertInstanceOf('Message', $message);
        $this->assertInstanceOf('User', $message->user);
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

If a model cannot be saved to the database, for example if it fails validation through a library like Ardent, a League\FactoryMuffin\Exception\Save will be raised.

## Testing

```bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/factory-muffin/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Zizaco Zizuini](https://github.com/Zizaco)
- [All Contributors](https://github.com/thephpleague/factory-muffin/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/factory-muffin/blob/master/LICENSE) for more information.
