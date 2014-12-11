<?php

$fm->define('ModelThatWillSaveStub', []);
$fm->define('ModelThatFailsToSaveStub', []);
$fm->define('ModelThatFailsToDeleteStub', []);
$fm->define('ModelThatAlsoFailsToDeleteStub', []);
$fm->define('ModelWithNoSaveMethodStub', []);
$fm->define('ModelWithNoDeleteMethodStub', []);
$fm->define('ModelWithValidationErrorsStub', []);
$fm->define('ModelWithTrackedSaves', []);
$fm->define('no return:ModelWithTrackedSaves', [], function () {
    // No return is treated as true
});
$fm->define('return true:ModelWithTrackedSaves', [], function () {
    return true;
});
$fm->define('return false:ModelWithTrackedSaves', [], function () {
    return false;
});
