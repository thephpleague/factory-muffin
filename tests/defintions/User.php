<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('\League\FactoryMuffin\Test\Facade\User', array(
    'name' => 'string',
    'active' => 'boolean',
    'email' => 'email'
));
