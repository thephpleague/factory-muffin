<?php

use League\FactoryMuffin\Facades\FactoryMuffin;
use League\FactoryMuffin\Facades\Faker;


FactoryMuffin::define('User', array(
    'name'   => Faker::firstNameMale(),
    'email'  => Faker::email(),
));

FactoryMuffin::define('Cat', array(
    'name'    => Faker::firstNameFemale(),
    'user_id' => Faker::numberBetween(1, 5),
));
