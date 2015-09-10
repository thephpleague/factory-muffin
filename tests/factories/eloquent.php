<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('User', [
    'name'   => 'firstNameMale',
    'email'  => 'email',
]);

FactoryMuffin::define('Cat', [
    'name'    => 'firstNameFemale',
    'user_id' => 'numberBetween|1;5',
]);
