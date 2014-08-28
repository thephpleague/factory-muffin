<?php

use League\FactoryMuffin\Facades\FactoryMuffin;
use League\FactoryMuffin\Facades\Faker;

FactoryMuffin::define('ProfileModelStub', array(
    'profile' => Faker::text(),
));

FactoryMuffin::define('UserModelStub', array(
    'name'    => Faker::word(),
    'active'  => Faker::boolean(),
    'email'   => Faker::email(),
    'profile' => 'factory|ProfileModelStub',
));

FactoryMuffin::define('group:UserModelStub', array(
    'address' => Faker::address(),
));

FactoryMuffin::define('anothergroup:UserModelStub', array(
    'address' => Faker::address(),
    'active'  => 'custom',
));

FactoryMuffin::define('foo:DogModelStub', array(
    'name' => Faker::firstNameMale(),
    'age'  => Faker::numberBetween(1, 15),
));

FactoryMuffin::define('ExampleCallbackStub', array(), function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

FactoryMuffin::define('AnotherCallbackStub', array(
    'foo' => Faker::email(),
), function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
