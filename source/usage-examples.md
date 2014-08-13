---
layout: layout
title: Usage - Real Examples
permalink: /usage/examples/
---

# Usage - Real Examples

To start with, we need to create some definitions:
```php
# tests/factories/all.php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('Message', array(
    'user_id'      => 'factory|User',
    'subject'      => 'sentence',
    'message'      => 'text',
    'phone_number' => 'randomNumber|8',
    'created'      => 'date|Ymd h:s',
    'slug'         => 'call|makeSlug|word',
));

FactoryMuffin::define('User', array(
    'username' => 'firstNameMale',
    'email'    => 'email',
    'avatar'   => 'imageUrl|400;600',
    'greeting' => RandomGreeting::get(),
    'four'     => function() {
        return 2 + 2;
    },
));
```

You can then use these factories in your tests:
```php
# tests/TestUserModel.php

use League\FactoryMuffin\Facade as FactoryMuffin;

class TestUserModel extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        // note that method chaining is supported
        FactoryMuffin::setFakerLocale('en_EN')->setSaveMethod('save'); // optional step
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
    }

    public function testSampleFactory()
    {
        $message = FactoryMuffin::create('Message');
        $this->assertInstanceOf('Message', $message);
        $this->assertInstanceOf('User', $message->user);
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete'); // optional step
        FactoryMuffin::deleteSaved();
    }
}
```
