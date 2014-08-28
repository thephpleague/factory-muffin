<?php

use League\FactoryMuffin\Facades\FactoryMuffin;

FactoryMuffin::define('MakerCustomisationModelStub', array(
    'foo' => 'bar'
));

FactoryMuffin::define('SetterCustomisationModelStub', array(
    'bar' => 'baz'
));

FactoryMuffin::define('SaverAndDeleterCustomisationModelStub', array());
