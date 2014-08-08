<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('ProfileModelStub', array(
    'profile' => 'text',
));

FactoryMuffin::define('UserModelStub', array(
    'name'    => 'word',
    'active'  => 'boolean',
    'email'   => 'email',
    'profile' => 'factory|ProfileModelStub',
));

FactoryMuffin::define('group:UserModelStub', array(
    'address' => 'address',
));

FactoryMuffin::define('anothergroup:UserModelStub', array(
    'address' => 'address',
    'active' => 'false'
));
