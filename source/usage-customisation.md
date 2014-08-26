---
layout: layout
title: Usage - Additional Customisation
permalink: usage/customisation/
---

# Usage - Additional Customisation

You may call `League\FactoryMuffin\Facade::setCustomMaker(function ($class) { return new $class('example'); })` in order to register a closure to customise the model creation. This will be used internally by Factory Muffin rather than us just straight up using `new $class()`.

You may call  `League\FactoryMuffin\Facade::setCustomSetter(function ($object, $name, $value) { $object->set($name, $value); })` in order to register a closure to customise the attribute setting. This will be used internally by Factory Muffin when setting your attributes rather than us just using `$object->$name = $value`.

You may call `League\FactoryMuffin\Facade::setCustomSaver(function ($object) { $object->save(); $object->push(); return true; })` in order to save your object in a custom way. This will be used internally by Factory Muffin when saving your object rather than us just using `$object->save()`.

You may call `League\FactoryMuffin\Facade::setCustomDeleter(function ($object) { $object->forceDelete(); return true; })` in order to delete your object in a custom way. This will be used internally by Factory Muffin when deleting your object rather than us just using `$object->delete()`.
