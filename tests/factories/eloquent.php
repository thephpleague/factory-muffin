<?php

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('User', [
    'name'   => Faker::firstNameMale(),
    'email'  => Faker::email(),
]);

$fm->define('Cat', [
    'name'    => Faker::firstNameFemale(),
    'user_id' => Faker::numberBetween(1, 5),
]);
