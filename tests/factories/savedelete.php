<?php
/** @var \League\FactoryMuffin\FactoryMuffin $fm */

$fm->define('ModelThatWillSaveStub', []);
$fm->define('ModelThatFailsToSaveStub', []);
$fm->define('ModelThatFailsToDeleteStub', []);
$fm->define('ModelThatAlsoFailsToDeleteStub', []);
$fm->define('ModelWithNoSaveMethodStub', []);
$fm->define('ModelWithNoDeleteMethodStub', []);
$fm->define('ModelWithValidationErrorsStub', []);
$fm->define('ModelWithTrackedSaves', array());
$fm->define('no return:ModelWithTrackedSaves', array(), function () {
    // No return is treated truthie
});
$fm->define('return true:ModelWithTrackedSaves', array(), function () {
    return true;
});
$fm->define('return false:ModelWithTrackedSaves', array(), function () {
    return false;
});
