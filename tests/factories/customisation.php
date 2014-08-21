<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('MakerCustomisationModelStub', array(
    'foo' => 'bar'
));

FactoryMuffin::define('SetterCustomisationModelStub', array(
    'bar' => 'baz'
));

FactoryMuffin::define('SaverAndDeleterCustomisationModelStub', array());
