FactoryMuffin
===========

[![Build Status](https://api.travis-ci.org/Zizaco/factory-muff.png)](https://travis-ci.org/Zizaco/factory-muff)
[![Latest Stable Version](https://poser.pugx.org/zizaco/factory-muff/v/stable.png)](https://packagist.org/packages/zizaco/factory-muff)
[![Latest Unstable Version](https://poser.pugx.org/zizaco/factory-muff/v/unstable.png)](https://packagist.org/packages/zizaco/factory-muff)
[![Total Downloads](https://poser.pugx.org/zizaco/factory-muff/downloads.png)](https://packagist.org/packages/zizaco/factory-muff)
[![License](https://poser.pugx.org/zizaco/factory-muff/license.png)](https://packagist.org/packages/zizaco/factory-muff)
[![ProjectStatus](http://stillmaintained.com/Zizaco/factory-muff.png)](http://stillmaintained.com/Zizaco/factory-muff)

The goal of this Package is to enable the rapid creation of objects for the purpose of testing. Basically a "[factory\_girl\_rails](https://github.com/thoughtbot/factory_girl_rails)" simplified for use with PHP.

### License

MIT

Installation
-----------

In the `require` key of `composer.json` file add the following

    "zizaco/factory-muff": "dev-master"

Run the Composer update command

    $ composer update


How it works
------------

FactoryMuffin (which stands for Factory Muffin) uses a list of 2300+ words with at least 4 characters. These words are scrambled at every execution and will not repeat unless you use all the words. In this case the list is re-started and scrambled again.

Theoretically you will not need to worry about repeating values​​, unless your application has ALOT of tests to run which may cause the wordlist to restart. If this is your case, you can simply increase wordlist in _wordlist.php_

### Usage

Declare a __public static__ array called __$factory__ in your model. This array should contain the kind of values you want for the attributes.

Example:
```php
class Message extends Eloquent
{
    // Array that determines the kind of attributes
    // you would like to have
    public static $factory = array(
        'user_id' => 'factory|User',
        'subject' => 'string',
        'address' => 'email',
        'message' => 'text',
        'phone_number' => 'integer|8',
        'created' => 'date|Ymd h:s',
        'slug' => 'call|makeSlug|string',
    );

    // Relashionship with user
    public function user()
    {
        return $this->belongs_to('User');
    }

    // this static method generates the 'slug'
    public static function makeSlug($factory_muff_generated_string)
    {
        $base = strtolower($factory_muff_generated_string);
        return preg_replace('|[^a-z0-9]+|', '-', $base);
    }
```

You can also declare a static method named `factory()` on your model. This allows more flexibility as PHP has restrictions on what you can use as array values when defining properties.

Example:
```php
class Message extends Eloquent
{
    // Defined as a static method
    public static factory()
    {
        return array(
            'user_id' => 'factory|User',
            // Not possible when defined as a static array
            'greeting' => RandomGreeting::get(),
            // Closures will be called automatically
            'four' => function() {
                return 2 + 2;
            },
        );
    }
```

To create model instances do the following:
```php
<?php

use League\FactoryMuffin\FactoryMuffin;

class TestUserModel extends PHPUnit_Framework_TestCase {

    public function __construct()
    {
        // Prepare FactoryMuffin
        $this->factory = new FactoryMuffin;
    }

    public function testSampleFactory()
    {
        // Creates a new instance
        $message = $this->factory->create( 'Message' );

        // Access the relationship, because attributes
        // with kind "factory|<ModelName> creates and
        // saves the <ModelName> object and return the
        // id. And now, because of eloquent we can do
        // this:
        $message->user->username;

        // And you can also get attributes for a new
        // instance
        $new_message = new Message( $this->factory->attributesFor( 'Message' ) )

        // For both methods (create and attributesFor
        // you can pass fixed attributes. Those will be
        // merged into the object before save.
        $muffin_message = $this->factory->create(
            'Message', array(
                'subject' => 'About Muffin',
                'message' => 'Its tasty!',
            ),
        );
    }
```

### Kinds of attribute supported

| Kind          | Option  | Description                                                                        | Example
| :-----------: | :-----: |:----------------------------------------------------------------------------------:| :----------------:|
| string        | length  | Random string of text                                                              | string|12         |
| email         | -       | Random email address                                                               | email             |
| text          | length  | Random body of text                                                                | text|100          |
| integer       | length  | Random integer/number                                                              | integer|10        |
| date          | format  | Generate date with specific format                                                 | date|d-M-Y        |
| factory       | model   | Will run ->create() on another model and return it's id                            | factory|User      |
| call          | method  | Allows you to call any static methods                                              | call|staticMethod |
| default       | string  | Any Kinds that are not reccognised will try and load from Faker, or return the text| creditCardDetails |


### Save Failures

If a model cannot be saved to the database, for example if it fails validation through a library like Ardent, a League\FactoryMuffin\SaveException will be raised.

More help
---------

Read the source code. There is alot of comments there. __;)__

or contact me.
