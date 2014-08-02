Upgrading
=========


## Upgrading from 1.6.x to 2.0.x

### Class Name Changes

Every class has moved. So here's a summary of the changes:
* The root namespace has been moved to `League\FactoryMuffin`, and you should now access the facade using `Zizaco\FactoryMuff\Facade::method()`.
* Many kind classes have been removed in favour of the faker alternatives. Those remaining can be found under the `Zizaco\FactoryMuff\Generators` namespace.
* There are many more exceptions, and the names of the existing ones have changed. The exceptions can be found under the `Zizaco\FactoryMuff\Exception` namespace.

A detailed list of every change, with the fully qualified names are listed below:
* Moved: `Zizaco\FactoryMuff\FactoryMuff` => `League\FactoryMuffin\Factory`
* Moved: `Zizaco\FactoryMuff\Facade\FactoryMuff` => `League\FactoryMuffin\Factory`
* Moved: `Zizaco\FactoryMuff\SaveException` => `League\FactoryMuffin\Exceptions\SaveFailedException`
* Moved: `Zizaco\FactoryMuff\NoDefinedFactoryException` => `League\FactoryMuffin\Exceptions\NoDefinedFactoryException`
* Moved: `Zizaco\FactoryMuff\Kind` => `League\FactoryMuffin\Generators\Base`
* Moved: `Zizaco\FactoryMuff\Kind\Call` => `League\FactoryMuffin\Generators\Call`
* Moved: `Zizaco\FactoryMuff\Kind\Closure` => `League\FactoryMuffin\Generators\Closure`
* Moved: `Zizaco\FactoryMuff\Kind\Factory` => `League\FactoryMuffin\Generators\Factory`
* Moved: `Zizaco\FactoryMuff\Kind\Generic` => `League\FactoryMuffin\Generators\Generic`
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

### Autoloading

We have moved from PSR-0 to PSR-4 for autoloading.

## Factory Definitions

Having a public static factory property is no longer supported. You must use the define method introduced in the 1.5.x series. You may call it like this: `League\FactoryMuffin\Factory::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))`. We have provided a nifty way for you to do this in your tests. PHPUnit provides a `setupBeforeClass` method. Within that method you can call `League\FactoryMuffin\Factory::loadFactories(__DIR__ . '/factories');`, and it will include all files in the factories folder. Within those php files, you can put your definitions (all your code that calls the define method). A full example in included on our readme.

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

* We now use the faker package, so our `Zizaco\FactoryMuff\Wordlist` class has been removed. All your previous definitions should still work in as close to the same way as possible, but watch out for any minor differences. With the addition of the faker library, far more definitions are now possible since any definitions not natively provided by us, fall back to the faker package. Also, it should be noted you may use closures now to generate completely custom attributes.

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

As well as having a `public static $factory = array('foo' => 'bar')` property on your model, you may also call `Zizaco\FactoryMuff\Facade\FactoryMuff::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))` to define your model's factory.

### Installing This Version

In your composer.json, add:

```json
{
    "require-dev": {
        "league/factory-muffin": "1.5.*"
    }
}
```
