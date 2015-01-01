<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('User')->setDefinitions([
    'name'   => Faker::firstNameMale(),
    'email'  => Faker::email(),
]);

$fm->define('Cat')->setDefinitions([
    'name'    => Faker::firstNameFemale(),
    'user_id' => Faker::numberBetween(1, 5),
]);
