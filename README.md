Factory Muffin
==============

[![Build Status](https://img.shields.io/travis/thephpleague/factory-muffin.svg?style=flat-square)](https://travis-ci.org/thephpleague/factory-muffin)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thephpleague/factory-muffin.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/factory-muffin.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Latest Version](https://img.shields.io/github/release/thephpleague/factory-muffin.svg?style=flat-square)](https://github.com/thephpleague/factory-muffin/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/league/factory-muffin.svg?style=flat-square)](https://packagist.org/packages/league/factory-muffin)

The goal of this package is to enable the rapid creation of objects for the purpose of testing.

It's basically a "[factory\_girl](https://github.com/thoughtbot/factory_girl)", simplified for use with PHP.


## Installing

[PHP](https://php.net) 5.3+ and [Composer](https://getcomposer.org) are required.

In your composer.json, simply add `"league/factory-muffin": "~2.1"` to your `"require-dev"` section:
```json
{
    "require-dev": {
        "league/factory-muffin": "~2.1"
    }
}
```


## Upgrading

It maybe be useful for existing users to check out the [upgrade guide](UPGRADING.md).


## Usage

### Introduction

This is the usage guide for Factory Muffin 2.1. Within this guide, you will see "the `xyz` function can be called". You should assume that these functions should be called statically on the `League\FactoryMuffin\Facade` class. It should also be noted that you can see a real example at the end of the guide.

### The Facade

The facade class (`League\FactoryMuffin\Facade`) should always be your main point of entry for communicating with Factory Muffin. It will dynamically proxy static method calls to the underlying factory instance. The other classes, including the factory class (`League\FactoryMuffin\Factory`), are not intended for direct public use. The facade additionally provides a `reset` method that will re-create the underlying factory instance. Also, note that all public methods that would have returned void, return the factory instance in order to support method chaining.

### Factory Definitions

You can define model factories using the `define` function. You may call it like this: `League\FactoryMuffin\Facade::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))`, where `foo` is the name of the attribute you want set on your model, and `bar` describes how you wish to generate the attribute. Please see the generators section for more information on how this works.

You can also define multiple different factory definitions for your models. You can do this by prefixing the model class name with your "group" followed by a colon. This results in you defining your model like this: `League\FactoryMuffin\Facade::define('myGroup:Fully\Qualifed\ModelName', array('foo' => 'bar'))`. You don't have to entirely define your model here because we will first look for a definition without the group prefix, then apply your group definition on top of that definition, overriding attribute definitions where required.

We have provided a nifty way for you to do this in your tests. PHPUnit provides a `setupBeforeClass` function. Within that function you can call `League\FactoryMuffin\Facade::loadFactories(__DIR__ . '/factories');`, and it will include all files in the factories folder. Within those php files, you can put your definitions (all your code that calls the define function). The `loadFactories` function will throw a `League\FactoryMuffin\Exceptions\DirectoryNotFoundException` exception if the directory you're loading is not found.

### Creation/Instantiation Callbacks

You may optionally specify a callback to be executed on model creation/instantiation as a third parameter when defining a definition. We will pass your model instance as the first parameter to the closure if you specify one. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function was used), and `false` if it's not being persisted (the instance function was used). We're using the `isPendingOrSaved` function under the hood here. Note that if you specify a callback and use the create function, we will try to save your model to the database both before and after we execute the callback.

### Generators

#### Generic

The generic generator will be the generator you use the most. It will communicate with the faker library in order to generate your attribute.

##### Example 1

There is a simple example of setting a few different attributes.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'foo'    => 'word',          // Set the foo attribute to a random word
    'name'   => 'firstNameMale', // Set the name attribute to a random male first name
    'email'  => 'email',         // Set the email attribute to a random email address
    'body'   => 'text',          // Set the body attribute to a random string of text
    'slogan' => 'sentence',      // Set the slogan attribute to a random sentence
));
```

##### Example 2

This will set the `age` attribute to a random number between 20 and 40. Note how we're using the `;` here to pass multiple arguments to the faker method.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'age' => 'numberBetween|20;40',
));
```

##### Example 3

This will set the `name` attribute to a random female first name. It will ensure that it is unique between all your generated models.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'name' => 'unique:firstNameFemale',
));
```

##### Example 4

This will set the `profile_pic` attribute to a random image url of dimensions 400 by 400. Because we've added the optional flag at the start, not all the generated models will have an image url set; sometimes we will return null.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'profile_pic' => 'optional:imageUrl|400;400',
));
```

##### More

Check out the [faker library](https://github.com/fzaninotto/Faker) itself to see all the available methods. There are far too many to cover in the documentation here, and far too many for them to cover in their documentation too. Note that you may access the underlying faker instance using the `getFaker` method.

#### Factory

The factory generator can be useful for setting up relationships between models. The factory generator will return the model id of the model you ask it to generate.

##### Example 1

When we create a Foo object, we will find that the Bar object will been generated and saved too, and it's id will be assigned to the `bar_id` attribute of the Foo model.
```php
League\FactoryMuffin\Facade::define('Foo', array(
    'bar_id' => 'factory|Bar'
));

League\FactoryMuffin\Facade::define('Bar', array(
    'baz' => 'date|Y-m-d'
));
```

#### Call

The call generator allows you to generate attributes by calling **static** methods on your models.

##### Example 1

This will set the `foo` attribute to whatever calling `MyModel::exampleMethod()` returns.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'foo' => 'call|exampleMethod',
));
```

##### Example 2

This will set the `bar` attribute to whatever calling `MyModel::anotherMethod('hello')` returns.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'bar' => 'call|anotherMethod|hello',
));
```

