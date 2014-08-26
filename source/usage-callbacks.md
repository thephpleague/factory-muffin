---
layout: layout
title: Usage - Callbacks
permalink: usage/callbacks/
---

# Usage - Callbacks

You may optionally specify a callback to be executed on model creation/instantiation as a third parameter when defining a definition. We will pass your model instance as the first parameter to the closure if you specify one. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function has used), and `false` if it's not being persisted (the instance function was used). We're using the `isPendingOrSaved` function under the hood here. Note that if you specify a callback and use the create function, we will try to save your model to the database both before and after we execute the callback.
