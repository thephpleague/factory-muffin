---
layout: layout
title: Upgrading from 1.4.x to 1.5.x
permalink: upgrading/1.5/
---

# Upgrading from 1.4.x to 1.5.x

## Exceptions

We've added some exceptions for certain events:
* The `Zizaco\FactoryMuff\SaveException` will now be thrown on model creation if the save function returns false.
* The `Zizaco\FactoryMuff\NoDefinedFactoryException` will now be thrown if you attempt to generate attributes for an model that has no defined factory. Previously, php would raise a fatal error.

## Factory Definitions

Instead of having a `public static $factory = array('foo' => 'bar')` property on your model, you should call `Zizaco\FactoryMuff\Facade\FactoryMuff::define('Fully\Qualifed\ModelName', array('foo' => 'bar'))` to define your model's factory. Note that the property on the model is still supported for now, but is deprecated, and will be removed in 2.0.

## Installing This Version

In your composer.json, add:
```json
{
    "require-dev": {
        "league/factory-muffin": "1.5.*"
    }
}
```
