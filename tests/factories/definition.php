<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('ProfileModelStub', array(
    'profile' => 'text',
));

FactoryMuffin::define('UserModelStub', array(
    'name'    => 'word',
    'active'  => 'boolean',
    'email'   => 'email',
    'age'     => 'numberBetween|18;35',
    'profile' => 'factory|ProfileModelStub',
));

FactoryMuffin::define('group:UserModelStub', array(
    'address' => 'address',
));

FactoryMuffin::define('anothergroup:UserModelStub', array(
    'address' => 'address',
    'active'  => 'false',
));

FactoryMuffin::define('centenarian:UserModelStub', array(
    'age' => 'numberBetween|100;100',
));

FactoryMuffin::define('callbackgroup:UserModelStub', array(), function($obj) {
    $obj->test = 'bar';
});

FactoryMuffin::define('foo:DogModelStub', array(
    'name' => 'firstNameMale',
    'age'  => 'numberBetween|1;15',
));

FactoryMuffin::define('ExampleCallbackStub', array(), function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

FactoryMuffin::define('AnotherCallbackStub', array(
    'foo' => 'email'
), function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
