Upgrading
=========


Welcome to the upgrade guide for Factory Muffin. We've tried to cover all changes from 1.4 through to the current release. If we've missed anything, feel free to create an issue, or send a pull request. From 2.0, we are following the PSR-2 coding standard, and semantic versioning, so there will be no BC breaks until 3.0 other than essential fixes, and any changes will be documented here.


## Upgrading from 1.6.x to 2.0.x

### Introduction

Version 2.0 marks a major file milestone in this project, under the new name of "Factory Muffin". We see a large number of improvements and some breaking changes. Within this section of the upgrading guide, you will see "the `xyz` function can be called". You should assume that these functions should be called statically on the `League\FactoryMuffin\Facade` class.

### Class Name Changes

Every class has moved. So here's a summary of the changes:
* The root namespace has been moved from `Zizaco\FactoryMuff` to `League\FactoryMuffin`. You should now access the facade using `Zizaco\FactoryMuff\Facade::fooBar()`.
* Many generator (kind) classes have been removed in favour of the faker alternatives. Those remaining can be found under the `Zizaco\FactoryMuff\Generators` namespace.
* There are many more exceptions, and the names of the existing exceptions have changed. The exceptions can be found under the `Zizaco\FactoryMuff\Exception` namespace.

A detailed list of every change, with the fully qualified names is listed below:
* Moved: `Zizaco\FactoryMuff\FactoryMuff` => `League\FactoryMuffin\Factory`
* Moved: `Zizaco\FactoryMuff\Facade\FactoryMuff` => `League\FactoryMuffin\Facade`
* Moved: `Zizaco\FactoryMuff\SaveException` => `League\FactoryMuffin\Exceptions\SaveFailedException`
* Moved: `Zizaco\FactoryMuff\NoDefinedFactoryException` => `League\FactoryMuffin\Exceptions\NoDefinedFactoryException`
* Moved: `Zizaco\FactoryMuff\Kind` => `League\FactoryMuffin\Generators\Base`
* Moved: `Zizaco\FactoryMuff\Kind\Call` => `League\FactoryMuffin\Generators\Call`
* Moved: `Zizaco\FactoryMuff\Kind\Closure` => `League\FactoryMuffin\Generators\Closure`
* Moved: `Zizaco\FactoryMuff\Kind\Factory` => `League\FactoryMuffin\Generators\Factory`
* Moved: `Zizaco\FactoryMuff\Kind\Generic` => `League\FactoryMuffin\Generators\Generic`
* Added: `League\FactoryMuffin\Exceptions\DeleteFailedException`
* Added: `League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException`
* Added: `League\FactoryMuffin\Exceptions\DeletingFailedException`
* Added: `League\FactoryMuffin\Exceptions\DirectoryNotFoundException`
* Added: `League\FactoryMuffin\Exceptions\MethodNotFoundException`
* Added: `League\FactoryMuffin\Exceptions\ModelException`
* Added: `League\FactoryMuffin\Exceptions\SaveMethodNotFoundException`
* Removed: `Zizaco\FactoryMuff\Kind\Date`
* Removed: `Zizaco\FactoryMuff\Kind\Integer`
* Removed: `Zizaco\FactoryMuff\Kind\Name`
* Removed: `Zizaco\FactoryMuff\Kind\String`
* Removed: `Zizaco\FactoryMuff\Kind\Text`

It also should be noted that we've moved from PSR-0 to PSR-4 for autoloading.

### Facade Changes

Under it's new name, the facade class now uses `__callStatic` to dynamically call the underlying factory instance. Also, note that all public methods that would have returned void, return the factory instance in order to support method chaining.

### Factory Definitions

Having a public static factory property is no longer supported. You must use the `define` function introduced in the 1.5.x series. You may call it like this: `League\FactoryMuffin\Facade::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))`. We have provided a nifty way for you to do this in your tests. PHPUnit provides a `setupBeforeClass` function. Within that function you can call `League\FactoryMuffin\Facade::loadFactories(__DIR__ . '/factories');`, and it will include all files in the factories folder. Within those php files, you can put your definitions (all your code that calls the define function). The `loadFactories` function will throw a `League\FactoryMuffin\Exceptions\DirectoryNotFoundException` exception if the directory you're loading is not found. A full example is included in the readme.

### Generator (Kind) Changes

