<?php

use League\FactoryMuffin\Facades\FactoryMuffin;

FactoryMuffin::define('ModelThatWillSaveStub', array());
FactoryMuffin::define('ModelThatFailsToSaveStub', array());
FactoryMuffin::define('ModelThatFailsToDeleteStub', array());
FactoryMuffin::define('ModelThatAlsoFailsToDeleteStub', array());
FactoryMuffin::define('ModelWithNoSaveMethodStub', array());
FactoryMuffin::define('ModelWithNoDeleteMethodStub', array());
FactoryMuffin::define('ModelWithValidationErrorsStub', array());
