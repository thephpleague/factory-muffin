<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('MakerCustomisationModelStub', [
    'foo' => 'bar',
]);

FactoryMuffin::define('SetterCustomisationModelStub', [
    'bar' => 'baz',
]);

FactoryMuffin::define('SaverAndDeleterCustomisationModelStub', []);