We now refer to what was previously the Kind classes, as Generator classes. We've removed some of these in favour of the faker alternatives. We currently provide the following generators: `Call`, `Closure`, `Factory`, and `Generic`. The call, closure, and factory generators have not changed significantly since previous versions, and the generic generator still provides access to the faker generators. Note that you can use a `;` to send multiple arguments to the generators. The `Closure` generator will now pass the instance of your model into your closure as the first parameter too.

The removed generators are `Date`, `Integer`, `Name`, `String`, and `Text`, however, these are still callable (some name changes required), as they are available on the generic generator through faker.
* Instead of using `integer|8`, you can use `randomNumber|8`.
* Instead of using `string`, you can use `sentence`, or `word`.
* Instead of using `name`, you can use things like `firstNameMale`.
* `date` and `text` can be used in the same way you were using them before.

### Creating And Seeding

The `create` function can be called in the same way, but has internal improvements. Now, it will also save anything you generate with the `Factory` generator too. We now have a new function called `seed`, which accepts an additional argument at the start which is the number of models to generate in the process. The `seed` function will effectively be calling the `create` function over and over. It should be noted that you can set a custom save function before you get going with the `setSaveMethod` function. Also, a reminder that the `instance` function is still available if you don't want database persistence.

You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\NoDefinedFactoryException` will be thrown if you try to create a model and you haven't defined a factory definition for it earlier.
* `League\FactoryMuffin\Exceptions\SaveFailedException` will be thrown if the save function on your model returns false.
* `League\FactoryMuffin\Exceptions\SaveMethodNotFoundException` will be thrown if the save function on your model does not exist.
* Any other exception thrown by your model while trying to create or save it.

There are 2 other helper functions available. You may call `saved` to return an array of all the saved objects. You may call `isSaved` with an instance of a model to check if it's saved.

### Deleting

You can now delete all your saved models with the `deleteSaved` function. It should be noted that you can set a custom delete function before you get going with the `setDeleteMethod` function.

If one or more models cannot be deleted, a `League\FactoryMuffin\Exceptions\DeletingFailedException` will be raised after we have attempted to delete all the saved models. You may access each underlying exception, in the order they were thrown during the whole process, with the `getExceptions` function which will return an array of exceptions. You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\DeleteFailedException` will be thrown if the delete function on your model returns false.
* `League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException` will be thrown if the delete function on your model does not exist.
* Any other exception thrown by your model while trying to delete it.

It's recommended that you call the `deleteSaved` function from PHPUnit's `tearDownAfterClass` function. A full example is included in the readme.

### Exceptions

The exceptions have been completely overhauled. Each exception is documented with the documentation for the functions that throw them.

You can see a diagram showing the exception hierarchy here:

![diagram](https://cloud.githubusercontent.com/assets/2829600/3790579/8fc52572-1b0d-11e4-96b1-7f0eac0dc10d.png)

### Other BC Breaks

The `attributesFor` function no longer accepts a class name as the first argument, and the `generateAttr` function no longer accepts a class name as a second argument. Please pass an actual model instance to both functions instead.

### Installing This Version

In your composer.json, add:
```json
{
    "require-dev": {
        "league/factory-muffin": "2.0.*"
    }
}
```


## Upgrading from 1.5.x to 1.6.x

### Faker Usage

* We now use the faker package, so our `Zizaco\FactoryMuff\Wordlist` class has been removed. All your previous definitions should still work in as close to the same way as possible, but watch out for any minor differences. With the addition of the faker library, far more definitions are now possible since any definitions not natively provided by us, fall back to the faker package. Also, it should be noted you may use closures now to generate completely custom attributes. The new classes can be found under the `Zizaco\FactoryMuff\Kind` namespace.

### Installing This Version

In your composer.json, add:
```json
{
    "require-dev": {
        "league/factory-muffin": "1.6.*"
    }
}
```


## Upgrading from 1.4.x to 1.5.x

### Exceptions

We've added some exceptions for certain events:
* The `Zizaco\FactoryMuff\SaveException` will now be thrown on model creation if the save function returns false.
* The `Zizaco\FactoryMuff\NoDefinedFactoryException` will now be thrown if you attempt to generate attributes for an model that has no defined factory. Previously, php would raise a fatal error.

### Factory Definitions

Instead of having a `public static $factory = array('foo' => 'bar')` property on your model, you mcan call `Zizaco\FactoryMuff\Facade\FactoryMuff::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))` to define your model's factory. Note that the property on the model is deprecated from this point onwards, and will be removed in 2.0.

### Installing This Version

In your composer.json, add:
```json
{
    "require-dev": {
        "league/factory-muffin": "1.5.*"
    }
}
```
