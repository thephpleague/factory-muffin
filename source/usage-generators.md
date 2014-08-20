---
layout: layout
title: Usage - Generators
permalink: usage/generators/
---

# Usage - Generators

## Generic

The generic generator will be the generator you use the most. It will communicate with the faker library in order to generate your attribute.

### Example 1

There is a simple example of setting a few different attributes.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'foo'    => 'word',          // Set the foo attribute to a random word
    'name'   => 'firstNameMale', // Set the name attribute to a random male first name
    'email'  => 'email',         // Set the email attribute to a random email address
    'body'   => 'text',          // Set the body attribute to a random string of text
    'slogan' => 'sentence',      // Set the slogan attribute to a random sentence
));
```

### Example 2

This will set the `age` attribute to a random number between 20 and 40. Note how we're using the `;` here to pass multiple arguments to the faker method.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'age' => 'numberBetween|20;40',
));
```

### Example 3

This will set the `name` attribute to a random female first name. It will ensure that it is unique between all your generated models.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'name' => 'unique:firstNameFemale',
));
```

### Example 4

This will set the `profile_pic` attribute to a random image url of dimensions 400 by 400. Because we've added the optional flag at the start, not all the generated models will have an image url set; sometimes we will return null.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'profile_pic' => 'optional:imageUrl|400;400',
));
```

### More

Check out the [faker library](https://github.com/fzaninotto/Faker) itself to see all the available methods. There are far too many to cover in the documentation here, and far too many for them to cover in their documentation too. Note that you may access the underlying faker instance using the `getFaker` method.

## Factory

The factory generator can be useful for setting up relationships between models. The factory generator will return the model id of the model you ask it to generate.

### Example 1

When we create a Foo object, we will find that the Bar object will been generated and saved too, and it's id will be assigned to the `bar_id` attribute of the Foo model.
```php
League\FactoryMuffin\Facade::define('Foo', array(
    'bar_id' => 'factory|Bar'
));

League\FactoryMuffin\Facade::define('Bar', array(
    'baz' => 'date|Y-m-d'
));
```

## Call

The call generator allows you to generate attributes by calling **static** methods on your models.

### Example 1

This will set the `foo` attribute to whatever calling `MyModel::exampleMethod()` returns.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'foo' => 'call|exampleMethod',
));
```

### Example 2

This will set the `bar` attribute to whatever calling `MyModel::anotherMethod('hello')` returns.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'bar' => 'call|anotherMethod|hello',
));
```

### Example 3

This will set the `baz` attribute to whatever calling the `exampleMethod` method on the `OtherModel` after we generate and save it.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'baz' => 'call|exampleMethod|factory|OtherModel',
));

League\FactoryMuffin\Facade::define('OtherModel', array(
    'example' => 'boolean',
));
```

## Closure

The closure generator can be used if you want a more custom solution. Whatever you return from the closure you write will be set as the attribute. Note that we pass an instance of your model as the first parameter of the closure to give you even more flexibility to modify it as you wish. We additionally pass a boolean as the second parameter that will be `true` if the model is being persisted to the database (the create function has used), and `false` if it's not being persisted (the instance function was used). We're using the `isPendingOrSaved` function under the hood here.

### Example 1

As you can see from this example, the ability to use a closure to generate attributes can be so useful and flexible. Here we use it to generate a slug based on the initially randomly generated 5 word long title.
```php
League\FactoryMuffin\Facade::define('MyModel', array(
    'title' => 'sentence|5',
    'slug' => function ($object, $saved) {
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $object->title);
        $slug = strtolower(trim($slug, '-'));
        $slug = preg_replace("/[\/_|+ -]+/", '-', $slug);

        return $slug;
    },
));
```
