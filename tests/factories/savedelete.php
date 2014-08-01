<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('ModelThatWillSaveStub', array());
FactoryMuffin::define('ModelThatFailsToSaveStub', array());
FactoryMuffin::define('ModelThatFailsToDeleteStub', array());
FactoryMuffin::define('ModelWithNoSaveMethodStub', array());
FactoryMuffin::define('ModelWithNoDeleteMethodStub', array());
FactoryMuffin::define('ModelWithValidationErrorsStub', array());
