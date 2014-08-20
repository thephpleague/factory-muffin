---
layout: layout
title: Usage - Creating & Seeding
permalink: usage/creating/
---

# Usage - Creating & Seeding

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
* You may call `isPendingOrSaved` with an instance of a model to check if will be saved, or is already saved.
