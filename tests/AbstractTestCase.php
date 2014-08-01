<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::setFakerLocale('en_EN');
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
        FactoryMuffin::setSaveMethod('save'); // this is not required, but allows you to modify the method name
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete'); // this is not required, but allows you to modify the method name
        FactoryMuffin::deleteSaved();
    }
}
