---
layout: layout
title: Upgrading from 2.0.x to 2.1.x
permalink: upgrading/2.1/
---

# Upgrading from 2.0.x to 2.1.x

## Multiple Factory Definitions

Now you can define multiple different factory definitions for your models. You can do this by prefixing the model class name with your "group" followed by a colon. This results in you defining your model like this: `League\FactoryMuffin\Facade::define('myGroup:Fully\Qualifed\ModelName', array('foo' => 'bar'))`. You don't have to entirely define your model here because we will first look for a definition without the group prefix, then apply your group definition on top of that definition, overriding attribute definitions where required.

## Small Change To Model Creation

Before you try to create your model instance, we'll check whether the class actually exists, thus avoiding a fatal error. If the class does not exist, we'll throw a `League\FactoryMuffin\Exceptions\ModelNotFoundException`. Note that this exception is new in 2.1.

## Changes To The Saving Process

We'll now keep track of objects waiting to be saved separately from objects that have already been saved. This means the `saved` and `isSaved` functions will behave slightly differently. Details of the changes are listed below:

* The `saved` function will now return an array of objects actually saved to the database whereas before it would return an array of objects that were either saved, or were going to be saved later.
* The `isSaved` function will now check if your object is actually saved to the database whereas before it would check if it was saved, or was going to be saved later.
* The `pending` function is new, and returns an array of objects that will be saved to the database later, but have not been saved yet.
* The `isPending` function is new, and checks if your object is going to be saved to the database later, but has not been saved yet.
* The `isPendingOrSaved` function is new, and checks if your object is either saved in the database, or will be saved to the database later. This behaves as the old `isSaved` function used to behave.

## Creation/Instantiation Callbacks

When you define your definitions, you may optionally specify a callback to be executed on model creation/instantiation as a third parameter. We will pass your model instance as the first parameter to the closure if you specify one. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function was used), and `false` if it's not being persisted (the instance function was used). Note that if you specify a callback and use the create function, we will try to save your model to the database both before and after we execute the callback.

## Addition To The Closure Generator

Now, we additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function was used), and `false` if it's not being persisted (the instance function was used). This is, of course, in addition to passing the object instance as the first parameter. Previously, you'd have had to call the `isSaved` function on the facade, but this is no longer needed now for checking if the object is being persisted. Note that due to changes to the `isSaved` function, we're actually calling `isPendingOrSaved` under the hood now.

## Additional Customisation

You may now call `League\FactoryMuffin\Facade::setCustomMaker(function ($class) { return new $class('example'); })` in order to register a closure to customise the model creation. This will be used internally by Factory Muffin rather than us just straight up using `new $class()`.

You may now call `League\FactoryMuffin\Facade::setCustomSetter(function ($object, $name, $value) { $object->set($name, $value); })` in order to register a closure to customise the attribute setting. This will be used internally by Factory Muffin when setting your attributes rather than us just using `$object->$name = $value`.

You may now call `League\FactoryMuffin\Facade::setCustomSaver(function ($object) { $object->save(); $object->push(); return true; })` in order to save your object in a custom way. This will be used internally by Factory Muffin when saving your object rather than us just using `$object->save()`.

You may now call `League\FactoryMuffin\Facade::setCustomDeleter(function ($object) { $object->forceDelete(); return true; })` in order to delete your object in a custom way. This will be used internally by Factory Muffin when deleting your object rather than us just using `$object->delete()`.

We hope this change allows you to use even more completely custom models with Factory Muffin.

## Other Minor Changes

We've added a `reset` method to the facade that will re-create the underlying factory instance.

We've added a `getFaker` method to the factory (also available through the facade), that will return the underlying faker instance. This method actually already existed in 2.0, but was previously `private` rather than `public`.

We no longer use `include_once` to load your definitions with the `loadFactories` method. We use `include` instead so that your definitions can be correctly re-added to your factory instance if you use the `reset` method.

There is a very tiny change to the exception message of the `League\FactoryMuffin\Exceptions\NoDefinedFactoryException`. If you were relying on that for some reason, watch out for that.

## Installing This Version

In your composer.json, add:
```json
{
    "require-dev": {
        "league/factory-muffin": "2.1.*"
    }
}
```
