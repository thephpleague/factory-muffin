<?php

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('ProfileModelStub', [
    'profile' => Faker::text(),
]);

$fm->define('UserModelStub', [
    'name'    => Faker::word(),
    'active'  => Faker::boolean(),
    'email'   => Faker::email(),
    'profile' => 'factory|ProfileModelStub',
]);

$fm->define('group:UserModelStub', [
    'address' => Faker::address(),
]);

$fm->define('anothergroup:UserModelStub', [
    'address' => Faker::address(),
    'active'  => 'custom',
]);

$fm->define('callbackgroup:UserModelStub', [], function ($obj) {
    $obj->test = 'bar';
});

$fm->define('foo:DogModelStub', [
    'name' => Faker::firstNameMale(),
    'age'  => Faker::numberBetween(1, 15),
]);

$fm->define('ExampleCallbackStub', [], function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

$fm->define('AnotherCallbackStub', [
    'foo' => Faker::email(),
], function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