##### Example 3

This will set the `baz` attribute to whatever calling the `exampleMethod` method on the `OtherModel` after we generate and save it.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'baz' => 'call|exampleMethod|factory|OtherModel',
));

League\FactoryMuffin\Facade::define('OtherModel', array(
    'example' => 'boolean',
));
```

#### Closure

The closure generator can be used if you want a more custom solution. Whatever you return from the closure you write will be set as the attribute. Note that we pass an instance of your model as the first parameter of the closure to give you even more flexibility to modify it as you wish. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function was used), and `false` if it's not being persisted (the instance function was used). We're using the `isPendingOrSaved` function under the hood here.

##### Example 1

As you can see from this example, the ability to use a closure to generate attributes can be so useful and flexible. Here we use it to generate a slug based on the initially randomly generated 5 word long title.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'title' => 'sentence|5',
    'slug' => function ($object, $saved) {
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $object->title);
        $slug = strtolower(trim($slug, '-'));
        $slug = preg_replace("/[\/_|+ -]+/", '-', $slug);

        return $slug;
    },
));
```

### Creating And Seeding

The `create` function will create and save your model, and will also save anything you generate with the `Factory` generator too. If you want to create multiple instances, check out the seed `seed` function, which accepts an additional argument at the start which is the number of models to generate in the process. The `seed` function will effectively be calling the `create` function over and over. It should be noted that you can set a custom save function before you get going with the `setSaveMethod` function. Also, a reminder that the `instance` function is still available if you don't want database persistence.

You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\ModelNotFoundException` will be thrown if the model class defined is not found.
* `League\FactoryMuffin\Exceptions\NoDefinedFactoryException` will be thrown if you try to create a model and you haven't defined a factory definition for it earlier.
* `League\FactoryMuffin\Exceptions\SaveFailedException` will be thrown if the save function on your model returns false.
* `League\FactoryMuffin\Exceptions\SaveMethodNotFoundException` will be thrown if the save function on your model does not exist.
* Any other exception thrown by your model while trying to create or save it.

There are 5 other helper functions available:
* You may call `pending` to return an array of all the objects waiting to be saved.
* You may call `isPending` with an instance of a model to check if will be saved.
* You may call `saved` to return an array of all the saved objects.
* You may call `isSaved` with an instance of a model to check if it's saved.
* You may call `isPendingOrSaved` with an instance of a model to check if it will be saved, or is already saved.

### Deleting

You can delete all your saved models with the `deleteSaved` function. It should be noted that you can set a custom delete function before you get going with the `setDeleteMethod` function.

If one or more models cannot be deleted, a `League\FactoryMuffin\Exceptions\DeletingFailedException` will be raised after we have attempted to delete all the saved models. You may access each underlying exception, in the order they were thrown during the whole process, with the `getExceptions` function which will return an array of exceptions. You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\DeleteFailedException` will be thrown if the delete function on your model returns false.
* `League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException` will be thrown if the delete function on your model does not exist.
* Any other exception thrown by your model while trying to delete it.

It's recommended that you call the `deleteSaved` function from PHPUnit's `tearDownAfterClass` function.

### Additional Customisation

You may call `League\FactoryMuffin\Facade::setCustomMaker(function ($class) { return new $class('example'); })` in order to register a closure to customise the model creation. This will be used internally by Factory Muffin rather than us just straight up using `new $class()`.

You may call  `League\FactoryMuffin\Facade::setCustomSetter(function ($object, $name, $value) { $object->set($name, $value); })` in order to register a closure to customise the attribute setting. This will be used internally by Factory Muffin when setting your attributes rather than us just using `$object->$name = $value`.

You may call `League\FactoryMuffin\Facade::setCustomSaver(function ($object) { $object->save(); $object->push(); return true; })` in order to save your object in a custom way. This will be used internally by Factory Muffin when saving your object rather than us just using `$object->save()`.

You may call `League\FactoryMuffin\Facade::setCustomDeleter(function ($object) { $object->forceDelete(); return true; })` in order to delete your object in a custom way. This will be used internally by Factory Muffin when deleting your object rather than us just using `$object->delete()`.

### Exceptions

Each exception is documented with the documentation for the functions that throw them.

You can see a diagram showing the exception hierarchy here:

![diagram](https://cloud.githubusercontent.com/assets/2829600/3857444/5dba7f1a-1f03-11e4-928a-5b24ca0f1e26.png)

### Real Examples

To start with, we need to create some definitions:
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
), function ($object, $saved) {
    // we're taking advantage of the callback functionality here
    $object->message .= '!';
});

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
        // note that method chaining is supported
        FactoryMuffin::setFakerLocale('en_EN')->setSaveMethod('save'); // optional step
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
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

### Further Information

If you want more information, the following resources are available to you:
  * Generated api docs are available [here](http://factory-muffin.thephpleague.com/api/).
  * [Philip Brown's](https://github.com/philipbrown) article is available [here](http://culttt.com/2013/05/27/laravel-4-fixture-replacement-with-factorymuff/).
  * Our [test suite](tests) may also be useful to you.


## Contributing

Please check out our [contribution guidelines](CONTRIBUTING.md) for details.


## Credits

Factory Muffin is based on [Zizaco Zizuini](https://github.com/Zizaco)'s original work on "Factory Muff", and is currently maintained by [Scott Robertson](https://github.com/scottrobertson) and [Graham Campbell](https://github.com/GrahamCampbell). Thank you to all our wonderful [contributors](https://github.com/thephpleague/factory-muffin/contributors) too.


## License

Factory Muffin is licensed under [The MIT License (MIT)](LICENSE).
