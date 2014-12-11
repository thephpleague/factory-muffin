<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('ModelThatWillSaveStub', array());
FactoryMuffin::define('ModelThatFailsToSaveStub', array());
FactoryMuffin::define('ModelThatFailsToDeleteStub', array());
FactoryMuffin::define('ModelThatAlsoFailsToDeleteStub', array());
FactoryMuffin::define('ModelWithNoSaveMethodStub', array());
FactoryMuffin::define('ModelWithNoDeleteMethodStub', array());
FactoryMuffin::define('ModelWithValidationErrorsStub', array());
FactoryMuffin::define('ModelWithTrackedSaves', array());
FactoryMuffin::define('no return:ModelWithTrackedSaves', array(), function () {
    // No return is treated truthie
});
FactoryMuffin::define('return true:ModelWithTrackedSaves', array(), function () {
    return true;
});
FactoryMuffin::define('return false:ModelWithTrackedSaves', array(), function () {
    return false;
});
