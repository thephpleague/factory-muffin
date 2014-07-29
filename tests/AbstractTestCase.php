<?php

namespace League\FactoryMuffin\Test;

use League\FactoryMuffin\Facade\FactoryMuffin;
use PHPUnit_Framework_TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
        FactoryMuffin::setSaveMethod('save'); // this is not required, but allows you to modify the method name
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete'); // this is not required, but allows you to modify the method name
        FactoryMuffin::deleteSaved();
    }
}
