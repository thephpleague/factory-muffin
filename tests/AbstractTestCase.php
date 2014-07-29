<?php

namespace League\FactoryMuffin\Test;

use League\FactoryMuffin\Facade\FactoryMuffin;
use PHPUnit_Framework_TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    public static function setupBeforeClass()
    {
        FactoryMuffin::loadFactories(__DIR__ . '/factories');
        FactoryMuffin::setSaveMethod('save');
    }

    public static function tearDownAfterClass()
    {
        FactoryMuffin::setDeleteMethod('delete');
        FactoryMuffin::deleteSaved();
    }
}
