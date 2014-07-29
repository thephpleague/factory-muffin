<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('ProfileModelStub', array(
    'profile' => 'text',
));

FactoryMuffin::define('UserModelStub', array(
    'name'    => 'string',
    'active'  => 'boolean',
    'email'   => 'email',
    'profile' => 'factory|ProfileModelStub',
));
