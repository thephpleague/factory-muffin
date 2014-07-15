# FactoryMuffin

[![Build Status](https://travis-ci.org/thephpleague/factory-muffin.svg)](https://travis-ci.org/thephpleague/factory-muffin)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thephpleague/factory-muffin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thephpleague/factory-muffin/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/league/factory-muffin/v/stable.png)](https://packagist.org/packages/league/factory-muffin)
[![Latest Unstable Version](https://poser.pugx.org/league/factory-muffin/v/unstable.png)](https://packagist.org/packages/league/factory-muffin
[![Total Downloads](https://poser.pugx.org/league/factory-muffin/downloads.png)](https://packagist.org/packages/league/factory-muffin)
[![License](https://poser.pugx.org/league/factory-muffin/license.png)](https://packagist.org/packages/league/factory-muffin)

The goal of this Package is to enable the rapid creation of objects for the purpose of testing. Basically a "[factory\_girl\_rails](https://github.com/thoughtbot/factory_girl_rails)" simplified for use with PHP.

## Install

Via Composer

``` json
{
    "require": {
        "league/factory-muffin": "~2.0"
    }
}
```

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

## Kinds of attribute supported

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


## Save Failures

If a model cannot be saved to the database, for example if it fails validation through a library like Ardent, a League\FactoryMuffin\SaveException will be raised.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/factory-muffin/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Zizaco Zizuini](https://github.com/Zizaco)
- [All Contributors](https://github.com/thephpleague/factory-muffin/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/factory-muffin/blob/master/LICENSE) for more information.
