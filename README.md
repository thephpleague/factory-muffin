FactoryMuff
===========

![factory muff poster](https://dl.dropbox.com/u/12506137/libs_bundles/factorymuff.png)

[![Build Status](https://api.travis-ci.org/Zizaco/factory-muff.png)](https://travis-ci.org/Zizaco/factory-muff)
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

FactoryMuff (which stands for Factory Muffin) uses a list of 2300+ words with at least 4 characters. These words are scrambled at every execution and will not repeat unless you use all the words. In this case the list is re-started and scrambled again.

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
    );

    // Relashionship with user
    public function user()
    {
        return $this->belongs_to('User');
    }
```

To create model instances do the following:
```php
<?php

class TestUserModel extends PHPUnit_Framework_TestCase {

    public function __construct()
    {
        // Prepare FactoryMuff
        $this->factory = new FactoryMuff;
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

* string
 * Grab a random word from the wordlist. Ex: "bucket","mouse","laptop","America"
* email
 * An word from the wordlist + domain. Ex: "smart@example.com", "Brasil@nonexist.org"
* text
 * A text of about 7 words from the list. Ex: "something table underrated blackboard"
* factory|ModelName
 * Will trigger the __create__ for the given model and return it's id.
* Any thing else
 * Will be returned. Ex: kind "tuckemuffin" will become the value of the attribute in the instantiated object.


### Save Failures

If a model cannot be saved to the database, for example if it fails validation through a library like Ardent, a Zizaco\FactoryMuff\SaveException will be raised.

More help
---------

Read the source code. There is alot of comments there. __;)__

or contact me.
