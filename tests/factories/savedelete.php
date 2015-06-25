<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$fm->define('ModelThatWillSaveStub');
$fm->define('ModelThatFailsToSaveStub');
$fm->define('ModelThatFailsToDeleteStub');
$fm->define('ModelThatAlsoFailsToDeleteStub');
$fm->define('ModelWithNoSaveMethodStub');
$fm->define('ModelWithNoDeleteMethodStub');
$fm->define('ModelWithValidationErrorsStub');
$fm->define('ModelWithBadValidationErrorsStub');
$fm->define('ModelWithTrackedSaves');

$fm->define('no return:ModelWithTrackedSaves')->setCallback(function () {
    // No return is treated as true
});

$fm->define('return true:ModelWithTrackedSaves')->setCallback(function () {
    return true;
});

$fm->define('return false:ModelWithTrackedSaves')->setCallback(function () {
    return false;
});
