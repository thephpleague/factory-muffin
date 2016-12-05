<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('ProfileModelStub')->addDefinition('profile', Faker::text());

$fm->define('AttributeDefinitionsStub');

$fm->define('NotAClass');

$fm->define('UserModelStub')->setDefinitions([
    'name'    => Faker::word(),
    'active'  => Faker::boolean(),
    'email'   => Faker::email(),
    'age'     => Faker::numberBetween(18, 35),
    'profile' => 'factory|ProfileModelStub',
])->setCallback(function ($obj) {
    $obj->test = 'foo';
});

$fm->define('group:UserModelStub')->addDefinition('address', Faker::address());

$fm->define('anothergroup:UserModelStub')->setDefinitions([
    'address' => Faker::address(),
    'active'  => 'custom',
]);

$fm->define('centenarian:UserModelStub')->setDefinitions([
    'age' => Faker::numberBetween(100, 100),
]);

$fm->define('callbackgroup:UserModelStub')->setCallback(function ($obj) {
    $obj->test = 'bar';
});

$fm->define('noattributes:UserModelStub')->clearDefinitions();

$fm->define('ExampleCallbackStub')->setCallback(function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

$fm->define('AnotherCallbackStub')->addDefinition('foo', Faker::email())->setCallback(function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});

$fm->define('CustomMakerStub')->setMaker(function ($class) {
    return new $class('qwerty');
});

$fm->define('group:CustomMakerStub')->setMaker(function ($class) {
    return new $class('qwertyuiop');
});

$fm->define('clear:CustomMakerStub')->clearMaker();

$fm->define('definitionscallback:UserModelStub')->setDefinitions(function () {
    return [
        'name'    => Faker::word(),
        'active'  => Faker::boolean(),
        'email'   => Faker::email(),
        'age'     => Faker::numberBetween(18, 35),
        'profile' => 'factory|ProfileModelStub',
    ];
});
