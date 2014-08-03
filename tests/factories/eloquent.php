<?php

use League\FactoryMuffin\Facade as FactoryMuffin;


FactoryMuffin::define('User', array(
    'name'   => 'firstNameMale',
    'email'  => 'email',
));

FactoryMuffin::define('Cat', array(
    'name'    => 'firstNameFemale',
    'user_id' => 'numberBetween|1;5',
));
