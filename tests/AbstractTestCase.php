<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::setSaveMethod('save'); // optional step
        FactoryMuffin::setFakerLocale('en_EN'); // optional step
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete'); // optional step
        FactoryMuffin::deleteSaved();
    }
}
