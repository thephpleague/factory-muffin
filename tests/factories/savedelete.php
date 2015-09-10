<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('ModelThatWillSaveStub', []);
FactoryMuffin::define('ModelThatFailsToSaveStub', []);
FactoryMuffin::define('ModelThatFailsToDeleteStub', []);
FactoryMuffin::define('ModelThatAlsoFailsToDeleteStub', []);
FactoryMuffin::define('ModelWithNoSaveMethodStub', []);
FactoryMuffin::define('ModelWithNoDeleteMethodStub', []);
FactoryMuffin::define('ModelWithValidationErrorsStub', []);
