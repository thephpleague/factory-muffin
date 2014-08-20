---
layout: layout
title: Usage - Additional Customisation
permalink: usage/customisation/
---

# Usage - Additional Customisation

You may call `League\FactoryMuffin\Facade::setCustomMaker(function ($class) { return new $class('example'); })` in order to register a closure to customise the model creation. This will be used internally by Factory Muffin rather than us just straight up using `new $class()`.

You may call  `League\FactoryMuffin\Facade::setCustomSetter(function ($object, $name, $value) { $object->set($name, $value); })` in order to register a closure to customise the attribute setting. This will be used internally by Factory Muffin when setting your attributes rather than us just using `$object->$name = $value`.
