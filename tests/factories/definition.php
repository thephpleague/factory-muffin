<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('ProfileModelStub', array(
    'profile' => 'text',
));

FactoryMuffin::define('UserModelStub', array(
    'name'    => 'word',
    'active'  => 'boolean',
    'email'   => 'email',
    'profile' => 'factory|ProfileModelStub',
));
