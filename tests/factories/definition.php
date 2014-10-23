<?php

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('ProfileModelStub', array(
    'profile' => Faker::text(),
));

$fm->define('UserModelStub', array(
    'name'    => Faker::word(),
    'active'  => Faker::boolean(),
    'email'   => Faker::email(),
    'profile' => 'factory|ProfileModelStub',
));

$fm->define('group:UserModelStub', array(
    'address' => Faker::address(),
));

$fm->define('anothergroup:UserModelStub', array(
    'address' => Faker::address(),
    'active'  => 'custom',
));

$fm->define('callbackgroup:UserModelStub', array(), function($obj) {
    $obj->test = 'bar';
});

$fm->define('foo:DogModelStub', array(
    'name' => Faker::firstNameMale(),
    'age'  => Faker::numberBetween(1, 15),
));

$fm->define('ExampleCallbackStub', array(), function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

$fm->define('AnotherCallbackStub', array(
    'foo' => Faker::email(),
), function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
