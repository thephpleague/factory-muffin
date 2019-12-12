<?php

use League\FactoryMuffin\Faker\Facade as Faker;

/* @var \League\FactoryMuffin\FactoryMuffin $fm */
$fm->define('FakerHydrationModel')->setDefinitions([
    'title' => Faker::word(),
    'email' => Faker::email(),
    'text'  => Faker::text(),
]);
