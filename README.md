Factory Muffin 3.0
==================

[![StyleCI Status](https://styleci.io/repos/7930464/shield)](https://styleci.io/repos/7930464)
[![Build Status](https://img.shields.io/travis/thephpleague/factory-muffin.svg?style=flat-square)](https://travis-ci.org/thephpleague/factory-muffin)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thephpleague/factory-muffin.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/factory-muffin.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Latest Version](https://img.shields.io/github/release/thephpleague/factory-muffin.svg?style=flat-square)](https://github.com/thephpleague/factory-muffin/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/league/factory-muffin.svg?style=flat-square)](https://packagist.org/packages/league/factory-muffin)

The goal of this package is to enable the rapid creation of objects for the purpose of testing.

It's basically a "[factory\_girl](https://github.com/thoughtbot/factory_girl)", simplified for use with PHP.


## Installing

[PHP](https://php.net) 5.4+ and [Composer](https://getcomposer.org) are required.

In your composer.json, simply add `"league/factory-muffin": "^3.0"` to your `"require-dev"` section:
```json
{
    "require-dev": {
        "league/factory-muffin": "^3.0"
    }
}
```

[Faker](https://github.com/fzaninotto/Faker) support is provided by [Factory Muffin Faker](https://github.com/thephpleague/factory-muffin-faker). If you want to enable faker support, then you need to add `"league/factory-muffin-faker": "^2.0"` too:
```json
{
    "require-dev": {
        "league/factory-muffin": "^3.0",
        "league/factory-muffin-faker": "^2.0"
    }
}
```


## Upgrading

It maybe be useful for existing users to check out the [upgrade guide](UPGRADING.md).


## Usage

### Introduction

This is the usage guide for Factory Muffin 3.0. Within this guide, you will see "the `xyz` function can be called". You should assume that these functions should be called on an instance of `League\FactoryMuffin\FactoryMuffin`; you should keep track of this instance yourself, and you can of course have multiple instances of this class for maximum flexibility. For simplicities sake, many of our examples include a `$fm` variable. This variable will actually be made available when files are required using the `loadFactories` function.

### Model Definitions

You can define model factories using the `define` function. You may call it like this: `$fm->define('Fully\Qualifed\ModelName')->addDefinitions('foo', 'bar')`, where `foo` is the name of the attribute you want set on your model, and `bar` describes how you wish to generate the attribute. You may also define multiple attributes at once like this: `$fm->define('Fully\Qualifed\ModelName')->setDefinitions('foo', 'bar')`. Note that both functions append to the internal attributes definition array rather than replacing it. Please see the generators section for more information on how this works.

You can also define multiple different model definitions for your models. You can do this by prefixing the model class name with your "group" followed by a colon. This results in you defining your model like this: `$fm->define('myGroup:Fully\Qualifed\ModelName')->addDefinitions('bar', 'baz')`. You don't have to entirely define your model here because we will first look for a definition without the group prefix, then apply your group definition on top of that definition, overriding attribute definitions where required.

We have provided a nifty way for you to do this in your tests. PHPUnit provides a `setupBeforeClass` function. Within that function you can call `$fm->loadFactories(__DIR__ . '/factories');`, and it will include all files in the factories folder. Within those php files, you can put your definitions (all your code that calls the define function). The `loadFactories` function will throw a `League\FactoryMuffin\Exceptions\DirectoryNotFoundException` exception if the directory you're loading is not found.

### Model Hydration

If your model classes use public setter methods or public properties, FactoryMuffin can automatically hydrate your models using its default hydration strategy `League\FactoryMuffin\HydrationStrategies\PublicSetterHydrationStrategy`.

However, if your models use completely different ways to access their properties, you can implement your own hydration strategies and register them per model. 
You simply need to implement the interface `League\FactoryMuffin\HydrationStrategies\HydrationStrategyInterface` and register an instance of your strategy with the class name of your model `$fm->setHydrationStrategy('Fully\Qualified\ModelName', $my_custom_strategy);`.

For convenience, FactoryMuffin already ships with an alternative hydration strategy called `League\FactoryMuffin\HydrationStrategies\ReflectionHydrationStrategy`. 
As the name suggests, this strategy uses reflection to set the attributes directly, which allows it to set `private/protected` properties. You simply need to register an instance of this strategy with every model that you want to be hydrated by this strategy.

### Creation/Instantiation Callbacks

You may optionally specify a callback to be executed on model creation/instantiation as a third parameter when defining a definition. We will pass your model instance as the first parameter to the closure if you specify one. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function was used), and `false` if it's not being persisted (the instance function was used). We're using the `isPendingOrSaved` function under the hood here. Note that if you specify a callback and use the create function, we will try to save your model to the database both before and after we execute the callback.

### Generators

#### Callable

The callable generator can be used if you want a more custom solution. Whatever you return from your closure, or valid callable, will be set as the attribute. Note that we pass an instance of your model as the first parameter of the closure/callable to give you even more flexibility to modify it as you wish. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function was used), and `false` if it's not being persisted (the instance function was used). We're using the `isPendingOrSaved` function under the hood here. In the following examples, we will go through using a closure, or callable, and then how to use faker to generate attributes.

##### Example 1

As you can see from this example, the ability to use a closure to generate attributes can be so useful and flexible. Here we use it to generate a slug based on the initially randomly generated 5 word long title.
```php
$fm->define('MyModel')->setDefinitions([
    'title' => Faker::sentence(5),
    'slug' => function ($object, $saved) {
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $object->title);
        $slug = strtolower(trim($slug, '-'));
        $slug = preg_replace("/[\/_|+ -]+/", '-', $slug);

        return $slug;
    },
]);
```

##### Example 2

This will set the `foo` attribute to whatever calling `MyModel::exampleMethod($object, $saved)` returns.
```php
$fm->define('MyModel')->setDefinitions([
    'foo' => 'MyModel::exampleMethod',
]);
```

##### Example 3

There is a simple example of setting a few different attributes using our faker wrapper.
```php
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('MyModel')->setDefinitions([
    'foo'    => Faker::word(),          // Set the foo attribute to a random word
    'name'   => Faker::firstNameMale(), // Set the name attribute to a random male first name
    'email'  => Faker::email(),         // Set the email attribute to a random email address
    'body'   => Faker::text(),          // Set the body attribute to a random string of text
    'slogan' => Faker::sentence(),      // Set the slogan attribute to a random sentence
]);
```

##### Example 4

This will set the `age` attribute to a random number between 20 and 40.
```php
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('MyModel')->setDefinitions([
    'age' => Faker::numberBetween(20, 40),
]);
```

##### Example 5

This will set the `name` attribute to a random female first name. Because we've called the `unique` method first, the attribute should be unique between all your generated models. Be careful with this if you're generating lots models because we might run out of unique items. Also, note that calling `Faker::setLocale('whatever')` will reset the internal unique list.
```php
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('MyModel')->addDefinition('name', Faker::unique()->firstNameFemale());
```

##### Example 6

This will set the `profile_pic` attribute to a random image url of dimensions 400 by 400. Because we've called the `optional` method first, not all the generated models will have an image url set; sometimes we will return null.
```php
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('MyModel')->addDefinition('profile_pic', Faker::optional()->imageUrl(400, 400));
```

##### More

Check out the [faker library](https://github.com/fzaninotto/Faker) itself to see all the available methods. There are far too many to cover in the documentation here, and far too many for them to cover in their documentation too. Note that you can fiddle with the underlying faker instance through the public methods on our faker class if you want.

#### Factory

The factory generator can be useful for setting up relationships between models. The factory generator will return the model id of the model you ask it to generate.

##### Example 1

When we create a Foo object, we will find that the Bar object will been generated and saved too, and it's id will be assigned to the `bar_id` attribute of the Foo model.
```php
$fm->define('Foo')->addDefinition('bar_id', 'factory|Bar');

$fm->define('Bar')->addDefinition('baz', Faker::date('Y-m-d'));
```

### Creating And Seeding

The `create` function will create and save your model, and will also save anything you generate with the `Factory` generator too. If you want to create multiple instances, check out the seed `seed` function, which accepts an additional argument at the start which is the number of models to generate in the process. The `seed` function will effectively be calling the `create` function over and over.

For example, let's create a user model and associate multiple location and email models to each one. Each email will also have multiple token models.
```php
$user = $fm->create('User');
$profiles = $fm->seed(5, 'Location', ['user_id' => $user->id]);
$emails = $fm->seed(100, 'Email', ['user_id' => $user->id]);

foreach ($emails as $email) {
    $tokens = $fm->seed(50, 'Token', ['email_id' => $email->id]);
}
```

You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\ModelNotFoundException` will be thrown if the model class defined is not found.
* `League\FactoryMuffin\Exceptions\DefinitionNotFoundException` will be thrown if you try to create a model and you haven't defined a model definition for it earlier.
* `League\FactoryMuffin\Exceptions\SaveFailedException` will be thrown if the save function on your model returns false.
* `League\FactoryMuffin\Exceptions\SaveMethodNotFoundException` will be thrown if the save function on your model does not exist.
* Any other exception thrown by your model while trying to create or save it.

There are 5 other helper functions available:
* You may call `pending` to return an array of all the objects waiting to be saved.
* You may call `isPending` with an instance of a model to check if will be saved.
* You may call `saved` to return an array of all the saved objects.
* You may call `isSaved` with an instance of a model to check if it's saved.
* You may call `isPendingOrSaved` with an instance of a model to check if it will be saved, or is already saved.

Also, a reminder that the `instance` function is still available if you don't want database persistence.

### Deleting

You can delete all your saved models with the `deleteSaved` function. Please note that your saved models will be deleted in the reverse order they were saved to ensure relationships behave correctly.

If one or more models cannot be deleted, a `League\FactoryMuffin\Exceptions\DeletingFailedException` will be raised after we have attempted to delete all the saved models. You may access each underlying exception, in the order they were thrown during the whole process, with the `getExceptions` function which will return an array of exceptions. You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\DeleteFailedException` will be thrown if the delete function on your model returns false.
* `League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException` will be thrown if the delete function on your model does not exist.
* Any other exception thrown by your model while trying to delete it.

It's recommended that you call the `deleteSaved` function from PHPUnit's `tearDownAfterClass` function, however, if you are writing tests using Laravel's `TestCase`, you should call the `deleteSaved` function from the `tearDown` method before calling `parent::tearDown`. This method flushes the application instance's bindings and Factory Muffin would not unable to execute its deletes. Further more, this unbinds the assigned exception handler and you will not be able to troubleshoot your tests due to binding resolution exceptions obfuscating the true exceptions.

### Real Examples

To start with, we need to create some definitions:
```php
# tests/factories/all.php

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('Message')->setDefinitions([
    'user_id'      => 'factory|User',
    'subject'      => Faker::sentence(),
    'message'      => Faker::text(),
    'phone_number' => Faker::randomNumber(8),
    'created'      => Faker::date('Ymd h:s'),
    'slug'         => 'Message::makeSlug',
])->setCallback(function ($object, $saved) {
    // we're taking advantage of the callback functionality here
    $object->message .= '!';
});

$fm->define('User')->setDefinitions([
    'username' => Faker::firstNameMale(),
    'email'    => Faker::email(),
    'avatar'   => Faker::imageUrl(400, 600),
    'greeting' => 'RandomGreeting::get',
    'four'     => function() {
        return 2 + 2;
    },
]);
```

You can then use these factories in your tests:
```php
# tests/TestUserModel.php

use League\FactoryMuffin\Faker\Facade as Faker;

class TestUserModel extends PHPUnit_Framework_TestCase
{
    protected static $fm;

    public static function setupBeforeClass()
    {
        // create a new factory muffin instance
        static::$fm = new FactoryMuffin();

        // you can customize the save/delete methods
        // new FactoryMuffin(new ModelStore('save', 'delete'));

        // load your model definitions
        static::$fm->loadFactories(__DIR__.'/factories');

        // you can optionally set the faker locale
        Faker::setLocale('en_EN');
    }

    public function testSampleFactory()
    {
        $message = static::$fm->create('Message');
        $this->assertInstanceOf('Message', $message);
        $this->assertInstanceOf('User', $message->user);
    }

    public static function tearDownAfterClass()
    {
        static::$fm->deleteSaved();
    }
}
```

### Further Information

If you want more information, the following resources are available to you:
  * Generated api docs are available [here](http://factory-muffin.thephpleague.com/api/).
  * [Philip Brown's](https://github.com/philipbrown) article is available [here](http://culttt.com/2013/05/27/laravel-4-fixture-replacement-with-factorymuff/).
  * Our [test suite](tests) may also be useful to you.

## Integrations

* [CakePHP 3](https://github.com/gourmet/muffin)
* [Yii 2](https://github.com/saada/yii2-factory-muffin)


## Contributing

Please check out our [contribution guidelines](CONTRIBUTING.md) for details.


## Credits

Factory Muffin is based on [Zizaco Zizuini](https://github.com/Zizaco)'s original work on "Factory Muff", and is currently maintained by [Graham Campbell](https://github.com/GrahamCampbell). [Scott Robertson](https://github.com/scottrobertson) was also a co-maintainer before the 3.0 release. Thank you to all our wonderful [contributors](https://github.com/thephpleague/factory-muffin/contributors) too.


## License

Factory Muffin is licensed under [The MIT License (MIT)](LICENSE).
