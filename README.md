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


## Installing

[PHP](https://php.net) 5.3+ and [Composer](https://getcomposer.org) are required.

In your composer.json, simply add `"league/factory-muffin": "~2.0@dev"` to your `"require-dev"` section:
```json
{
    "require-dev": {
        "league/factory-muffin": "~2.0@dev"
    }
}
```


## Upgrading

It maybe be useful for existing users to check out the [upgrade guide](UPGRADING.md).


## Usage

### Introduction

This is the usage guide for Factory Muffin 2.0. Within this guide, you will see "the `xyz` function can be called". You should assume that these functions should be called statically on the `League\FactoryMuffin\Facade` class. It should also be noted that you can see a real example at the end of the guide.

### The Facade

The facade class (`League\FactoryMuffin\Facade`) should always be your main point of entry for communicating with Factory Muffin. It will dynamically proxy static method calls to the underline factory instance. The other classes, including the factory class (`League\FactoryMuffin\Factory`), are not intended for direct public use.

### Factory Definitions

You can define model factories using the `define` function. You may call it like this: `League\FactoryMuffin\Facade::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))`. We have provided a nifty way for you to do this in your tests. PHPUnit provides a `setupBeforeClass` function. Within that function you can call `League\FactoryMuffin\Facade::loadFactories(__DIR__ . '/factories');`, and it will include all files in the factories folder. Within those php files, you can put your definitions (all your code that calls the define function). The `loadFactories` function will throw a `League\FactoryMuffin\Exceptions\DirectoryNotFoundException` exception if the directory you're loading is not found.

### Generators

| Generator     | Option  | Description                                                                      | Example
| :-----------: | :-----: | :------------------------------------------------------------------------------: | :------------------: |
| factory       | model   | Will run ->create() on another model and return it's id                          | factory|User         |
| call          | method  | Allows you to call any static methods                                            | call|staticMethod    |
| closure       | closure | Allows you to call pass a closure that will be called                            | function {return 1;} |
| default       |         | Any Generators that are not reccognised will load from Faker, or return the text | creditCardDetails    |

It may be useful for long standing users to checkout the generator changes from 1.6 to 2.0 in the [upgrade guide](UPGRADING.md).

### Creating And Seeding

This `create` function will create and save your model, and will also save anything you generate with the `Factory` generator too. If you want to create multiple instances, check out the seed `seed` function, which accepts an additional argument at the start which is the number of models to generate in the process. The `seed` function will affectively be calling the `create` function over and over. It should be noted that you can set a custom save function before you get going with the `setSaveMethod` function. Also, a reminder that the `instance` function is still available if you don't want database persistence.

You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\NoDefinedFactoryException` will be thrown if you try to create a model and you haven't defined a factory definition for it earlier.
* `League\FactoryMuffin\Exceptions\SaveFailedException` will be thrown if the save function on your model returns false.
* `League\FactoryMuffin\Exceptions\SaveMethodNotFoundException` will be thrown if the save function on your model does not exist.
* Any other exception thrown by your model while trying to create or save it.

There are 2 other helper functions available. You may call `saved` to return an array of all the saved objects. You may call `isSaved` with an instance of a model to check if it's saved.

### Deleting

You can delete all your saved models with the `deleteSaved` function. It should be noted that you can set a custom delete function before you get going with the `setDeleteMethod` function.

If one or more models cannot be deleted, a `League\FactoryMuffin\Exceptions\DeletingFailedException` will be raised after we have attempted to delete all the saved models. You may access each underline exception, in the order they were thrown during the whole process, with the `getExceptions` function which will return an array of exceptions. You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\DeleteFailedException` will be thrown if the save function on your model returns false.
* `League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException` will be thrown if the save function on your model does not exist.
* Any other exception thrown by your model while trying to delete it.

It's recommended that you call the `deleteSaved` function from PHPUnit's `tearDownAfterClass` function.

### Exceptions

Each exception is documented with the documentation for the functions that throw them.

You can see an diagram showing the exception hierarchy here:

![diagram](https://cloud.githubusercontent.com/assets/2829600/3790579/8fc52572-1b0d-11e4-96b1-7f0eac0dc10d.png)

### Real Examples

To start with, we need to create some defintions:
```php
# tests/factories/all.php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('Message', array(
    'user_id'      => 'factory|User',
    'subject'      => 'sentence',
    'message'      => 'text',
    'phone_number' => 'randomNumber|8',
    'created'      => 'date|Ymd h:s',
    'slug'         => 'call|makeSlug|word',
));

FactoryMuffin::define('User', array(
    'username' => 'firstNameMale',
    'email'    => 'email',
    'avatar'   => 'imageUrl|400;600',
    'greeting' => RandomGreeting::get(),
    'four'     => function() {
        return 2 + 2;
    },
));
```

You can then use these factories in your tests:
```php
# tests/TestUserModel.php

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


## Contributing

Please check out our [contribution guidelines](CONTRIBUTING.md) for details.


## Credits

Factory Muffin is based on [Zizaco Zizuini](https://github.com/Zizaco)'s original work on "Factory Muff", and is currently maintained by [Scott Robertson](https://github.com/scottrobertson) and [Graham Campbell](https://github.com/GrahamCampbell). Thank you to all our wonderful [contributors](https://github.com/thephpleague/factory-muffin/contributors) too.


## License

Factory Muffin is licensed under [The MIT License (MIT)](LICENSE).
