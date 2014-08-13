---
layout: layout
title: Usage - Deleting
permalink: usage/deleting/
---

# Usage - Deleting

You can delete all your saved models with the `deleteSaved` function. It should be noted that you can set a custom delete function before you get going with the `setDeleteMethod` function.

If one or more models cannot be deleted, a `League\FactoryMuffin\Exceptions\DeletingFailedException` will be raised after we have attempted to delete all the saved models. You may access each underlying exception, in the order they were thrown during the whole process, with the `getExceptions` function which will return an array of exceptions. You may encounter the following exceptions:
* `League\FactoryMuffin\Exceptions\DeleteFailedException` will be thrown if the delete function on your model returns false.
* `League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException` will be thrown if the delete function on your model does not exist.
* Any other exception thrown by your model while trying to delete it.

It's recommended that you call the `deleteSaved` function from PHPUnit's `tearDownAfterClass` function.
