<?php

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('User', array(
    'name'   => Faker::firstNameMale(),
    'email'  => Faker::email(),
));

$fm->define('Cat', array(
    'name'    => Faker::firstNameFemale(),
    'user_id' => Faker::numberBetween(1, 5),
));
