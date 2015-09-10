<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('ProfileModelStub', [
    'profile' => 'text',
]);

FactoryMuffin::define('UserModelStub', [
    'name'    => 'word',
    'active'  => 'boolean',
    'email'   => 'email',
    'profile' => 'factory|ProfileModelStub',
]);

FactoryMuffin::define('group:UserModelStub', [
    'address' => 'address',
]);

FactoryMuffin::define('anothergroup:UserModelStub', [
    'address' => 'address',
    'active'  => 'false',
]);

FactoryMuffin::define('callbackgroup:UserModelStub', [], function ($obj) {
    $obj->test = 'bar';
});

FactoryMuffin::define('foo:DogModelStub', [
    'name' => 'firstNameMale',
    'age'  => 'numberBetween|1;15',
]);

FactoryMuffin::define('ExampleCallbackStub', [], function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

FactoryMuffin::define('AnotherCallbackStub', [
    'foo' => 'email',
], function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
